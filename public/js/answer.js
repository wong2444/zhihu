;(function () {
    'use strict';

    angular.module('answer',[])
        .service('AnswerService',['$http','$state',function ($http,$state) {
            var me=this;
            var del=false;
            me.data={};
            me.answer_form={};
            me.rData={};
            me.action='add';
          
            me.new_comment={};
            me.count_vote=function (answers) {
                for(var i=0;i<answers.length;i++){
                    var votes, item=answers[i];

                    if(!item['question_id'] ){
                        continue ;
                    }

                    me.data[item.id]=item;

                    if( !item['users']){
                        continue ;
                    }
                    item.userUpvoted=false;
                    item.userDownvoted=false;
                    item.upvote_count=0;
                    item.downvote_count=0;
                    votes=item['users'];

                    if(votes){
                        for(var j=0;j<votes.length;j++){
                            var v=votes[j];
                            if(v['pivot'].vote==1){
                                item.upvote_count++;
                                if(v['pivot'].user_id==his.id){
                                    item.userUpvoted=true;
                                }


                            }
                            if(v['pivot'].vote==2){
                                item.downvote_count++;
                                if(v['pivot'].user_id==his.id){
                                    item.userDownvoted=true;
                                }
                            }
                        }
                    }

                  //     console.log(item);
                }
//console.log(answers);
                return answers;
            };

            me.add_or_update=function (question_id) {

                if(!question_id){
                    console.error('question_id is required');
                    return ;
                }


                me.answer_form.question_id=question_id;
                if(me.answer_form.id)
                {
                    $http.post('api/answer/change',me.answer_form)
                        .then(function (r) {
                            if(r.data.status){
                               


                                console.log('edit successfully!');

                                me.answer_form={};
                                me.update_data(r.data.data.id);
                                 $state.reload();
                            }
                        })

                }else{
                    $http.post('api/answer/add',me.answer_form)
                        .then(function (r) {
                            if(r.data.status){
                                console.log('add successfully!');
                                
                                me.answer_form={};
                                me.update_data(r.data.data.id);
                                $state.reload();

                            }
                        })
                }

            };
            
            
            me.delete=function (id) {
                if(!id){
                    console.error('id is required');
                    return ;
                }
                $http.post('api/answer/remove',{id:id})
                    .then(function (r) {
                        if(r.data.status){
                            console.log('deleted successfully!');
                            del=true;
                            me.update_data(id);
                            $state.reload();
                            // location.href='';
                            
                            
                        }
                    })



            }


            me.deleteConfirm=function(id){

                 if(confirm('Delete answer ?')) {
                      
                        me.delete(id);
                        
                    
                        
                    }
            }
   
            
            
            
            


            me.read=function (params) {
                return $http.post('api/answer/read',params)


                    .then(function (r) {
                        console.log('params',params);
                        console.log('r',r);
                        if(r.data.status){
                            me.data=angular.merge({} , me.data,r.data.data);
                           
                            return r.data.data;
                        }
                        return false;
                    })
            };


            me.addOrEdit_comment=function () {

            if(me.action=='add'){
                return  $http.post('api/comment/add',me.new_comment)
                    .then(function (r) {
                console.log('r',r);
                if(r.data.status){
                    return true;
                }
                return false;



        })
}else if(me.action=='edit'){
    return  $http.post('api/comment/change',me.new_comment)
        .then(function (r) {
            console.log('r',r);
            if(r.data.status){
                return true;
            }
            return false;



        })
}

             
            }



            me.delete_comment=function () {

                if(confirm('Delete comment ?')) {

                    return  $http.post('api/comment/remove',me.new_comment)
                        .then(function (r) {
                            console.log('r',r);
                            if(r.data.status){
                                return true;
                            }
                            return false;



                        })

                }




            }






            me.vote=function (conf)
            {
                if(!conf.id ||!conf.vote){
                    console.log('id and vote are required');
                    return;
            }


                var answer=me.data[conf.id];//me.data的資料己在countVote方法中取得
                 console.log('me.data: ',me.data);
                console.log('answer: ',answer);


                    var users=answer.users;

                if(answer.user_id==his.id){
                    alert('不能投票給自己的答案');
                    return false;
                }




                for(var i=0; i<users.length;i++){
                    if(users[i].id==his.id &&conf.vote==users[i].pivot.vote ){
                      conf.vote=3;
                        conf.userUpvoted=false;
                        conf.userDownvoted=false;
                    }
                }


                console.log('me.data[conf.id]',me.data[conf.id]);


                return $http.post('api/answer/vote',conf)
                    .then(function (r) {
                        if(r.data.status){
                            return true;
                        }else if(r.data.msg='login required'){
                            alert(r.data.msg);
                            $state.go('login');
                        }
                        return false;
                    },function () {
                        return false;
                    })
            }


         me.update_data=function (id) {
             if(del==true){

                 delete  me.data[id];
                 // me.data.splice(id,1);
                 del=false;

                 return id;
         }
            return $http.post('api/answer/read',{id: id})
                .then(function (r) {

                    me.data[id]=r.data.data;
     
                });

             // if(angular.isNumeric(input)){
             //     var id=input;
             //     if(angular.isArray(input)){
             //         var id_set=input;
             //     }
             // }
         }

        






        }])
        .directive('commentBlock',['AnswerService','$http',function (AnswerService,$http) {
          var o={};
            o.templateUrl='comment.tpl';
            o.scope={
                answer_id : '=answerId' //是comment-block 中的 answer-id="item.id"
                                        //= 後面是angular表達式即是 item.id
                                        //@是當成字符串處理
            }
            o.link=function (sco,ele,attr) {
                //link是在頁面發現commentBlock後立即執行的方法

                //sco是元素內部的域
                //ele是 <div id></div>的id
                sco.Answer=AnswerService;
                sco._={};
                sco.data={};
                sco.helper=helper;
                sco.id=his.id;
                function get_comment_list() {
                   return $http.post('api/comment/read', {answer_id: sco.answer_id})
                        .then(function (r) {
                            // console.log('r',r);
                           console.log('comData',r.data.data);
                            sco.data=angular.merge({},sco.data,r.data.data);
                             console.log('scodata', sco.data);
                        })

                   
                }
                
                
                    if(sco.answer_id){
                        get_comment_list();
                    }
                    //console.log('sco.answer_id',sco.answer_id);



                sco._.addOrEdit_comment=function () {

                    AnswerService.new_comment.answer_id=sco.answer_id;
                    AnswerService.addOrEdit_comment().
                        then(function (r) {
                     if(r){

                         AnswerService.new_comment={};
                         get_comment_list();
                     }
                    })

                }


                sco._.edit_comment=function (item) {
                    console.log('new_comment',AnswerService.new_comment);
                    AnswerService.new_comment=item;
                    AnswerService.new_comment.content=item.content;
                    AnswerService.action='edit';

                }


                sco._.delete_comment=function (item) {
                 //   console.log('new_comment',AnswerService.new_comment);
                    AnswerService.new_comment=item;


                    delete  sco.data[item.id];

                    //AnswerService.new_comment.answer_id=sco.answer_id;
                    AnswerService.delete_comment().
                    then(function (r) {
                        if(r){

                            AnswerService.new_comment={};
                           //sco.data.splice(item,1);



                            get_comment_list();
                        }
                    })


                }



                sco._.reply_comment=function (item) {
                    console.log('new_comment',AnswerService.new_comment);

                    AnswerService.new_comment.content= '回覆 '+item.user.username+':';


                }
            
                
            }

            //返回的是元素設置
            return o;
        }])
})();