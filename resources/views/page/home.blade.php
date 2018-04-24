<div ng-controller="HomeController" class="home container card">
    <h1>最新動態</h1>
    <div class="hr"></div>

    <div class="item-set">

        <div ng-repeat=" item in Timeline.data | filter:Timeline.searchText" class="feed item clearfix" ng-if="item.length!=0">

<span ui-sref="user({id: item.user.id})" class="grey ">[:item.user.username:] , [:item.user.intro?item.user.intro:'暫無介紹':]</span>
            <span ui-sref="question.detail({id: item.id})">    <h1>[: item.title :]</h1></span>
            <div ng-if="item.desc.length>60" class="desc AutoSkip" id=[:item.id:] >[:item.desc:]</div>
            <div ng-if="item.desc.length<60" class="desc"  >[:item.desc:]</div>
            <a ng-if="item.desc.length>60"  id=[:item.title:]  class="grey anchor" ng-click="Timeline.extendOrHide(item.title,item.id)"> 展開</a>
            <div class="grey">[:item.created_at:]</div>
            <div>

                <span class="grey anchor" ng-click="item.show_ans=!item.show_ans">回答數: [:item.answers_with_user_info.length:]</span>

               <span ng-if="his.id == item.user_id"
                     ng-click="Timeline.openEdit(item)"  class="anchor grey">
                   <span ng-if="item.show_update_form">取消</span>修改問題</span>
                <span ng-if="his.id == item.user_id"
                      ng-click="Timeline.deleteQuestion(item.id)"  class="anchor grey">刪除問題</span>
            </div>

            <form ng-if="item.show_update_form"
                  class="well grey_card" ng-submit="Timeline.update_question(item)" name="question_update_form">
                <div class="input-group">
                    <label>問題標題</label>
                    <input type="text" ng-model="item.title" name="title"
                           ng-minlength="5" ng-maxlength="255"    required>
                </div>
                <div class="input-group">
                    <label>問題描述</label>
                    <textarea type="text" ng-model="item.desc" name="desc">


                    </textarea>
                </div>
                <div class="input-group">
                    <button ng-disabled="question_update_form.title.$invalid"

                            class="primary"   type="submit">提交</button>


                    <button class="primary" ng-click="Timeline.cancel(item)"  >取消</button>

                </div>
            </form>





            <div ng-if="item.show_ans"   class="feed item clearfix ">
                <div class="hr"></div>
                <div ng-if="item.answers_with_user_info.length!=0 "
                     ng-repeat="ans in item.answers_with_user_info" >

                    <div class="vote" ng-if="ans.question_id">

                        <div class="votedUp" ng-if="ans.userUpvoted==true" ng-click="Timeline.vote({id: ans.id,vote: 1})">讚 [:  ans.upvote_count :]</div>
                        <div class="up" ng-if="ans.userUpvoted==false" ng-click="Timeline.vote({id: ans.id,vote: 1})">讚 [:  ans.upvote_count :]</div>
                        <div class="down" ng-if="ans.userDownvoted==false" ng-click="Timeline.vote({id: ans.id,vote: 2})"> 踩 [:  ans.downvote_count :]</div>
                        <div class="votedDown" ng-if="ans.userDownvoted==true" ng-click="Timeline.vote({id: ans.id,vote: 2})"> 踩 [:  ans.downvote_count :]</div>


                    </div>

                    <div class="feed-item-content">

                        <div ><span ui-sref="user({id: item.user.id})">   [: ans.user.username:]</span>
                        </div>

                        <div ng-if="ans.question_id" class="content-main">[: ans.content:]





                            <div class="action-set">


       <span class="grey">

            <span ui-sref="question.detail( {id: ans.question_id, answer_id: ans.id} )">[: ans.updated_at :]</span>
           <span class="anchor" ng-click="ans.show_comment=!ans.show_comment">
               <span ng-if="ans.show_comment">取消</span>評論</span>
            <span  ng-if="ans.user_id ==his.id">
                <span ng-click="Answer.answer_form=ans" class="anchor">編輯</span>
            <span ng-click="Timeline.delete(ans.id)" class="anchor">刪除</span>

            </span>

        </span>



                            </div>

</div>
                        </div>

                    <div  ng-if="ans.show_comment" comment-block answer-id="ans.id">

                        comment
                    </div>
                   
                    <div class="hr"></div>
                    


                </div>
            </div>
            <div  ng-if="item.show_ans">

                <form
                        ng-submit="Timeline.add_or_update(item.id)" name="answer_form" class="answer_form">
                    <div class="input-group">
                        <div ng-if="item.answers_with_user_info.length==0">

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





            <div class="hr"></div>
        </div>
        <div ng-if="Timeline.pending" class="tac">加載中...</div>
        <div ng-if="Timeline.no_more_data" class="tac">沒有更多數據了</div>
    </div>

</div>
