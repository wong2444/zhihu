<!DOCTYPE html>
<html ng-controller="BaseController" lang="en" ng-app="xiaohu" user-id="{{session('id')}}">
<head>
    <meta charset="UTF-8">
    <title>曉乎</title>
     <link rel="stylesheet"  href="public/node_modules/normalize-css/normalize.css">
    <script  type="text/javascript"  src="public/node_modules/jquery/dist/jquery.js"></script>
    <script  type="text/javascript"  src="public/node_modules/angular/angular.js"></script>
    <script  type="text/javascript"  src="public/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script type="text/javascript" src="public/js/base.js"></script>
    <script type="text/javascript" src="public/js/common.js"></script>
    <script type="text/javascript" src="public/js/question.js"></script>
    <script type="text/javascript" src="public/js/user.js"></script>
    <script type="text/javascript" src="public/js/answer.js"></script>
    <link rel="stylesheet" href="public/css/base.css">
</head>
<body>

<div class="navbar clearfix" >
    <div class="container">
        <div class="fl">
            <div ui-sref="home" class="navbar-item brand">曉乎</div>
         {{--   <form id="quick_search" ng-controller="QuestionAddController" ng-submit="Question.go_add_question()"> --}}
                <div class="navbar-item" >
                    <input type="text" placeholder="搜索....." ng-controller="HomeController"   ng-model="Timeline.searchText">




                </div>
                {{--<div class="navbar-item">--}}
                    {{--<button type="submit">搬索</button>--}}
                {{--</div>--}}
                {{--</form>--}}
                <form id="quick_ask" ng-controller="QuestionAddController" ng-submit="Question.go_add_question()">
                <div class="navbar-item">
                    <button type="submit">提問</button>
                </div>
            </form>

        </div>
        <div class="fr">
            <div  class="navbar-item" ui-sref="home">首頁</div>
            @if(is_logged_in())
                <div  class="navbar-item" ui-sref="user({id:{{session('id')}}})">{{session('username')}}</div>
               
          <div class="navbar-item"><span style="color: white"   ng-controller="LoginController" ng-click="User.logout()" class="anchor">登出</span></div>
            @else
            <div  class="navbar-item" ui-sref="signup">注冊
                <!-- ui-sref用於頁面跳轉類似 href -->
            </div>
            <div  class="navbar-item" ui-sref="login">登錄</div>
                @endif
        </div>


    </div>

</div>

<div class="page">
    <div ui-view=""></div>
</div>

<script type="text/ng-template" id="comment.tpl">
    <!-- type="text/ng-template" id="comment.tpl"是angular-ui-router的路由模板 -->
    <!-- 必須有一個最大的元素包住所有元素 -->
    <div class="comment-block">
        <div class="hr"></div>
        <div class="comment-item-set">

            <div class="rect"></div>
             <div class="grey tac well" ng-if="!helper.obj_length(data)">暫無評論</div>
            <div ng-if="helper.obj_length(data)" ng-repeat="item in data" class="comment-item clearfix">

                <div class="user">[:item.user.username:]</div>
                <div class="comment-content">[:item.content:] <span class="grey anchor" ><span  ng-click="_.reply_comment(item)">
                            回覆</span><span ng-if="item.user.id==id" ng-click="_.edit_comment(item)"> 編輯 </span><span ng-if="item.user.id==id" ng-click="_.delete_comment(item)">  刪除</span></span>
                   </div>

            </div>

        </div>

<div class="input-group ">

    <form ng-submit="_.addOrEdit_comment()"  class="comment_form">
        <input type="text" ng-model="Answer.new_comment.content"  placeholder="說些什麼...">
        <button class="primary" type="submit">評論</button>
    </form>

</div>



    </div>

</script>

</body>




</html>