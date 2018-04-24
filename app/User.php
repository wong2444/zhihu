<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;


class User extends Model
{
    // public $table = 'user_table';
    public function signup(){

        $has_username_and_password=$this->has_username_and_password();
       if(!$has_username_and_password){
           return err('username and password can not be null');
       }
        $username= $has_username_and_password[0];
        $password= $has_username_and_password[1];
     $user_exists=$this->where('username',$username)->exists();
        if($user_exists){
            return err('user exists');
        }

        $hashed_password=Hash::make($password);
        $this->password=$hashed_password;
        $this->username=$username;

        if($this->save()){
            return suc(['id'=>$this->id]);
        }else{
            return err('db insert fail');
        }
    }


    public function read(){
        if(!rq('id')){
            return err('required');
        }


        if(rq('id')==='self'){
            if(!$this->is_logged_in()){
                return err('login required');
            }
            $id=session('id');
        }else{
            $id=rq('id');
        }

$get=['id','username','avatar_url','intro'];
        $user=$this->find($id,$get);


      $data= $user->toArray();
        $answer_count=answer_ins()->where('user_id',$id)->count();
        $question_count=question_ins()->where('user_id',$id)->count();

        $data['answer_count']=$answer_count;
        $data['question_count']=$question_count;
        return suc($data);
    }


    public function login(){
        $has_username_and_password=$this->has_username_and_password();
        if(! $has_username_and_password){
            return err('username and password are required');
        }
        $username= $has_username_and_password[0];
        $password= $has_username_and_password[1];

        $user=$this->where('username',$username)->first();

        if(!$user){
              return err('user not exists');
        }
        $hashed_password=$user->password;
        if(!Hash::check($password,$hashed_password)){
            return  ['status'=>0, 'msg'=>'invalid password'];
        }

        session()->put('username',$user->username);
        session()->put('id',$user->id);
       
        return suc(['id' =>$user->id]);
        
    }

public function has_username_and_password(){
    $username=rq('username');
    $password=rq('password');

    if($username && $password){
        return [$username,$password];
    }

    return false;
}

    public function logout(){
        session()->forget('username');
        session()->forget('id');

        //session()->set('person.name','xiaoming')
        //session()->set('person.friend.hanmeimei.age',20)

      //  session()->flush();
        // return redirect('/');

        return  suc();
    }


    public function  is_logged_in(){
        if(session('id')==null){
            return false ;
        }
      return session('id');
    }

    public function change_password(){
        if(!$this->is_logged_in()){
            return err('login required');
        }

        if(!rq('old_password') || !rq('new_password')){
            return err('old_password and new_password are required');
        }
        $user=$this->find(session('id'));
        // dd($user);
if(!Hash::check(rq('old_password'),$user->password)){
    return err('invalid old_password');
}
        $user->password=bcrypt(rq('new_password'));
        return $user->save() ?
           suc():
           err('db update failed');


    }


    public function reset_password(){

        if($this->is_robot()){

            return err('max frequency reached');
        }

        if(!rq('phone')) {
           return err('phone is required');
        }
        $user=$this->where('phone',rq('phone'))->first();

        if(!$user){

            return  err('invalid phone number');
        }
        $captcha=$this->generate_captcha();
        $user->phone_captcha=$captcha;

if($user->save()){

    $this->send_sms();
    $this->update_root_time();

    return suc();
}

          return  err('db update failed');
    }


    public function validate_reset_password(){



        if($this->is_robot(2)){

            return err('max frequency reached');
        }

        if(!rq('phone') || !rq('phone_captcha') || !rq('new_password')){
            $this->update_root_time();
            return err('phone and phone_captcha are required');
        }
        $user=$this->where(['phone'=>rq('phone'),'phone_captcha'=>rq('phone_captcha')])->first();
        if(!$user){
            $this->update_root_time();
            return err('invalid phone or invalid phone_captcha');
        }

        $user->password=bcrypt(rq('new_password'));
        $this->update_root_time();
        return $user->save() ?
            suc():err('db update failed');
    }



    public function is_robot($time=10){
        if(!session('last_action_time')){
            return false;
        }
        $current_time=time();
        $last_active_time=session('last_action_time');
        $elapsed=$current_time-$last_active_time;

        if($elapsed>$time){
            return false;
        }
             return true;
    }

    public function update_root_time(){
        session()->put('last_action_time',time());

    }


public function exist(){
    return suc(['count'=>$this->where(rq())->count()]);

}



    public function send_sms(){
        return true;
    }


    public function generate_captcha(){
        return rand(1000,9999);
    }

    public function answers(){
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }



}
