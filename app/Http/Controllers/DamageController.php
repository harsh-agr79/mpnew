<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageController extends Controller
{
    public function tickets(Request $request){
        $result['data'] = DB::table('damage')->orderBy('date', 'DESC')->groupBy('invoiceid')->get();
        return view('damage/tickets', $result);
    }
    public function editticket(Request $request, $id){
        $result['data'] = DB::table('damage')->where('invoiceid', $id)->groupBy('item')->get();
        return view('damage/editticket', $result);
    }
    public function edittkt_pro(Request $request){
        // dd($request->post());
        $invoice = $request->post('invoice');
        $date = $request->post('date');
        $name = $request->post('name');
        $cusuni_id = $request->post('cusid');
        $item = $request->post('prod', []);
        $prodid = $request->post('prodid', []);
        $quantity = $request->post('quantity', []);
        $cusremarks = $request->post('cusremarks', []);
        $dqty = $request->post('dqty', []);
        $condition = $request->post('condition', []);
        $warranty = $request->post('warranty', []);
        $problem = $request->post('problem', []);
        $solution = $request->post('solution', []);
        $pop = $request->post('pop', []);
        $adremarks = $request->post('adremarks', []); 
        $category = $request->post('category', []); 
        $wproof = $request->post('warrantyproof', []); 
        $batch = $request->post('batch', []); 

        DB::table('damage')->where('invoiceid', $invoice)->delete();

        for ($i=0; $i < count($dqty); $i++) { 
            if ($dqty[$i] > 0) {
                DB::table('damage')->insert([
                    'date'=>$date,
                    'invoiceid'=>$invoice,
                    'name'=>$name,
                    'cusuni_id'=>$cusuni_id,
                    'item'=>$item[$i],
                    'produni_id'=>$prodid[$i],
                    'quantity'=>$quantity[$i],
                    'grpqty'=>$dqty[$i],
                    'cusremarks'=>$cusremarks[$i],
                    'adremarks'=>$adremarks[$i],
                    'problem'=>$problem[$i],
                    'solution'=>$solution[$i],
                    'condition'=>$condition[$i],
                    'warranty'=>$warranty[$i],
                    'category'=>$category[$i],
                    'pop'=>implode("|", $pop[$i]),
                    'warrantyproof'=>$wproof[$i],
                    'batch'=>$batch[$i],
                ]);
            }
        }

        return redirect('/tickets');
    }
}
