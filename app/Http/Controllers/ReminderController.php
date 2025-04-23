<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReminderController
{
    public function index(){
        $reminders = DB::table('reminders')->orderBy('reminder_date', 'asc')->get();
        return view('reminders', ['reminders' => $reminders]);
    }

    public function destroy(Request $request){
        $reminder = Reminder::find($request->id);
        try{
            $reminder->delete();
            return to_route('reminders');
        }catch(\Exception $e){
            return to_route('reminders')->with('status',$e->getMessage());
        }
    }
}
