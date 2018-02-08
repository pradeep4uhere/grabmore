var app = angular.module('psrLoginApp',['ngRoute']).
constant('LOGIN_URL','http://localhost/pradeep/web/API/internalapi.php?action=login');

app.config(function($routeProvider) {
  $routeProvider
  .when('/', {
    templateUrl : 'pages/login.html',
    controller  : 'LoginController'
  })
  .when('/forgotpassword', {
    templateUrl : 'pages/forgotpassword.html',
    controller  : 'PasswordController'
  })
  .when('/register', {
    templateUrl : 'pages/register.html',
    controller  : 'RegisterController'
  })
  .otherwise({redirectTo: '/'});
});

//Password Controller Start
app.controller('PasswordController', function($scope) {
  $scope.message = 'Hello from PasswordController';
});




//Password Controller Start
app.controller('RegisterController', function($scope) {
  $scope.message = 'Hello from RegisterController';
  window.location='register.html'

});


//Login Controller Start
app.controller('LoginController', function($scope,$http,LOGIN_URL) {
  $scope.class='';
  $scope.message='';
  $scope.user='';
  $scope.show =false;
 
  $('#loginBtn').click(function(){
      if($scope.user.userType==undefined){
        $scope.class='danger';
        $scope.message='Login is Successfully';
        alert('Please Choose User Type First');
      }else{
        loginUser();  
      }
      
  });

  var loginUser = function(){
     
     var userType=$scope.user.userType;
     $http.post(LOGIN_URL,
     {
       data: {user: $scope.user}
      
      })
      .success(function(data,status){
        console.log(data);
        //data.JSON.stringify(data);
        alert(data.status);
        if(data.status=='status'){
          $scope.class='danger';
          $scope.message='Login is Successfully';
          //window.location='index.php'
        }else{
          $scope.class='errro';
          $scope.message='Invalid Login';
        }
      
      })
      .error(function(data,status){
        $scope.class='danger';
        $scope.message='Login is not Successfully';
      });
  }

});

