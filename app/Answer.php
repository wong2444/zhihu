<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function add(){
        if(user_ins()->is_logged_in()==false){
            return  err('login required');
        }
        if(!rq('question_id') || !rq('content')){
            return  err('question_id and content are required');
        }
        $question=question_ins()->find(rq('question_id'));

        if(!$question){
            return  err('question not required');
        }

        $answered=$this
            ->where(['question_id'=>rq('question_id'),'user_id'=>session('id')])
        ->count();

        if($answered){
            return  ['status' => 0, 'msg' => 'answer duplicated'];
        }
            $this->content=rq('content');
            $this->question_id=rq('question_id');
            $this->user_id=session('id');
        return $this->save() ?
           suc( ['id' =>$this->id]):
            err('db insert failed');
    }

    public function change(){
        if(user_ins()->is_logged_in()==false){
            return  err('login required');
        }
        if(!rq('id') || !rq('content')){
            return  err('id and content are required');
        }
        $answer=$this->find(rq('id'));
        if($answer->user_id !=session('id')){
            return  err('permission denied');
        }
        $answer->content=rq('content');
        return $answer->save() ?
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



    public function read(){
        if( !rq('id') && !rq('question_id') && !rq('user_id')){

           return  err('id or question_id is required');
        }


        if(rq('user_id')){
            if(rq('user_id')==='self'){
                $user_id=session('id');
            }else{
                $user_id=rq('user_id');
            }
            return $this->read_by_user_id($user_id);
        }

            if(rq('id')){
            $answer=$this->with('user')->with('users')
                ->find(rq('id'));
            if(!$answer){
                return  err('answer not exists');

            }
                $answer=$this->count_vote($answer);
               return ['status'=>1,'data' =>$answer];
        }


        if(!question_ins()->find(rq('question_id'))){
            return  err('question not exists');
        }

$answers=$this
    ->where('quesion_id',rq('question_id'))
    ->get()
    ->keyBy('id');

        return ['status'=>1,'data' =>$answers];
    }


    public function count_vote($answer){
$upvote_count=0;
        $downvote_count=0;
        $id=session('id');
        $userUpvoted=false;
        $userDownvoted=false;
        foreach ($answer->users as $user){
            if($user->pivot->vote==1){
                $upvote_count++;
                if($user->pivot->user_id==$id){
                    $userUpvoted=true;

                }
            }else if ($user->pivot->vote==2){
                $downvote_count++;
                if($user->pivot->user_id==$id){
                    $userDownvoted=true;

                }
            }
        }
        $answer->upvote_count=$upvote_count;
        $answer->downvote_count=$downvote_count;
        $answer->userUpvoted=$userUpvoted;
        $answer->userDownvoted=$userDownvoted;
        return $answer;
    }





    public function remove(){
        if(!user_ins()->is_logged_in()){
            return  err('login required');
        }
        if(!rq('id')){
            return  err('id required');
        }
        $answer=$this->find(rq('id'));
        if(!$answer){
            return  err('answer not exist');
        }
        if(session('id')!=$answer->user_id){
            return  err('permission denied');
        }

        $answer->users()->newPivotStatement()
            ->where('answer_id',rq('id'))
            ->delete();
comment_ins()->where('answer_id',rq('id'))->delete();


        return $answer->delete() ?
            suc():
            err('db delete failed');

    }




    public function vote(){
        if(user_ins()->is_logged_in()==false){
            return  err('login required');
        }
        if(!rq('id') || !rq('vote')){
            return err('id and vote are required');
        }
        $answer=$this->find(rq('id'));



        if(!$answer){
            return err('answer not exists');
        }
        $vote=rq('vote');
        if($vote !=1 && $vote!=2&& $vote!=3){
            return err('invalid vote');
        }



    //沒有記錄返回null
  $answer->users()->newPivotStatement()->where('user_id',session('id'))
         ->where('answer_id',rq('id'))
            ->delete();
// ->newPivotStatement()找到中間表
            //$answer->users()返回關係
        if($vote==3){
            return suc();
        }


        //用關係方法寫入user_id
        $answer->users()->attach(session('id'),['vote'=>$vote]);

return ['status'=>1];
    }

    public function user(){
        return $this->belongsTo('App\User');
    }



    public function users(){
        return $this
            ->belongsToMany('App\User') 
            ->withPivot('vote') //laravel在多對多的表中默認只存兩字段,多出的要注冊
            ->withTimestamps();//強制時間更新
    }

public function question(){
    return $this->belongsTo('App\Question');
}


}
