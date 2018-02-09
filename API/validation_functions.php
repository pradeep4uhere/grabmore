<?php 
  /*Datavalidate*/
  function isValidSupplierProfileData($dataArr){

      if($dataArr['data']['user']['type']=='pass'){
          if($dataArr['data']['user']['oldpassword']==''){
              $msg="Please enter old password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['password']==''){
              $msg="Please enter new password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['cpassword']==''){
              $msg="Please enter confirm password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['password']!=$dataArr['data']['user']['cpassword']){
              $msg="Password did not match.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else{
            return true;
          }

      }else if($dataArr['data']['user']['type']=='profile'){

          // echo "<pre>";
          // print_r($dataArr['data']['user']); die;
          if($dataArr['data']['user']['firstName']==''){
              $msg="Please enter first name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['lastName']==''){
                $msg="Please enter last name.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['address']==''){
                $msg="Please enter address.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['pincode']==''){
                $msg="Please enter pincode.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['landmark']==''){
                $msg="Please enter landmark.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['mobile']==''){
                $msg="Please enter mobile.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['emailAddress']==''){
                $msg="Please enter email address.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else{
            return true;
          }

      }else{
      if($dataArr['data']['user']['fname']==''){
              $msg="Please enter first name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['lname']==''){
              $msg="Please enter last name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['address']==''){
              $msg="Please enter address.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['pincode']==''){
              $msg="Please enter pincode.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['emailaddress']==''){
              $msg="Please enter valid email address.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['password']==''){
              $msg="Please enter password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['cpassword']==''){
              $msg="Please enter confirm password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['cpassword']!=$dataArr['data']['user']['password']){
            $msg="You password did not matched.";
            $status='failed';
            getErrorMessage($dataArr['data'],$status,$msg);
      }else{
        return true;
      }
    }

  }





  function isValidVendorProfileData($dataArr){

      if($dataArr['data']['user']['type']=='pass'){
          if($dataArr['data']['user']['oldpassword']==''){
              $msg="Please enter old password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['password']==''){
              $msg="Please enter new password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['cpassword']==''){
              $msg="Please enter confirm password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['password']!=$dataArr['data']['user']['cpassword']){
              $msg="Password did not match.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else{
            return true;
          }

      }else if($dataArr['data']['user']['type']=='profile'){

          // echo "<pre>";
          // print_r($dataArr['data']['user']); die;
          if($dataArr['data']['user']['firstName']==''){
              $msg="Please enter first name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['lastName']==''){
                $msg="Please enter last name.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['companyName']==''){
                $msg="Please enter company name.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['comapnyAdress']==''){
                $msg="Please enter address.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['pincode']==''){
                $msg="Please enter pincode.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else if($dataArr['data']['user']['mobile']==''){
                $msg="Please enter mobile.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }
          /*else if($dataArr['data']['user']['panCard']==''){
                $msg="Please enter PAN Card No.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }*/
          else if($dataArr['data']['user']['emailAddress']==''){
                $msg="Please enter email address.";
                $status='failed';
                getErrorMessage($dataArr['data'],$status,$msg);
          }else{
            return true;
          }

      }else{
      if($dataArr['data']['user']['fname']==''){
              $msg="Please enter first name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['lname']==''){
              $msg="Please enter last name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['compname']==''){
              $msg="Please enter compnany name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['address']==''){
              $msg="Please enter address.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['pincode']==''){
              $msg="Please enter pincode.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['emailaddress']==''){
              $msg="Please enter valid email address.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['password']==''){
              $msg="Please enter password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['cpassword']==''){
              $msg="Please enter confirm password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['cpassword']!=$dataArr['data']['user']['password']){
            $msg="You password did not matched.";
            $status='failed';
            getErrorMessage($dataArr['data'],$status,$msg);
      }else{
        return true;
      }
    }

  }



  /*Datavalidate*/
  function isValidVendorData($dataArr){
      if($dataArr['data']['user']['fname']==''){
              $msg="Please enter first name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['lname']==''){
              $msg="Please enter last name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['compname']==''){
              $msg="Please enter compnany name.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['address']==''){
              $msg="Please enter address.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['pincode']==''){
              $msg="Please enter pincode.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['emailaddress']==''){
              $msg="Please enter valid email address.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['password']==''){
              $msg="Please enter password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['cpassword']==''){
              $msg="Please enter confirm password.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['cpassword']!=$dataArr['data']['user']['password']){
              $msg="You password did not matched.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else{
        return true;
      }

  }


  /*show error mesage*/
  function getErrorMessage($data,$status='failed',$msg=''){
    if(!empty($data['from'])){
      if($data['from']=='mobile'){
        echo json_encode(array('status'=>$status,'message'=>$msg)); exit;
      }else{
        echo json_encode(array('status'=>$status,'message'=>$msg)); exit;
      }
    }

  }



  /*
   * @Author: pradeep Kumar
   * @Dexripton: Validatin Menthod For Valid Post data
   * @for adding new product by Vendor
   * @return true on valid else return array
   */
  function isValidProductData($dataArr){

    if(!empty($dataArr['data']['user'])){
      if($dataArr['data']['user']['vendorId']==''){
              $msg="Invalid Vendor Id, please try again.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['catID']==''){
              $msg="Please choose category.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['unitID']==''){
              $msg="Please choose sub category.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['title']==''){
              $msg="Please enter title.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['description']==''){
              $msg="Please enter description.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['sku']==''){
              $msg="Please enter product code.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['capacity']==''){
              $msg="Please enter product capacity.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['capacity']==''){
              $msg="Please enter product capacity.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else if($dataArr['data']['user']['price']==''){
              $msg="Please enter product price.";
              $status='failed';
              getErrorMessage($dataArr['data'],$status,$msg);
      }else{
        return true;
      } 
    }

  }




  function isValidCheckSum($requestData){
    if(CHECKSUM_VALIDATE){
      if(is_array($requestData)){
        $str=SALT.'|'.TOKEN;
        foreach($requestData as $k=>$v){
          if($k=='checksum' || $k=='PHPSESSID' || $k=='t' || $k=='action' || $k=='token'){ 
            if($k=='checksum'){
              $checksum=$v;
            }
          }else{
            $str.='|'.$v;
          }
        }
        if($checksum==md5($str)){
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }
    }else{
      return true;
    }

  }






  function isValidPostCheckSum($postData){
    $requestData=$postData['user'];
    $requestData['from']=$postData['from'];
    $requestData['checksum']=$postData['checksum'];
    $requestData['ipaddress']=$postData['ipaddress'];
    if(CHECKSUM_VALIDATE){
      if(is_array($requestData)){
        $str=SALT.'|'.TOKEN;
        foreach($requestData as $k=>$v){
          if($k=='checksum' || $k=='PHPSESSID' || $k=='t' || $k=='action' || $k=='token'){ 
            if($k=='checksum'){
              $checksum=$v;
            }
          }else{
            $str.='|'.$v;
          }
        }
        if($checksum==md5($str)){
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }
    }else{
      return true;
    }

  }




  function invalidLogin(){
    return array('status'=>'error','message'=>'Invalid Login User.');
  }


?>
