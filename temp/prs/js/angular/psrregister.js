var app = angular.module('psrRegisterApp', ['ngRoute','ngMessages','validation.match'])
.constant('USERREGISTERAPI','http://localhost/pradeep/web/API/getvendordetails.json?id=');

app.config(function($routeProvider) {
  $routeProvider
  .when('/', {
    templateUrl : 'pages/register.html',
    controller  : 'RegisterController'
  })
  .otherwise({redirectTo: '/'});
});



//Password Controller Start
app.controller('RegisterController', function($scope,$http,USERREGISTERAPI) {
  $scope.message = 'Hello from RegisterController';
  $scope.show=true;
  $scope.user={};


  $('#sbtn').click(function(){
      saveUser();
  });

  
  $('#loginPage').click(function(){
    window.location='login.html';
  });



  var saveUser = function(){
    var userType=$scope.user.userType;
       $http.post(USERREGISTERAPI,
       {
         data: {user: $scope.user}
        
        })
        .success(function(data,status){
          $scope.class='success';
          $scope.message='User register successfully';
          window.location='login.html';
        
        })
        .error(function(data,status){
         $scope.class='danger';
         $scope.message='User not register';
        });
  
}

  });



