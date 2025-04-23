<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StatusController
{
    public function index()
    {
        $statuses = DB::table('statuses')->get();
        return $statuses;
    }
}
