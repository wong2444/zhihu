<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

function question_ins(){
    return new App\Question;
}
function user_ins(){
    return new App\User;
}
function answer_ins(){
    return new App\Answer;
}

function comment_ins(){
    return new App\Comment;
}

function paginate($page=1,$limit=16){
    $limit=$limit ? :16;
    $skip=($page ? $page -1:0) *$limit;
    return [$limit,$skip];
}

function rq($key=null,$default=null){
    if(!$key){
        return \Illuminate\Support\Facades\Request::all();
    }
    return \Illuminate\Support\Facades\Request::get($key,$default);
}

function err($msg=null){
    return ['status'=>0,'msg'=>$msg];
}
function suc($data_to_merge=[]){
    $data=['status'=>1,'data'=>[]];
    if($data_to_merge){
        $data['data']=$data_to_merge;
    }
         
    return $data;
}
 function  is_logged_in(){
    if(session('id')==null){
        return false ;
    }
    return session('id');
}

Route::get('tpl/page/home',function(){
   return view('page.home');
});
Route::get('tpl/page/signup',function(){
    return view('page.signup');
});
Route::get('tpl/page/login',function(){
    return view('page.login');
});

Route::get('tpl/page/question_add',function(){
    return view('page.question_add');
});

Route::get('tpl/page/question_detail',function(){
    return view('page.question_detail');
});

Route::get('tpl/page/user',function(){
    return view('page.user');
});


Route::get('/', function () {
    return view('index');
});

Route::any('api/signup', function () {
   
    return user_ins()->signup();
});


Route::any('api/login', function () {
   
    return user_ins()->login();
});


Route::any('api/logout', function () {

    return user_ins()->logout();
});

Route::any('api/user/read', function () {

    return user_ins()->read();
});



Route::any('api/user/change_password', function () {

    return user_ins()->change_password();
});


Route::any('api/user/reset_password', function () {

    return user_ins()->reset_password();
});

Route::any('api/user/validate_reset_password', function () {

    return user_ins()->validate_reset_password();
});

Route::any('api/user/exist', function () {

    return user_ins()->exist();
});



Route::any('api/question/add', function () {
    $result=question_ins()->add();
    
    return $result;
});

Route::any('api/question/change', function () {
    $result=question_ins()->change();

    return $result;
});

Route::any('api/question/read', function () {
    $result=question_ins()->read();

    return $result;
});

Route::any('api/question/remove', function () {
    $result=question_ins()->remove();

    return $result;
});

Route::any('api/answer/add', function () {
    $result=answer_ins()->add();

    return $result;
});

Route::any('api/answer/change', function () {
    $result=answer_ins()->change();

    return $result;
});
Route::any('api/answer/read', function () {
    $result=answer_ins()->read();

    return $result;
});
Route::any('api/answer/remove', function () {
    $result=answer_ins()->remove();

    return $result;
});

Route::any('api/answer/vote', function () {
    $result=answer_ins()->vote();

    return $result;
});



Route::any('api/comment/add', function () {
    $result=comment_ins()->add();

    return $result;
});

Route::any('api/comment/read', function () {
    $result=comment_ins()->read();

    return $result;
});

Route::any('api/comment/remove', function () {
    $result=comment_ins()->remove();

    return $result;
});

Route::any('api/comment/change', function () {
    $result=comment_ins()->change();

    return $result;
});


Route::any('api/timeline','CommonController@timeline');