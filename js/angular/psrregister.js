var app = angular.module('psrRegisterApp', ['ngRoute','ngMessages','validation.match'])
.constant('USERREGISTERAPI','API/internalapi.php?action=addnewvendor&t='+ Math.random());

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
  $scope.user={
                fname:'',
                lname:'',
                compname:'',
                address:'',
                pincode:'',
                emailaddress:'',
                mobile_number:'',
                password:''
              };


  $('#sbtn').click(function(){
      saveUser();
  });

  
  $('#loginPage').click(function(){
    window.location='login.php';
  });



  var saveUser = function(){
    var userType=$scope.user.userType;
       if($('#firstname').val()==''){
          alert('Please enter first name');
          return false;
       }else{
          $scope.fname=$('#firstname').val();
       }


       if($('#lastname').val()==''){
          alert('Please enter last name');
          return false;
       }else{
        $scope.lname=$('#lastname').val();
       }
       if($('#compname').val()==''){
          alert('Please enter company name');
          return false;
       }else{
        $scope.compname=$('#compname').val();
       }

       if($('#address').val()==''){
          alert('Please enter address');
          return false;
       }else{
        $scope.address=$('#address').val();
       }


       if($('#Pincode').val()==''){
          alert('Please enter Pincode');
          return false;
       }else{
        $scope.Pincode=$('#Pincode').val();
       }

       if($('#phonenumber').val()==''){
          alert('Please enter phonenumber');
          return false;
       }else{
        $scope.phonenumber=$('#phonenumber').val();
       }

       if($('#emailaddress').val()==''){
          alert('Please enter email address');
          return false;
       }else{
        $scope.emailaddress=$('#emailaddress').val();
       }


       if($('#confirmPassword').val()==''){
          alert('Please enter confirm password');
          return false;
       }else{
        $scope.confirmPassword=$('#confirmPassword').val();
       }



       $scope.user={
                fname:$scope.fname,
                lname:$scope.lname,
                compname:$scope.compname,
                address:$scope.address,
                pincode:$scope.Pincode,
                emailaddress:$scope.emailaddress,
                mobile_number:$scope.phonenumber,
                password:$scope.confirmPassword
              };
       $http.post(USERREGISTERAPI,
       {
         data: {user: $scope.user}
        
        })
        .success(function(data,status){
          $scope.class='success';
          $scope.message='User register successfully';
          alert('User register successfully');
          //window.location='login.php';
        
        })
        .error(function(data,status){
         $scope.class='danger';
         $scope.message='User not register';
         alert('User Not register !!');
        });
  
}

  });