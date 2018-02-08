<?php require('../config/constant.inc.php');
   require('api.class.php'); 
  $apiServiceObj= new apiService();
  $data=file_get_contents("php://input");
  $action=trim($_REQUEST['action']);
  switch($action){
    case 'login':
        $postData=$data;
        if(!empty($postData)){
        $dataArr=json_decode($postData,true);
          $loginData=(array)$dataArr['data']['user'];
          $postFields['email_address']=$loginData['email_address'];
          $postFields['password']=$loginData['password'];
          $postFields['userType']=$loginData['userType'];
          $apiServiceObj->postFields=$postFields; 
          $res=$apiServiceObj->userLogin();
          return $res;
        }else{
                $res=array('message'=>'Parameter mising.','error'=>'error');
          return $res;
        }
    break ;

    default : echo "Try After sometime";
    break;
  }


  
?>
