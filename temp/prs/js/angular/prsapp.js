var app = angular.module('prsApp', ['ngRoute']);

app.config(function($routeProvider) {
  $routeProvider

  .when('/', {
    templateUrl : 'pages/dashboard.html',
    controller  : 'DashboardController'
  })

  .when('/addvendor', {
    templateUrl : 'pages/vendor/addnewvendor.html',
    controller  : 'VendorAddController'
  })

  .when('/vendorlist', {
    templateUrl : 'pages/vendor/allvendor.html',
    controller  : 'VendorController'
  })

  .when('/editvendor/:id/:fname', {
    templateUrl : 'pages/vendor/editvendor.html',
    controller  : 'VendorEditController'
  })
  .otherwise({redirectTo: '/'});
});


//Dashboard Controller Start
app.controller('DashboardController', function($scope) {
  $scope.message = 'Hello from DashboardController';
});


//Vendor Controller Start
app.controller('VendorController', function($scope,$http) {
  $scope.message = 'Hello from VendorController';
  $http.get('http://localhost/pradeep/web/API/allvendorlist.json')
  .then(function(response){
    $scope.itemlist = response.data; 
  });
});


//Vendor Add Controller Start
app.controller('VendorAddController', function($scope) {
  $scope.message = 'Hello from VendorAddController';
});





//Vendor Edit Controller Start
app.controller('VendorEditController', function($scope,$http, $routeParams) {
  $scope.id = $routeParams.id;
  $scope.vendors={};
  $scope.class='';
  $scope.message='';

  $http.get('http://localhost/pradeep/web/API/getvendordetails.json?id='+$scope.id)
  .then(function(response){
    $scope.vendors = response.data[0]; 
  });


  $('#sbtn').click(function(){
    postVendorDetail();
  });

  var postVendorDetail = function(){
    $http.post('http://localhost/pradeep/web/API/getvendordetails.json?id=',
      {
       data: {vendors: $scope.vendors}
      
      })
      .success(function(data,status){
      
        $scope.class='success';
        $scope.message='Profile is updated Successfully';
      
      })
      .error(function(data,status){
      
        $scope.class='danger';
        $scope.message='Profile is not updated Successfully';

      });
  }

});


