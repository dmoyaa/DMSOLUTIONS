<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController
{
    public function index(){
        $users = DB::table('users_rol')->get();
        return view('administration', ['users' => $users]);
    }

    public function addUser(Request $request)
    {
        $user = new User();
        $user = DB::table('users')->where('user_email',$request->input('userEmail'))->get();
        $message = "";
        if($user -> isEmpty()){
            $user = new User();
            $user -> id = 0;
            $user -> user_first_name = $request->input('userNames');
            $user -> user_last_name = $request->input('userLastNames');
            $user -> user_email = $request->input('userEmail');;
            $user->user_password = password_hash($request->input('userPassword'), PASSWORD_ARGON2ID);
            $user -> user_role = $request->input('menuRol');
            $user -> user_status = 1;

            try {
                $user -> save();

            }catch (Exception $e) {
                $message = $e->getMessage();
            }
        }
        else{
            $message = "Email already exists!";
            session()->flash('status', $message);
        }
        return to_route('administration');
    }

    public function consultRole(){
        $roles = DB::table('roles')->get();
        return $roles;
    }

    public function destroy(Request $request){
        $id = $request->id;
        $user = User::find($request->id);
        $user->delete();
        return to_route('administration');
    }

    public function login(Request $request)
    {
        $user = DB::table('users')->where('user_email',$request->email)
            ->first();
        if (!$user) {
            return back()->with('status', 'Usuario no encontrado');
        }

        if (password_verify($request->input('password'), $user->user_password)) {
            session(['user_id' => $user->id, 'user_name' => $user->user_first_name]);
            return redirect()->route('home');
        } else {
            return back()->with('status', 'Contraseña incorrecta');
        }
    }

    public function consultUserDetail(Request $request){
        $user = DB::table('users')->where('id',$request->userDetail)->get()->first();
        return $user;
    }

    public function updateUser(Request $request, User $user){
        $user = User::find($request->hiddenUserId);
        $user -> user_first_name = $request->input('updateUserNames');
        $user -> user_last_name = $request->input('updateUserLastNames');
        $user -> user_email = $request->input('updateUserEmail');
        if($request->input('updateUserPassword') == null){

        }else{
            $user -> user_password = password_hash($request->input('updateUserPassword'), PASSWORD_ARGON2ID);
        }
        $user -> user_role = $request->input('menuActualizarRol');
        $user -> user_status = 1;
        try{
            $user -> save();
            return to_route('administration');
        }catch (Exception $e){
            $message = $e->getMessage();
            to_route('administration')->with('error', $message);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_id'); // Elimina el valor
        $request->session()->flush();

        return redirect()->route('login'); // Redirige al login
    }

}
