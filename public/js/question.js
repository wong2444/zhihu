;(function () {
    'use strict';
    angular.module('question',[])
        .service('QuestionService',['$state','$http','AnswerService',
            function ($state,$http,AnswerService) {
            var me=this;
            me.data={};
                me.current_question={};
                me. success=false;
            me.go_add_question=function () {

                $state.go('question.add')
            }
            me.add=function () {
                if(!me.new_question.title){
                    return;
                }
                $http.post('api/question/add',me.new_question)
                    .then(function (r) {
                        if(r.data.status){

                            me.new_question={};
                            $state.go('home');
                            location.href='';
                         
                        }else if(r.data.msg=='login required'){
                            alert(r.data.msg);
                            $state.go('login');
                        }
                    },function (e) {

                    })
            }



               



                me.update=function (item) {

                    if(item!=null){
                        me.current_question=item;

                        console.log('item',item);
                    }
                    
                    
                    if(!me.current_question.title){
                        alert('title is required');
                        return false;

                    }
return $http.post('api/question/change',me.current_question)

    .then(function (r) {
      if(r.data.status){
          me.show_update_form=false;
      }
    })


                }
                
                
                
                me.vote=function (conf) {

                  var $r=  AnswerService.vote(conf);
                      if($r)
                       $r .then(function (r) {
                            if(r){
                                    me.update_answer(conf.id);

                                //AnswerService.update_data(conf.id);
                            }
                        },function () {

                        })
                };

                me.update_answer=function (answer_id) {
$http.post('api/answer/read',{id: answer_id})
    .then(function (r) {
        if(r.data.status){
            for(var i=0;i< me.current_question.answers_with_user_info.length;i++){
                var answer=me.current_question.answers_with_user_info[i];
                if(answer.id==answer_id){
                    //console.log(r.data.data);
                    me.current_question.answers_with_user_info[i]=r.data.data;
                    AnswerService.data[answer_id]=r.data.data;
                }
            }
            console.log(me.current_question.answers_with_user_info);
        }
    })

                }
            
            me.read=function (params) {
                return $http.post('api/question/read',params)
                    .then(function (r) {
                        var its_answers;
                        if(r.data.status){
                            if(params.id){
                                me.data[params.id]=me.current_question=r.data.data;
                            its_answers=me.current_question.answers_with_user_info;
                                me.current_question.answers_with_user_info=AnswerService.count_vote(its_answers);
                              //   return   me.current_question;
                            }else{
                                me.data=angular.merge({},me.data,r.data.data);
                                return r.data.data;
                            }
                            
                          
                        }
                            return false;

                    })
            }


                me.delete=function (id) {

                    if(!id){
                        console.error('id is required');
                        return ;
                    }
                 
                    $http.post('api/question/remove',{id:id})
                        .then(function (r) {

                            if(r.data.status){

                            }else{
                                alert(r.data.msg);

                            }

                        })





                };
                




        }])

.controller('QuestionController',['$scope','QuestionService',
   'AnswerService', function ($scope,QuestionService,AnswerService) {
        $scope.Question= QuestionService;
        $scope.Answer= AnswerService;
}])
        .controller('QuestionAddController',['$scope','QuestionService',function ($scope,QuestionService) {
            $scope.Question= QuestionService;

            
        }])
        .controller('QuestionDetailController',['$scope','QuestionService',
          '$stateParams',  function ($scope,QuestionService,$stateParams) {
                QuestionService.read($stateParams);
                console.log('$stateParams',$stateParams);//{id:1,answer_id:2}

                if($stateParams.answer_id){
                    QuestionService.current_answer_id=$stateParams.answer_id;
                }else{
                    QuestionService.current_answer_id=null;
                }


                console.log("QuestionService.current_question",QuestionService.current_question);
                



        }])
})();