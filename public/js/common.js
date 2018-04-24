;(function () {
    'use strict';
    angular.module('common',[])
        .service('TimelineService',['$http','AnswerService','QuestionService','$state',
            function ($http,AnswerService,QuestionService,$state) {
            var me=this;
            me.data=[];
            me.current_page=1;
            me.no_more_data=false;
             var open_edit_data={};
                me.success=false;
                me.searchText="";
            me.vote=function (conf) {

               var $r= AnswerService.vote(conf);
                if($r)
                    $r.then(function (r) {
                        if(r){
                            AnswerService.update_data(conf.id);
                        }
                    },function () {
                        
                    })
            }

                me.add_or_update=function (id) {

               AnswerService.add_or_update(id);

                    // $state.reload();

                };

                me.deleteQuestion=function (id) {
                    if(confirm('Delete question ?')) {
                      
                        QuestionService.delete(id);
                        
                            for(var i=0;i<me.data.length;i++){
                                if(me.data[i].answers_with_user_info.length>0){
                                    break;
                                }
                                if(me.data[i].id==id){
                                    me.data.splice(me.data[i], 1);

                                
                            }
                        }
                    
                        
                    }
                    
                    
                    
                    $state.reload();

                };
                
                
                
                me.delete=function (id) {

                   if(confirm('Delete answer ?')) {

                       AnswerService.delete(id);


                   //       for(var i=0;i<me.data.length;i++){
                   //         for(var j=0; j<me.data[i].answers_with_user_info.length;j++){

                   //             if(me.data[i].answers_with_user_info[j].id==id){
                   //                  //更新首頁列表
                   //                 console.log('hello');
                   //                 me.data[i].answers_with_user_info.splice(j, 1);
                   //                // delete  me.data[i].answers_with_user_info[j];
                                   
                   //             }

                   //         }


                   // }

                                $state.reload();
                                // location.href='';




                    }


                  //  me.restore();
                    // $state.reload();
                }
                
                
                
                

                me.cancel=function (item) {
                    console.log('open_edit_data.title',  open_edit_data.title);
                    console.log('open_edit_data.desc',  open_edit_data.desc);


                  //  item = JSON.parse(JSON.stringify(open_edit_data));

                    console.log('item',item);
                    console.log('itemTitle',item.title);
                    console.log('itemDes',item.desc);
                    
                    item.show_update_form=!item.show_update_form;
                    console.log('item',item);

                console.log('me.data',me.data);
                   me.restore();

                   $state.reload();
                }

                me.openEdit=function (item) {
                    
                   console.log('open_edit_data.title',  open_edit_data.title);
                    console.log('open_edit_data.desc',  open_edit_data.desc);
                    open_edit_data = JSON.parse(JSON.stringify(item));
                    item.show_update_form=!item.show_update_form;


                }




                me.extendOrHide=function (btn,id) {

                    var str='#'+id;
                    var btn1='#'+btn;

                    $(str).toggleClass('AutoSkip');

                    $(btn1).text(function (i, text) {
                    text=text.trim();
                        if(text=='展開'){

                            return '隱藏';
                        }else {
                            return '展開';
                        }





                    })





                };


            
                
                
                
                


                me.update_question=function (item) {
                
                    QuestionService.update(item);
                    item.show_update_form=!item.show_update_form;
                    $state.reload();

                };



                me.restore=function () {

                    for(var i=0;i<me.data.length;i++){
                        if(me.data[i].id==  open_edit_data .id){
                            me.data[i]=open_edit_data;
                        }
                    }

                }


            me.get=function (conf) {
               
                if(me.pending){
                    return ;
                }
                     console.log("hisid",his.id);
                me.pending=true;
                conf=conf || {page :me.current_page};
                $http.post('api/timeline',conf)
                    .then(function (r) {
                        if(r.data.status){
                               console.log(r);
                            if(r.data.data.length){
                                
                               me.data=me.data.concat(r.data.data);
                                for(var i=0;i<me.data.length;i++){
                                    if(me.data[i].answers_with_user_info.length!=0){
                                        me.data[i].answers_with_user_info=AnswerService.count_vote(me.data[i].answers_with_user_info);
                                    }
                                }

                                // me.data=AnswerService.count_vote(me.data.answers_with_user_info);
                                console.log('me.data',me.data);
                                console.log('me.data.length',me.data.length);
                                me.current_page++;
                                if (me.data.length<16) {
                                    me.no_more_data=true;
                                }
                                // return me.data;

                            }else{
                                me.no_more_data=true;


                                
                            }


                        }else{
                            console.error('network error');

                        }
                    },function () {
                        console.error('network error');
                    })
                    .finally(function () {
                        me.pending=false;
                    })
            }

            me.reset_state=function(){
                me.data=[];
                me.current_page=1;
                me.no_more_data=false;
            }


        }])


        .controller('HomeController',['$scope','TimelineService','AnswerService','QuestionService',
            function ($scope,TimelineService,AnswerService,QuestionService) {

            $scope.Timeline =TimelineService;
                $scope.Question= QuestionService;
                $scope.Answer= AnswerService;
            var $win;
            TimelineService.reset_state();
            TimelineService.get();
          

            $win=$(window);

            $win.on('scroll',function () {
                if($win.scrollTop() - ($(document).height()-$win.height())>-30){
                    // $win.scrollTop()是滾動條的值向上減少向下增加

                    if($scope.Timeline.no_more_data==false){
                        TimelineService.get();
                    }


                }
            });
$scope.$watch(function () {
    return  AnswerService.data;
},function (new_data,old_data) {
    console.log('old_data',old_data);
    console.log('new_data',new_data);
    var timeline_data=TimelineService.data;
    
   
    // TimelineService.get();

 var k;
    for( k in new_data)
    {


        for(var i=0;i<timeline_data.length;i++){



        if(timeline_data[i].answers_with_user_info.length==0){
            continue ;
        }




            if(new_data[k].question_id==timeline_data[i].id)    {

            var isNew=true;
                for(var j=0; j<timeline_data[i].answers_with_user_info.length;j++){
       // timeline_data[i].answers_with_user_info[j].deleted=true;
                if(new_data[k].question_id==timeline_data[i].answers_with_user_info[j].question_id){


                    if(k==timeline_data[i].answers_with_user_info[j].id){



                            timeline_data[i].answers_with_user_info[j]=new_data[k];




                            isNew=false;
                }

            }





        }


    if(isNew==true){
         timeline_data[i].answers_with_user_info.push(new_data[k]);
    }



}


// if(k==timeline_data[i].id &&timeline_data[i].question_id!=null){
//     timeline_data[i].answers_with_user_info=new_data[k];
// }
}
}
   
    TimelineService.data=AnswerService.count_vote(TimelineService.data);

    console.log('timeline_data',timeline_data);



    // for(var i=0;i<timeline_data.length;i++){
    //     if(timeline_data[i].answers_with_user_info.length==0){
    //         continue ;
    //     }
    //     for(var j=0; j<timeline_data[i].answers_with_user_info.length;j++){
    //
    //         if( timeline_data[i].answers_with_user_info[j].deleted==true){
    //             delete  timeline_data[i].answers_with_user_info[j];
    //         }
    //
    //     }
    //
    //
    //
    // }






},true);






            }])

})();