;(function () {
    'use strict';
    angular.module('user',[])
        .service('UserService',['$state','$http',
            function ($state,$http) {

            var me=this;
            me.signup_data={};
            me.login_data={};
            me.self_data={};
            me.current_user={};

            me.read=function (param) {
                return $http.post('api/user/read',param)
                    .then(function (r) {
                        if(r.data.status){
                            me.current_user=r.data.data;
                            
                        }else{
                            if(r.data.msg=='login required'){
                                $state.go('login');
                            }
                        }
                    })
            }



            me.signup=function () {
                $http.post('api/signup',me.signup_data)
                    .then(function (r) {
                        if(r.data.status){
                            me.signup_data={};
                            $state.go('login');
                        }
                    },function () {

                    })
            };

                me.logout=function () {
                    $http.post('api/logout',{})
                        .then(function (r) {
                            if(r.data.status){

                                $state.go('home');
                                location.href='';

                            }else{
                                console.error('network error');

                            }
                        },function () {
                            console.error('network error');
                        })
                      
                }



                me.login=function () {
                $http.post('api/login',me.login_data)
                    .then(function (r) {
                        if(r.data.status){
                            $state.go('home');
                            location.href='';
                        }
                        else{
                            me.login_failed=true;

                        }

                    },function () {

                    })
            }
            


            me.username_exists=function () {

                $http.post('api/user/exist',{username: me.signup_data.username})
                    .then(function (r) {
                        if(r.data.status && r.data.data.count){
                            me.signup_username_exists=true;
                        }else{
                            me.signup_username_exists=false;
                        }
                    },function (e) {
                        console.log('e',e);
                    })
            }
        }])
        .controller('SignupController',['$scope','UserService',function ($scope,UserService) {
            $scope.User= UserService;

            $scope.$watch(function () {
                    return UserService.signup_data;
                },function (n,o) {
                    if(n.username !=o.username){
                        UserService.username_exists();}}
                ,true)}])

        .controller('LoginController',['$scope','UserService',function ($scope,UserService) {
            $scope.User= UserService;

            $scope.$watch(function () {
                    return UserService.signup_data;
                },function (n,o) {
                    if(n.username !=o.username){
                        UserService.username_exists();}}
                ,true)}])
        // watch第三個參數true表現要遞歸檢查

        .controller('UserController',['$scope','$stateParams','UserService','AnswerService',
            'QuestionService',
            function ($scope,$stateParams,UserService,AnswerService,QuestionService) {
                $scope.User=UserService;
                console.log('$statusParams',$stateParams);
                $scope.user_id=$stateParams.id;
                UserService.read($stateParams);
                AnswerService.read({ user_id : $stateParams.id})
                    .then(function (r) {
                        if(r){
                            UserService.his_answers=r;
                        }
                    })


                QuestionService.read({ user_id : $stateParams.id})
                    .then(function (r) {
                        console.log(r);
                        if(r){
                            UserService.his_questions=r;
                        }
                    })


        }])
})()