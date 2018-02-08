var app = angular.module('psrLoginApp',['ngRoute']).
constant('LOGIN_URL','API/internalapi.php?action=login&t='+ Math.random());

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
        swal({
            title: "Invalid User Type?",
            text: "Please Choose valid user type.",
            type: "error",
            showCancelButton: false,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ok!",
            closeOnConfirm: true
          },
          function(){
            //swal("Ok!", "Your imaginary file has been deleted.", "success");
          });
      }else{
        loginUser();  
      }
      
  });

  $scope.messageAlert = function(){
    swal({
            title: $scope.title,
            text: $scope.text,
            type: $scope.type,
            showCancelButton: false,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ok!",
            closeOnConfirm: true
          },
          function(){
            //swal("Ok!", "Your imaginary file has been deleted.", "success");
          });
  } 

  var loginUser = function(){
     
     var userType=$scope.user.userType;
     console.log($scope.user.email_address);
     
     if($scope.user.email_address==undefined){
        $scope.title='Invalid Email Address';
        $scope.text='Please enter valid email address.';
        $scope.type='error';
        $scope.messageAlert();
        return false;
     }
     if($scope.user.password==undefined){
        $scope.title='Invalid Password';
        $scope.text='Please enter valid password.';
        $scope.type='error';
        $scope.messageAlert();
        return false;
     }

     if($scope.user.email_address!=undefined && $scope.user.password!=undefined){
       $http.post(LOGIN_URL,
       {
         data: {user: $scope.user}
        
        })
        .success(function(data,status){
          var responseData=JSON.stringify(data);
          if(data.status=='success'){
            $scope.title='You Logged!!';
            $scope.text='Please wait redirect to dashboard.';
            $scope.type='success';
            $scope.messageAlert();
            setTimeout(function(){
              window.location='profile.php'
            },2000);
          }else{
            $scope.title='Invalid Credentials!!';
            $scope.text='Please enter valid Credentials.';
            $scope.type='error';
            $scope.messageAlert();
            return false;
          }
        })
        .error(function(data,status){
          $scope.title='Oops!!';
          $scope.text='somthing went wrong, please try agian.';
          $scope.type='error';
          $scope.messageAlert();
          return false;
        });
    }
  }

});
