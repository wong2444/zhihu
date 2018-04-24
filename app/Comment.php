<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function add(){
        if(user_ins()->is_logged_in()==false){
            return err('login required');
        }

        if(!rq('content')){
            return err('empty content');
        }

        if((!rq('question_id') && !rq('answer_id')) ||(!rq('question_id') && !rq('answer_id'))  ){
            return err('question_id or answer_id is required');
        }

        if(rq('question_id')){
$question=question_ins()->find(rq('question_id'));
            if(!$question){
                return err('question not exists');
            }
            $this->question_id=rq('question_id');
        }else{
            $answer=answer_ins()->find(rq('answer_id'));
            if(!$answer){
                return err('answer not exists');
            }
            $this->answer_id=rq('answer_id');
        }

        if(rq('reply_to')){
            $target=$this->find(rq('reply_to'));
            if(!$target){
                return err('target comment not exists');
            }
            if($target->user_id ==session('id')){
                return err('cannot reply to yourself');
            }


            $this->reply_to=rq('reply_to');
        }


        $this->content=rq('content');
        $this->user_id=session('id');
return $this->save() ?
   suc(['id'=>$this->id]) :
    err('db insert failed');



    }



    public function change(){
        if(user_ins()->is_logged_in()==false){
            return  err('login required');
        }
        if(!rq('id') || !rq('content')){
            return  err('id and content are required');
        }
        $comment=$this->find(rq('id'));
        if($comment->user_id !=session('id')){
            return  err('permission denied');
        }
        $comment->content=rq('content');
        return $comment->save() ?
            suc() :
            err('db update failed');
    }

    public function read_by_user_id($user_id){
        $user=user_ins()->find($user_id);
        if(!$user){
            err('user not exists');
        }
        $r= $this->where('user_id',$user_id)->with('question')->get()->keyBy('id');
        return suc($r->toArray());


    }
    
    
    
    
    
    
    


    public function user(){
        return $this->belongsTo('App\User');
    }



    public function read(){

        if(!rq('question_id') && !rq('answer_id')){
            return err('question_id or answer_id is required');
        }



        if(rq('quesion_id')){
            $question=question_ins()->find(rq('question_id'));
            if(!$question){
                return err('question not exists');
            }
            $data=$this->where('question_id',rq('question_id'))->get();
        }
        else{
            $answer=answer_ins()->find(rq('answer_id'));
            if(!$answer){
                return err('answer not exists');
            }
            $data=$this->with('user')->where('answer_id',rq('answer_id'))->get();
        }
        return ['status'=>0,'data'=>$data->keyBy('id')];
    }

    public function remove(){
        if(!user_ins()->is_logged_in()){
            return err('login required');

        }
if(!rq('id')){
    return err('id is required');
}
        $comment=$this->find(rq('id'));
        if(!$comment){
            return err('comment not exists');
        }

        if($comment->user_id != session('id')){
            return err('permission denied');
        }

        $this->where('id',rq('id'))->delete();


return $comment->delete() ?
   suc() :
    err('db delete failed');
    }




}
