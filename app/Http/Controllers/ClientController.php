<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Echo_;

class ClientController
{
    public function index()
    {
        $clients = Client::get();
        return view('clients', ['clients' => $clients]);
    }

    public function store (Request $request ){
        $client = new Client();
        $client = DB::table('clients')->where('client_identification',$request->input('clientIdentification'))->get();
        $message = "";
        if($client -> isEmpty()){
            $client = new Client();
            $client -> id = 0;
            $client -> client_name = $request->input('clientName');
            $client -> client_ph = $request->input('clientPhone');
            $client -> client_sec_ph = null;
            $client -> client_email = $request->input('clientEmail');
            $client -> client_identification = $request->input('clientIdentification');
            $client -> client_address = $request->input('clientAddress');

            try {
                $client -> save();
                $message = 'Client successfully added!';
                session()->flash('status', $message);
            }catch (Exception $e) {
                $message = $e->getMessage();
            }
        }
        else{
            $message = "Client already exists!";
            session()->flash('status', $message);
        }
        return to_route('clients')->with('status', $message);
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return to_route('clients');
    }

    public function update(Request $request)
    {
        $message = "";
        $client = Client::where('client_identification',$request->input('clientIdentification'))->get()->first();
        $client -> client_name = $request->input('clientName');
        $client -> client_ph = $request->input('clientPhone');
        $client -> client_sec_ph = null;
        $client -> client_email = $request->input('clientEmail');
        $client -> client_identification = $request->input('clientIdentification');
        $client -> client_address = $request->input('clientAddress');
        try {
            $client -> save();
            $message = 'Client successfully updated!';
        }catch (Exception $e) {
            $message = $e->getCode();
        }

        return to_route('clients')->with('status', $message);
    }
}
