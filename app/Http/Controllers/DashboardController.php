<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function proj_status(){
        $proj_status = DB::table('project_statuses')->get();
        return $proj_status;
    }

    public function proj_clients(){
        $proj_clients = DB::table('projects_by_clients')->get();
        return $proj_clients;
    }

    public function proj_month()
    {
        $proj_month = DB::table('projects_by_month')->get();
        return $proj_month;
    }

    public function quotes_with_no_projects(){
        $quotesWithoutProjects = DB::table('quotes')
            ->selectRaw(' MONTH(quote_expiration_date) as mes, COUNT(*) as cantidad')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('projects')
                    ->whereRaw('projects.quote_id = quotes.id');
            })
            ->groupBy(DB::raw('YEAR(quote_expiration_date), MONTH(quote_expiration_date)'))
            ->orderBy(DB::raw('YEAR(quote_expiration_date)'))
            ->orderBy(DB::raw('MONTH(quote_expiration_date)'))
            ->get();
        return $quotesWithoutProjects;
    }

    public function proj_count()
    {
        $count = DB::table('projects')->count();
        return $count;
    }
}
