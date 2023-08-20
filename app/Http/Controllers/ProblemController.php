<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProblemController extends Controller
{
    public function problem(Request $request){
        $result['data'] = DB::table('problem')->orderBy('id', 'DESC')->get();
        return view('damage/problem',$result);
    }

    public function addproblem(Request $request, $id = ''){
        if($id !== ""){
            $bat = DB::table('problem')->where('id', $id)->first();
            $result['problem'] = $bat->problem;
            $result['category'] = $bat->category;
            $result['id'] = $bat->id;
        }
        else{
            $result['problem'] = '';
            $result['category'] = '';
            $result['id'] = '';
        }
        return view('damage/addproblem', $result);
    }
    public function addproblem_process(Request $request){
       $problem = $request->post("problem");
       $category = $request->post("category");
       $id = $request->post('id');
       if($id > 0){
        DB::table('problem')->where('id',$id)->update([
            'problem'=>$problem,
            'category'=>$category,
        ]);
       }
       else{
        DB::table('problem')->insert([
            'problem'=>$problem,
            'category'=>$category,
           ]);
       }
       return redirect('/problem');
    }
    public function delproblem($id){
        DB::table('problem')->where('id', $id)->delete();
        return redirect('/problem');
    }
}
