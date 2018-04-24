<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function timeline(){
        list($limit,$skip)=paginate(rq('page'),rq('limit'));
        $questions=question_ins()
            ->with('user')
            ->with('answers_with_user_info')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();

        $questions=$questions->sortByDesc(function($item){
            return $item->created_at;
        });
        $questions=$questions->values()->all();
        // dd($questions);
       // $answers=answer_ins()
       //     ->with('question')
       //     ->with('user')
       //     ->with('users')
       //     ->limit($limit)
       //     ->skip($skip)
       //     ->orderBy('created_at','desc')
       //     ->get();

       // $answers=$answers->sortByDesc(function($item){
       //     return $item->created_at;
       // });

       // $answers=$answers->values()->all();

       //  $data=$questions->merge($answers);
        
       //  $data=$data->sortByDesc(function($item){
       //     return $item->created_at;
       // });
       //  $data=$data->values()->all();
        
//
//        $data=array_merge($questions,$answers);
        
        return suc($questions);

    }
}
