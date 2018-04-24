<div ng-controller="QuestionDetailController">
   <div class="container question_detail">

       <div class="card">
          <span class="grey ">[:Question.current_question.user.username:] , [:Question.current_question.user.intro?item.user.intro:'暫無介紹':]</span>
          <h1>[:Question.current_question.title :]</h1>
          <div class="desc">[:Question.current_question.desc:]



           </div>

           <div>
               <div class="grey">[:Question.current_question.created_at:]</div>
               <span class="grey">回答數: [:Question.current_question.answers_with_user_info.length:]</span>

               <span ng-if="his.id == Question.current_question.user_id"
                     ng-click="Question.show_update_form=!Question.show_update_form"  class="anchor grey">
                   <span ng-if="Question.show_update_form">取消</span>修改問題</span>
           </div>

           <form ng-if="Question.show_update_form"
                 class="well grey_card" ng-submit="Question.update()" name="question_update_form">
               <div class="input-group">
                   <label>問題標題</label>
                   <input type="text" ng-model="Question.current_question.title" name="title"
                          ng-minlength="5" ng-maxlength="255"    required>
               </div>
               <div class="input-group">
                   <label>問題描述</label>
                    <textarea type="text" ng-model="Question.current_question.desc" name="desc">


                    </textarea>
               </div>
               <div class="input-group">
                   <button ng-disabled="question_update_form.title.$invalid"

                           class="primary"   type="submit">提交</button>

               </div>
           </form>




           <div class="hr"></div>
           <div class="feed item clearfix ">
               <div ng-if="!Question.current_answer_id || Question.current_answer_id==item.id "
                    ng-repeat="item in Question.current_question.answers_with_user_info" >

                   <div class="vote" ng-if="item.question_id">

                       <div class="votedUp" ng-if="item.userUpvoted==true" ng-click="Question.vote({id: item.id,vote: 1})">讚 [:  item.upvote_count :]</div>
                       <div class="up" ng-if="item.userUpvoted==false" ng-click="Question.vote({id: item.id,vote: 1})">讚 [:  item.upvote_count :]</div>
                       <div class="down" ng-if="item.userDownvoted==false" ng-click="Question.vote({id: item.id,vote: 2})"> 踩 [:  item.downvote_count :]</div>
                       <div class="votedDown" ng-if="item.userDownvoted==true" ng-click="Question.vote({id: item.id,vote: 2})"> 踩 [:  item.downvote_count :]</div>




                   </div>

<div class="feed-item-content">

    <div ><span class="grey" ui-sref="user({id: item.user.id})">   [: item.user.username:]</span>
     </div>

    <div ng-if="item.question_id" class="content-main">[: item.content:]





        <div class="action-set">


       <span class="grey">

            <span ui-sref="question.detail( {id: item.question_id, answer_id: item.id} )">[: item.updated_at :]</span>
           <span class="anchor" ng-click="item.show_comment=!item.show_comment">
               <span ng-if="item.show_comment">取消</span>評論</span>
            <span  ng-if="item.user_id ==his.id">
                <span ng-click="Answer.answer_form=item" class="anchor">編輯</span>
            <span ng-click="Answer.deleteConfirm(item.id)" class="anchor">刪除</span>

            </span>

        </span>



        </div>





    </div>


</div>
                   <div  ng-if="item.show_comment" comment-block answer-id="item.id">

comment
                   </div>

                   <div class="hr"></div>



               </div>
           </div>
<div>
    <form
          ng-submit="Answer.add_or_update(Question.current_question.id)" name="answer_form" class="answer_form">
        <div class="input-group">
            <div ng-if="Question.current_question.answers_with_user_info.length==0">
                <div class="tac grey well" >還沒有回答,快來搶沙發</div>
                <div class="hr"></div>
            </div>

            <label >回答: </label>

               <textarea type="text"
                         minlength="5"
                         ng-model="Answer.answer_form.content"
                         name="content" required>


                    </textarea>

        </div>

        <div class="input-group">
            <button ng-disabled="answer_form.$invalid"

                    class="primary"   type="submit">提交</button>

        </div>
    </form>
</div>
       </div>


   </div>
</div>