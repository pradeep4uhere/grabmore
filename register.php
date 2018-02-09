<!DOCTYPE HTML>
<html>
<head>
<title>Sign In</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- Graph CSS -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<!--Sweet Alert CSs-->
<link href="css/sweetalert.css" rel="stylesheet"> 
<!-- lined-icons -->
<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
<!-- //lined-icons -->
<!-- chart -->
<script src="js/Chart.js"></script>
<!-- //chart -->
<!--animate-->
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
  <script>
     new WOW().init();
  </script>
 <!-- Meters graphs -->
<script src="js/jquery-1.10.2.min.js"></script>
<!-- Placed js at the end of the document so the pages load faster -->

</head> 
<style type="text/css">
   .sweet-alert h2{ font-size: 14px; }
   .sweet-alert p{ font-size: 13px; }
</style>  
 <body class="sign-in-up" ng-app="psrRegisterApp">
    <section>
      <div ng-view></div>
    <!--footer section start-->
      <footer>
         <p>&copy 2015 Easy Admin Panel. All Rights Reserved | Design by <a href="https://google.com/" target="_blank">Google.com.</a></p>
      </footer>
        <!--footer section end-->
  </section>
<script type="text/javascript">
  var ipaddress="<?php echo $_SERVER['REMOTE_ADDR']?>";
</script>  
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<script src="js/angular/sweetalert.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<script src="js/angular/angular.min.js"></script>
<script src="js/angular/angular-route.min.js"></script>
<!-- <script src="js/angular/angular-messages.js"></script>
<script src="js/angular/angular-validation-match.js"></script>
 --><script src="js/angular/psrregister.js"></script>
</body>
</html>