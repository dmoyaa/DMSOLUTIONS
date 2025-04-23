<?php  namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueProductsController {
    public function index(Request $request){
        //$detail = DB::table('quote_materials')->where('quote_id',$request->id)->get();
        $extraCosts = DB::table('extra_costs')
            ->where('quote_id', $request->id)
            ->get();
        $quote_detail = DB::table('quote_detail')->where('quote_id',$request->id)->get();

        //return $detail;
        return response()->json([
            'materials' => $quote_detail,
            'extra_costs' => $extraCosts
        ]);
    }
    public function consult(Request $r)     {
        $detail = DB::table('quotes')
            ->select('quote_helpers', 'quote_helper_payday', 'quote_supervisor_payday', 'quote_work_total','quote_other_costs_total')
            ->where('id', $r->id)->get();

        return $detail;
    }
}
