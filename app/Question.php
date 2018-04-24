<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public  function add(){


          if(user_ins()->is_logged_in()==false) {
              return err('login required');
          }
              if(rq('title')==null){
                    return err('title required');
              }
        $this->title=rq('title');
        $this->user_id=session('id');
        if(rq('desc')){
            $this->desc=rq('desc');
        }


return $this->save() ?
   suc([ 'id' => $this->id])  :
    err('db insert failed');

          }



    public function change(){
        if(!user_ins()->is_logged_in()){
            return  err('login required');
        }

if(!rq('id')){
    return  err('id required');
}

        $question=$this->find(rq('id'));

        if(!$question){
            return err('question not exists');
        }


        if($question->user_id !=session('id') ){
            return  err('permission denied');
        }
if(rq('title')){
    $question->title=rq('title');
}
        if(rq('desc')){
            $question->desc=rq('desc');
        }

return $question->save() ?
   suc() :
   err('db update failed');

    }


    public function read_by_user_id($user_id){
        $user=user_ins()->find($user_id);
        if(!$user){
            err('user not exists');
        }
        $r= $this->where('user_id',$user_id)->get()->keyBy('id');
        return suc($r->toArray());


    }










    public function read(){
    if(rq('id')){
        $r=$this->with('user')->with('answers_with_user_info')->find(rq('id'));
        return ['status'=>1,'data'=>$r];
    }

        if(rq('user_id')){
$user_id=rq('user_id');
            if($user_id=='self'){

                $user_id=session('id');
            }


            return $this->read_by_user_id($user_id);
        }



    list($limit,$skip)=paginate(rq('page'),rq('limit'));


    $r=$this->orderBy('created_at')->limit($limit)->skip($skip)
        ->get(['id','title','desc','user_id','created_at','updated_at'])->keyBy('id');

    return suc(['data'=>$r]);
}



    public function remove(){
        if(!user_ins()->is_logged_in()){
            return  err('login required');
        }
        if(!rq('id')){
            return  err('id required');
        }
        $question=$this->find(rq('id'));
        if(!$question){
            return  err('question not exist');
        }
        if(session('id')!=$question->user_id){
            return  err('permission denied');
        }
$answer_count=answer_ins()->where('question_id',rq('id'))->get()->count();
        
        if($answer_count>0){
            return  err('can not delete your question, it have answers' );
        }



return $question->delete() ?
           suc():
          err('db delete failed');

    }

public function user(){
    return $this->belongsTo('App\User');
}

    public  function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function answers_with_user_info()
    {
        return $this->answers()->with('user')->with('users');
    }

    
}
