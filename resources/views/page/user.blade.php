<div ng-controller="UserController">
<div class="user container  ">
    <div class="card">

        <h1>用戶詳情</h1>
        <div class="hr"></div>
        <div class="basic">
            <div class="info_item clearfix" >

                <div>username</div>
                <div>
                    [: User.current_user.username :]
                </div>
            </div>

            <div class="info_item clearfix" >

                <div>intro</div>
                <div>
                    [: User.current_user.intro || '暫無介紹' :]
                </div>
            </div>



        </div>

        <h2>用戶提問</h2>
        <div ng-repeat=" (key, value) in User.his_questions">
            <span ui-sref="question.detail({id: value.id})" >   [: value.title :]</span>


        </div>

        <br/>


        <h2>用戶回答</h2>
        <div class="feed item" ng-repeat=" (key, value) in User.his_answers">

            <div  class="title">  <span ui-sref="question.detail({id: value.question_id,answer_id:value.id })" >[:value.question.title:]</span></div>

            [: value.content :]

           



        </div>
    </div>

</div>






</div>
