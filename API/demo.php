<?php require('../config/constant.inc.php');
   require('api.class.php'); 
  $apiServiceObj= new apiService();
  $data=file_get_contents("php://input");
  $action=trim($_REQUEST['action']);
  $conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_DATABASE); 
  //$rid=mysqli_select_db(DB_DATABASE) or die('Mysql not Connect');
  function mysqlRealString($str){
      return mysqli_real_escape_string($GLOBALS['conn'],$str);
  }


  /*************************Common Function Start************************/
  function getOrderId($orderId){
  		$str=DATE('Ymd');
  		return 'PRS'.$str.$orderId;
  }


  function isValidVendor($id){
      $conn=$GLOBALS['conn'];
      $query=" SELECT `PKCompanyRefNo` FROM ".PREFIX.VENDOR." WHERE `PKCompanyRefNo`='".$id."'";
      $res=mysqli_query($conn,$query);
      if(mysqli_num_rows($res)>0){
        return true;
      }else{
        return false;
      }
  }



  function passGen($length = 8) {
    $pass = array();
    for ($i = 0; $i < $length; $i++) {
        $pass[] = chr(mt_rand(32, 126));
    }
    return implode($pass);
  }


  function isValidSupplier($type,$val,$PKCompanyRefNo){
      $conn=$GLOBALS['conn'];
      $query=" SELECT $type FROM ".PREFIX.SUPPLIER." WHERE $type='".$val."' AND `PKCompanyRefNo`='".$PKCompanyRefNo."'";
      $res=mysqli_query($conn,$query);
      if(mysqli_num_rows($res)>0){
        return true;
      }else{
        return false;
      } 
  }



  function isSupplierPresent($type,$val,$PKCompanyRefNo,$id){
      $conn=$GLOBALS['conn'];
      $query=" SELECT $type FROM ".PREFIX.SUPPLIER." WHERE $type='".$val."' AND `PKCompanyRefNo`='".$PKCompanyRefNo."' AND `id` <> '".$id."'";
      $res=mysqli_query($conn,$query);
      if(mysqli_num_rows($res)>0){
        return true;
      }else{
        return false;
      } 
  }



  function trimStr($str){
    return trim($str);
  }


  
  /*************************Common Function Ends************************/

  switch($action){
    case 'allvendor':
          $result=array();
        $query=" SELECT * FROM `generatingcompany`";
        $res=mysqli_query($conn,$query);
        if(mysqli_num_rows($res)>0){
          while($row=mysqli_fetch_assoc($res)){
            $result[]=$row;
          }
        }
        echo json_encode($result);die;
        
    break ;


    case 'getvendordetails':
        $id=$_REQUEST['id'];
        if($id>0){
          $query=" SELECT * FROM `generatingcompany` WHERE `PKCompanyRefNo`=".$id."";
          $res=mysqli_query($conn,$query);
          if(mysqli_num_rows($res)>0){
            $row=mysqli_fetch_assoc($res);
          }
          $result=array('status'=>'success','data'=>$row);
        }else{
          $result=array('status'=>'errro','msg'=>'Parameter missing.');
        }
        echo json_encode($row);die;
        
    break ;



    case 'login':
        $userType=$_REQUEST['userType'];
        $password=md5($_REQUEST['password']);
        $email_address=$_REQUEST['email_address'];
        $mobile=$_REQUEST['mobile'];

        if($userType>0){
            if($userType==1){
            $query=" SELECT * FROM `generatingcompany` WHERE `pasword`='".$password."' ";
            if($email_address!=''){
              $query.=" AND `companyEmailIds`='".$email_address."' ";
            }
            if($mobile!=''){
              $query.=" AND `mobile`='".$mobile."' ";
            }
          }
          if($userType==2){
            $query=" SELECT * FROM `supplier` WHERE `email_address`='".$email_address."' AND `password`='".$password."'";
          }
          if($userType==3){
            $query=" SELECT * FROM `client` WHERE `email_address`='".$email_address."' AND `password`='".$password."'";
          }
          //echo $query;die;
          $res=mysqli_query($conn,$query);
          if(mysqli_num_rows($res)>0){
            $row=mysqli_fetch_assoc($res);
          }
          if(!empty($row)){
            $result=array('status'=>'success','data'=>$row);
          }else{
            $result=array('status'=>'error','data'=>"No User Found");
          }
        }else{
          $result=array('status'=>'errro','msg'=>'Parameter missing.');
        }
        echo json_encode($result);die;
        
    break ;


    case 'addsupplier':
    if(array_key_exists('id', $_REQUEST)){
      $postData=array();
      $postData['password']='';
      $postData['id']=$_REQUEST['id'];
    }else{
      $postData['id']=0;
    }

    $postData['PKCompanyRefNo']=$_REQUEST['PKCompanyRefNo'];
    $postData['address']=$_REQUEST['address'];
    $postData['email_address']=$_REQUEST['email_address'];
    $postData['first_name']=$_REQUEST['first_name'];
    $postData['last_name']=$_REQUEST['last_name'];
    $postData['landmark']=$_REQUEST['landmark'];
    $postData['mobile']=$_REQUEST['mobile'];
    $postData['password']="";
    if(array_key_exists('password', $_REQUEST)){
      if($_REQUEST['password']!=''){
        $postData['password']=$_REQUEST['password'];
      }
    }
   
    $postData['pincode']=$_REQUEST['pincode'];
    $postData['created_on']=date('Y-m-d H:i:s');
    if(!empty($postData)){
      if(array_key_exists('id', $postData) && $postData['id']>0){
      $query="UPDATE `supplier` SET `address` = '".$postData['address']."',";
      if($postData['password']!=''){
          $query.="`password` = '".md5($postData['password'])."', ";
      }
      $query.=" `email_address` = '".$postData['email_address']."',`first_name` = '".$postData['first_name']."',";
      $query.=" `last_name` = '".$postData['last_name']."',`landmark` = '".$postData['landmark']."',";
      $query.=" `mobile` = '".$postData['mobile']."' ";
      $query.=" WHERE `supplier`.`id` =".$postData['id']."";
      //echo $query;
      $res=mysqli_query($conn,$query);
      $ress=mysqli_affected_rows($conn);
      }else{
      
      if($postData['password']==''){
        $pass=md5(passGen(8));
      }else{
        $pass=md5($postData['password']);
      }
      //Validate If this Email Address Allready Present
      if(strlen($postData['mobile'])!=10){
        $result=array('status'=>'failed','last_id'=>0,'message'=>'Invalid mobile number '.$postData['mobile']);
        echo json_encode($result); die;
      }else if(isValidSupplier('email_address',$postData['email_address'],$postData['PKCompanyRefNo'])){
        $result=array('status'=>'failed','last_id'=>0,'message'=>'Supplier email already present.');
        echo json_encode($result); die;
      }else if(isValidSupplier('mobile',$postData['mobile'],$postData['PKCompanyRefNo'])){
        $result=array('status'=>'failed','last_id'=>0,'message'=>'Supplier mobile already present.');
        echo json_encode($result); die;
      }else{
            echo $query="INSERT INTO `supplier` (`PKCompanyRefNo` ,`address` ,`email_address` ,`first_name` ,`last_name`,`landmark`,`mobile`,`pincode`,`created_on`,`password`)VALUES ('".$postData['PKCompanyRefNo']."','".$postData['address']."','".$postData['email_address']."','".$postData['first_name']."','".$postData['last_name']."','".$postData['landmark']."','".$postData['mobile']."','".$postData['pincode']."','".$postData['created_on']."','".$pass."')";
            $res=mysqli_query($conn,$query);
            $ress=mysqli_insert_id($conn);  
            if($ress){
              $result=array('status'=>'success','last_id'=>$ress,'message'=>'Supllier added successfully.');
            }else{
              $result=array('status'=>'success','last_id'=>0,'message'=>mysqli_error($conn));
            }
      }
    }
    }
    echo json_encode($result); die;

    break;


    case 'allsupplier':
          $id=$_REQUEST['id'];

        $query=" SELECT * FROM `supplier`";
        if($id!='' && $id >0){
          $query.=" WHERE `PKCompanyRefNo`=".$id."";
        }
        $res=mysqli_query($conn,$query);
        if(mysqli_num_rows($res)>0){
          while($row=mysqli_fetch_assoc($res)){
            $result[]=$row;
          }
        }
        echo json_encode($result);die;
        
    break ;


    /*******************Get Details of Supplier based on vendor Id*************/
    case 'supplierdetails':
    $id=$_REQUEST['id'];
    $vendor_id=$_REQUEST['vendor_id'];

    $query=" SELECT * FROM `supplier`";
    if($id!='' && $id >0){
      $query.=" WHERE `id`=".$id."";
    }
    if($vendor_id!='' && $vendor_id >0){
      $query.=" AND `PKCompanyRefNo`=".$vendor_id."";
    }
    $res=mysqli_query($conn,$query);
    if(mysqli_num_rows($res)>0){
      while($row=mysqli_fetch_assoc($res)){
        $result[]=$row;
      }
    }
    if(!empty($result)){
      $res=array('status'=>'success','data'=>$result);
    }else{
      $res=array('status'=>'failed','messgae'=>'No Details found');
    }
    echo json_encode($res);die; 
    break ;
    /*******************Get Details of Supplier based on vendor Id*************/
    

    case 'updatepassword':
    $postData['password']=$_REQUEST['pasword'];
    $postData['modified_on']=date('Y-m-d H:i:s');

    if(!empty($postData)){
      if(array_key_exists('id', $postData)){
        $query="UPDATE `supplier` SET 
        `password` = '".$postData['password']."',
        `modified_on` = '".$postData['modified_on']."' WHERE `supplier`.`id` =".$postData['PKCompanyRefNo']."";
      $res=mysqli_query($conn,$query);
      $ress=mysqli_affected_rows();
      }else{
        $query="INSERT INTO `supplier` (`PKCompanyRefNo` ,`address` ,`email_address` ,`first_name` ,`last_name`,`landmark`,`mobile`,`password`,`pincode`,`created_on`)VALUES ('".$postData['PKCompanyRefNo']."','".$postData['address']."','".$postData['email_address']."','".$postData['first_name']."','".$postData['last_name']."','".$postData['landmark']."','".$postData['mobile']."','".$postData['password']."','".$postData['pincode']."','".$postData['created_on']."')";
      $res=mysqli_query($conn,$query);
      $ress='';     
      }
      $result=array('status'=>'success','last_id'=>$ress);
    }
    return json_encode($result);die;

    break;



    case 'putvendordetails':
          $postData['PKCompanyRefNo']=$_REQUEST['PKCompanyRefNo'];
          $postData['oldpassword']=$_REQUEST['oldpassword'];
          $postData['password']=trim($_REQUEST['password']);
          $postData['modified_on']=date('Y-m-d H:i:s');
          if(!empty($postData)){
            if(array_key_exists('PKCompanyRefNo', $postData)){
            $query="UPDATE ".PREFIX.VENDOR." SET `pasword` = '".$postData['password']."'   WHERE ".PREFIX.VENDOR.".`PKCompanyRefNo` =".$postData['PKCompanyRefNo']."";
            $res=mysqli_query($conn,$query);
            if($res){
              $result=array('status'=>'success','message'=>"Password Updated successfully");
            }else{
              $result=array('status'=>'failed','message'=>"please try after sometime.");
            }
          }
        }
        echo json_encode($result);die;
    break;



    case 'putsupplierdetails':
          $postData['id']=$_REQUEST['id'];
          $postData['oldpassword']=$_REQUEST['oldpassword'];
          $postData['password']=trim($_REQUEST['password']);
          $postData['modified_on']=date('Y-m-d H:i:s');
          if(!empty($postData)){
            if(array_key_exists('id', $postData)){
            $query="UPDATE ".PREFIX.SUPPLIER." SET `password` = '".$postData['password']."'   WHERE ".PREFIX.SUPPLIER.".`id` =".$postData['id']."";
            $res=mysqli_query($conn,$query);
            if($res){
              $result=array('status'=>'success','message'=>"Password Updated successfully");
            }else{
              $result=array('status'=>'failed','message'=>"please try after sometime.");
            }
          }
        }
        echo json_encode($result);die;
    break;

    


    case 'putsupplierprofiledetails':
          $postData['id']=$_REQUEST['id'];
          $postData['PKCompanyRefNo']=$_REQUEST['PKCompanyRefNo'];
          $postData['fname']=$_REQUEST['fname']; 
          $postData['lname']=$_REQUEST['lname']; 
          $postData['address']=$_REQUEST['address']; 
          $postData['emailAddress']=$_REQUEST['emailAddress']; 
          $postData['landmark']=$_REQUEST['landmark']; 
          $postData['pincode']=$_REQUEST['pincode']; 
          $postData['mobile']=$_REQUEST['mobile']; 
          $postData['modified_on']=date('Y-m-d H:i:s');
          if(!empty($postData)){
            if(array_key_exists('PKCompanyRefNo', $postData)){
            $query="UPDATE ".PREFIX.SUPPLIER." SET ";
            if($postData['fname']!=''){
              $query.=" `first_name` = '".$postData['fname']."',";  
            }

            if($postData['lname']!=''){
              $query.="  `last_name` = '".$postData['lname']."',";  
            }

            if($postData['emailAddress']!=''){
              $query.=" `email_address` = '".$postData['emailAddress']."',";  
            }

            if($postData['landmark']!=''){
              $query.=" `landmark` = '".$postData['landmark']."',";  
            }

            if($postData['pincode']!=''){
              $query.=" `pincode` = '".$postData['pincode']."',";  
            }

            if($postData['mobile']!=''){
              $query.=" `mobile` = '".$postData['mobile']."'";  
            }

          $query.=" WHERE ".PREFIX.SUPPLIER.".`id` ='".$postData['id']."'";
          if(isValidSupplier('id',$postData['id'],$postData['PKCompanyRefNo'])){


            // Validate here is this supplier updating same mobile number and email address with
            // Is Valid Mobile Number 
            if(strlen($postData['mobile'])!=10){
              $result=array('status'=>'failed','last_id'=>0,'message'=>'Invalid mobile number');
              echo json_encode($result); die;
            }
            
            // Is this email address present into database with same vendor 
            if(isSupplierPresent('email_address',$postData['emailAddress'],$postData['PKCompanyRefNo'],$postData['id'])){
              $result=array('status'=>'failed','last_id'=>0,'message'=>'Supplier email already present.');
              echo json_encode($result); die;
            }
            
            // Is this mobile present into database with same vendor 
            if(isSupplierPresent('mobile',$postData['mobile'],$postData['PKCompanyRefNo'],$postData['id'])){
              $result=array('status'=>'failed','last_id'=>0,'message'=>'Supplier mobile already present.');
              echo json_encode($result); die;
            }


              $res=mysqli_query($conn,$query);
              if($res){
                $result=array('status'=>'success','message'=>"profile Updated successfully");
              }else{
                $result=array('status'=>'failed','message'=>"please try after sometime.");
            }
            }else{
                $result=array('status'=>'failed','message'=>"Invalid vendor Id, try after sometime.");
            }
          }
        }
        echo json_encode($result);die;
    break;




    case 'putvendorprofiledetails':
          $postData['PKCompanyRefNo']=$_REQUEST['PKCompanyRefNo'];
          $postData['fname']=$_REQUEST['fname']; 
          $postData['lname']=$_REQUEST['lname']; 
          $postData['companyName']=$_REQUEST['companyName']; 
          $postData['comapnyAdress']=$_REQUEST['comapnyAdress']; 
          $postData['pincode']=$_REQUEST['pincode']; 
          $postData['mobile']=$_REQUEST['mobile']; 
          $postData['panCardNum']=$_REQUEST['panCardNum']; 
          $postData['compIsActive']=$_REQUEST['compIsActive']; 
          $postData['modified_on']=date('Y-m-d H:i:s');
          if(!empty($postData)){
            if(array_key_exists('PKCompanyRefNo', $postData)){
            $query="UPDATE ".PREFIX.VENDOR." SET ";
            if($postData['fname']!=''){
              $query.=" `fname` = '".$postData['fname']."',";  
            }

            if($postData['lname']!=''){
              $query.="  `lname` = '".$postData['lname']."',";  
            }

            if($postData['companyName']!=''){
              $query.=" `companyName` = '".$postData['companyName']."',";  
            }

            if($postData['comapnyAdress']!=''){
              $query.=" `comapnyAdress` = '".$postData['comapnyAdress']."',";  
            }

            if($postData['pincode']!=''){
              $query.=" `pincode` = '".$postData['pincode']."',";  
            }

            if($postData['mobile']!=''){
              $query.=" `mobile` = '".$postData['mobile']."'";  
            }

            if(array_key_exists('panCardNum', $postData)){
              if($postData['panCardNum']!=''){
                $query.=" , `panCardNum` = '".$postData['panCardNum']."'";  
              }
            }

            if(array_key_exists('compIsActive', $postData)){
              if($postData['compIsActive']!=''){
                $query.=" , `compIsActive` = '".$postData['compIsActive']."'";  
              }
            }

          $query.=" WHERE ".PREFIX.VENDOR.".`PKCompanyRefNo` ='".$postData['PKCompanyRefNo']."'";
          if(isValidVendor($postData['PKCompanyRefNo'])){
              $res=mysqli_query($conn,$query);
              if($res){
                $result=array('status'=>'success','message'=>"profile Updated successfully");
              }else{
                $error=mysqli_error($conn);
                $result=array('status'=>'failed','message'=>"please try after sometime, ".$error." ");
            }
            }else{
                $result=array('status'=>'failed','message'=>"Invalid vendor Id, try after sometime.");
            }
          }
        }
        echo json_encode($result);die;
    break;

    


    case 'addclient':
    $postData['password']='';
    $postData['id']=$_REQUEST['id'];
    $postData['supplier_id']=$_REQUEST['supplier_id'];
    $postData['vendor_id']=$_REQUEST['vendor_id'];
    $postData['address']=$_REQUEST['address'];
    $postData['email_address']=$_REQUEST['email_address'];
    $postData['first_name']=$_REQUEST['first_name'];
    $postData['last_name']=$_REQUEST['last_name'];
    $postData['landmark']=$_REQUEST['landmark'];
    $postData['mobile']=$_REQUEST['mobile'];
    if(array_key_exists('password', $_REQUEST)){
      $postData['password']=$_REQUEST['password'];
    }
    $postData['pincode']=$_REQUEST['pincode'];
    $postData['created_on']=date('Y-m-d H:i:s');

    if(!empty($postData)){
      if(array_key_exists('id', $postData) && $postData['id']>0){
        $query="UPDATE `client` SET `address` = '".$postData['address']."',";
        $query.=" `email_address` = '".$postData['email_address']."', ";
        $query.=" `first_name` = '".$postData['first_name']."', ";
        $query.=" `last_name` = '".$postData['last_name']."', ";
        $query.=" `landmark` = '".$postData['landmark']."', ";
        $query.=" `mobile` = '".$postData['mobile']."', ";
        if($postData['password']!=''){
          $query.=" `password` = '".$postData['password']."', ";
        }
        $query.=" `pincode` = '".$postData['pincode']."'";
        $query.=" WHERE `client`.`id` =".$postData['id']."";
        $res=mysqli_query($conn,$query);
        $ress=1;
      }else{
      $query="INSERT INTO `client` (`supplier_id` ,`vendor_id` ,`address` ,`email_address` ,`first_name` ,`last_name`,`landmark`,`mobile`,`password`,`pincode`,`created_on`)VALUES ('".$postData['supplier_id']."','".$postData['vendor_id']."','".$postData['address']."','".$postData['email_address']."','".$postData['first_name']."','".$postData['last_name']."','".$postData['landmark']."','".$postData['mobile']."','".$postData['password']."','".$postData['pincode']."','".$postData['created_on']."')";
      $res=mysqli_query($conn,$query);
      $ress='';     
      }
      $result=array('status'=>'success','last_id'=>$ress);
    }
    return json_encode($result);die;

    break;



    case 'allclient':
        $result=array();  
          $vendor_id=$_REQUEST['vendor_id'];
          $supplier_id=$_REQUEST['supplier_id'];

        $query=" SELECT * FROM `client`";
        if($vendor_id!='' && $vendor_id >0){
          $query.=" WHERE `vendor_id`=".$vendor_id."";
        }

        if($supplier_id!='' && $supplier_id >0){
          $query.=" AND `supplier_id`=".$supplier_id."";
        }
       
        $res=mysqli_query($conn,$query);
        if(mysqli_num_rows($res)>0){
          while($row=mysqli_fetch_assoc($res)){
            $result[]=$row;
          }
        }
        echo json_encode($result);die;
        
    break ;


    case 'addproduct':
    if(isset($_REQUEST['id'])){
      $postData['id']=$_REQUEST['id'];
    }
    $postData['category_id']=$_REQUEST['category_id'];
    $postData['unit_id']=$_REQUEST['unit_id'];
    $postData['PKCompanyRefNo']=$_REQUEST['PKCompanyRefNo'];
    $postData['title']=$_REQUEST['title'];
    $postData['description']=$_REQUEST['description'];
    $postData['product_sku']=$_REQUEST['product_sku'];
    $postData['capacity']=$_REQUEST['capacity'];
    $postData['price']=$_REQUEST['price'];
    $postData['status']=$_REQUEST['status'];
    $postData['created_on']=date('Y-m-d');
    if(!empty($postData)){
      if(array_key_exists('id', $postData) && $postData['id']>0){
        $query="UPDATE ".PREFIX.PRODUCT." SET ";
        $query.=" `title` = '".$postData['title']."', ";
        $query.=" `description` = '".$postData['description']."', ";
        $query.=" `product_sku` = '".$postData['product_sku']."', ";
        $query.=" `capacity` = '".$postData['capacity']."', ";
        $query.=" `price` = '".$postData['price']."', ";
        $query.=" `status` = '".$postData['status']."'";
        $query.=" WHERE ".PREFIX.PRODUCT.".id =".$postData['id']."";
        $res=mysqli_query($conn,$query);
        $ress=mysqli_affected_rows($conn);
        if($ress){
          $result=array('status'=>'success','last_id'=>$postData['id']);
        }else{
          $result=array('status'=>'failed','last_id'=>"");
        }
      }else{
      $query="INSERT INTO ".PREFIX.PRODUCT." (
                            `category_id` ,
                            `unit_id` ,
                            `PKCompanyRefNo` ,
                            `title`,
                            `description`,
                            `product_sku`,
                            `capacity` ,
                            `price` ,
                            `status`,
                            `created_date`,
                            `created_by`
                              )VALUES (
                                  '".mysqlRealString($postData['category_id'])."',
                                  '".mysqlRealString($postData['unit_id'])."',
                                  '".mysqlRealString($postData['PKCompanyRefNo'])."',
                                  '".mysqlRealString($postData['title'])."',
                                  '".mysqlRealString($postData['description'])."',
                                  '".mysqlRealString($postData['product_sku'])."',
                                  '".mysqlRealString($postData['capacity'])."',
                                  '".mysqlRealString($postData['price'])."',
                                  '".mysqlRealString($postData['status'])."',
                                  '".mysqlRealString($postData['created_on'])."',
                                  '".mysqlRealString($postData['PKCompanyRefNo'])."'
                                )";
        $res=mysqli_query($conn,$query);
        $ress=mysqli_insert_id($conn);      
        if($ress){
          $result=array('status'=>'success','last_id'=>$ress);
        }else{
          $result=array('status'=>'failed','last_id'=>"");
        }
      }
      
    }
    echo json_encode($result);die;

    break;


    case 'allproduct':
        $result=array();  
        $vendor_id=$_REQUEST['vendor_id'];
        $start=$_REQUEST['start'];
        $offset=$_REQUEST['offset'];
        $query=" SELECT p.*,c.title as categoryName,v.companyName as vendorName,u.title as unitTitle,DATE(p.created_date) as createdDate";
        $query.=" FROM ".PREFIX.PRODUCT."  as p ";
        $query.=" LEFT JOIN ".PREFIX.MASTER_CATEGORY." as c ON p.category_id=c.id ";
        $query.=" LEFT JOIN ".PREFIX.VENDOR." as v ON v.PKCompanyRefNo=p.PKCompanyRefNo ";
        $query.=" LEFT JOIN ".PREFIX.MEASUREMENT_UNIT." as u ON p.unit_id=u.id ";
        if($vendor_id!='' && $vendor_id >0){
          $query.=" WHERE p.PKCompanyRefNo=".$vendor_id."";
        }
        if($start>=0 && $offset>0){
          $query.=" LIMIT ".$start.",".$offset."";
        }
        $res=mysqli_query($conn,$query);
        if(mysqli_num_rows($res)>0){
          while($row=mysqli_fetch_assoc($res)){
            $result[]=$row;
          }
          if(!empty($result)){
            $res=array('start'=>$start,'offset'=>$offset,'data'=>$result);
          }
        }else{
          $res=array();
        }
        echo json_encode($res);die;
        
    break ;



    case 'getproduct':
    $id=$_REQUEST['prod_id'];
    $vendor_id=$_REQUEST['vendor_id'];

    $query=" SELECT * FROM `product`";
    if($id!='' && $id >0){
      $query.=" WHERE `id`=".$id."";
    }
    if($vendor_id!='' && $vendor_id >0){
      $query.=" AND `PKCompanyRefNo`=".$vendor_id."";
    }
    $res=mysqli_query($conn,$query);
    if(mysqli_num_rows($res)>0){
      while($row=mysqli_fetch_assoc($res)){
        $result=$row;
      }
      $res=array('status'=>'success','data'=>$result);
    }else{
      $res=array('status'=>'failed','data'=>'null');
    }
    echo json_encode($res);die;

    break ;



    case 'getalmasterlist': 
      $type=$_REQUEST['type'];
      if($type=='unit'){
        $query=" SELECT * FROM ".PREFIX.MEASUREMENT_UNIT." WHERE status=1 ORDER BY title";
      }
      if($type=='servicecategory'){
        $query=" SELECT * FROM ".PREFIX.MASTER_CATEGORY." WHERE status=1 ORDER BY title";
      }
      if($type=='states'){
        $query=" SELECT * FROM ".PREFIX.MASTER_STATE." WHERE status=1 ORDER BY name";
      }
      
      $res=mysqli_query($conn,$query);
        if(mysqli_num_rows($res)>0){
          while($row=mysqli_fetch_assoc($res)){
            $result[]=$row;
          }
        }
        echo json_encode($result);
        die;
    break;  

    
  case 'getcitylist': 
      $type=$_REQUEST['type'];
      $state_id=$_REQUEST['state_id'];
      $query=" SELECT * FROM ".PREFIX.MASTER_CITY." WHERE status=1 ";
      if($state_id!=''){
        $query.=" AND `state_id`='".$state_id."'";
      }
      $query.=" ORDER BY name";
      $res=mysqli_query($conn,$query);
        if(mysqli_num_rows($res)>0){
          while($row=mysqli_fetch_assoc($res)){
            $result[]=$row;
          }
        }
        echo json_encode($result);
        die;
    break;  





    case 'createorder':
        $postData['vendor_id']=$_REQUEST['vendor_id'];
	    $postData['supplier_id']=$_REQUEST['supplier_id'];
	    $postData['order_by']=$_REQUEST['order_by'];
	    $postData['product_id']=$_REQUEST['product_id'];
	    $postData['quantity']=$_REQUEST['quantity'];
	    $postData['price']=$_REQUEST['price'];
	    $postData['discount']=$_REQUEST['discount'];
	    $postData['discount_type_id']=$_REQUEST['discount_id'];
	    $postData['discount_price']=$_REQUEST['discount_price'];
	    $postData['total_price']=$_REQUEST['total_price'];
	    $postData['order_status']=$_REQUEST['order_status'];
	    $postData['payment_status']='Dues';
      $postData['ipaddress']=trimStr($_REQUEST['ipaddress']);
	    $postData['created_on']=date('Y-m-d');
	    if(!empty($postData)){
    	  $query="INSERT INTO ".PREFIX.ORDER_BY." (
                            `vendor_id` ,
                            `supplier_id` ,
                            `order_by` ,
                            `product_id`,
                            `quantity`,
                            `price`,
                            `discount` ,
                            `discount_type_id` ,
                            `discount_price`,
                            `total_price`,
                            `order_status`,
                            `payment_status`,
                            `ip_address`,
                            `created_date`
                              )VALUES (
                                  '".mysqlRealString($postData['vendor_id'])."',
                                  '".mysqlRealString($postData['supplier_id'])."',
                                  '".mysqlRealString($postData['order_by'])."',
                                  '".mysqlRealString($postData['product_id'])."',
                                  '".mysqlRealString($postData['quantity'])."',
                                  '".mysqlRealString($postData['price'])."',
                                  '".mysqlRealString($postData['discount'])."',
                                  '".mysqlRealString($postData['discount_type_id'])."',
                                  '".mysqlRealString($postData['discount_price'])."',
                                  '".mysqlRealString($postData['total_price'])."',
                                  '".mysqlRealString($postData['order_status'])."',
                                  '".mysqlRealString($postData['payment_status'])."',
                                  '".mysqlRealString($postData['ipaddress'])."',
                                  '".mysqlRealString($postData['created_on'])."'
                                )";
	        $res=mysqli_query($conn,$query);
	        $ress=mysqli_insert_id($conn);      
	        if($ress){
	          //Update Valid Order Id For this Order
	          $lastOrderId=getOrderId($ress);
	          $query="UPDATE `".PREFIX.ORDER_BY."`	 SET ";
		        $query.=" `orderid` = '".$lastOrderId."'";
		        $query.=" WHERE ".PREFIX.ORDER_BY.".id =".$ress."";
		        $res1=mysqli_query($conn,$query);
		        $ress2=mysqli_affected_rows($conn);
		        if($ress2){
		      	 $result=array('status'=>'success','orderid'=>$lastOrderId,'message'=>"Order placed successfully.");	
		        }else{
		      	 $result=array('status'=>'success','orderid'=>$lastOrderId,'message'=>"Order in process.");
		        }
	         }else{
	           $result=array('status'=>'failed','last_id'=>"",'message'=>"Order not placed.");
	        }
      
      
    }
    echo json_encode($result);die;

    break;






    case 'updateorder':
        $postData['id']=$_REQUEST['id'];
        $postData['orderid']=$_REQUEST['orderid'];
        $postData['order_status']=mysqlRealString($_REQUEST['order_status']);
        $postData['payment_status']=mysqlRealString($_REQUEST['payment_status']);
        
        $status=array('Ordered','InProcess','Delivered','Delivered','Return');
        $paymentStatus=array('Done','Dues');
        if($postData['order_status']!=''){
          if(in_array($postData['order_status'], $status)){

          }else{
            $result=array('status'=>'failed','message'=>"Invalid Order Status Value.");
            echo json_encode($result);die;
          }
        }


        if($postData['payment_status']!=''){
          if(in_array($postData['payment_status'], $paymentStatus)){

          }else{
            $result=array('status'=>'failed','message'=>"Invalid Order payment status.");
            echo json_encode($result);die;
          }
        }

        $update='F';
        $query="UPDATE `".PREFIX.ORDER_BY."` SET ";
            
            if($postData['order_status']!=''){
              $query.=" `order_status` = '".$postData['order_status']."'";
              $update='T';
            }

            if(($postData['payment_status']!='') && ($postData['order_status']!='')){
              $query.=" , `payment_status` = '".$postData['payment_status']."'";
              $update='T';
            }

            if(($postData['payment_status']!='') && ($postData['order_status']=='')){
              $query.=" `payment_status` = '".$postData['payment_status']."'";
              $update='T';
            }

            if($update=='T'){
              $query.=" WHERE ".PREFIX.ORDER_BY.".id ='".$postData['id']."'";
              $res1=mysqli_query($conn,$query);
              $ress2=mysqli_affected_rows($conn);
              if($ress2){
               $result=array('status'=>'success','message'=>"Order update successfully.");  
              }else{
               $result=array('status'=>'failed','message'=>"Order not update.");
              }
            }else{
               $result=array('status'=>'failed','message'=>"Nothing to new update, update parameter missing.");
            }
            echo json_encode($result);die;
        
    break;



    case 'getorderlist': 
      
      		if(isset($_REQUEST['vendor_id'])){
              $vendor_id=$_REQUEST['vendor_id'];
            }else{
              $vendor_id="";
            }

            if(isset($_REQUEST['category_id'])){
              $category_id=$_REQUEST['category_id'];
            }else{
              $category_id="";
            }

            if(isset($_REQUEST['supplier_id'])){
              $supplier_id=$_REQUEST['supplier_id'];
            }else{
	           $supplier_id="";	
            }

            if(isset($_REQUEST['user_id'])){
              $user_id=$_REQUEST['user_id'];
            }else{
	            $user_id="";	
            }

            if(isset($_REQUEST['date'])){
              $date=$_REQUEST['date'];
            }else{
              $date="";
            }

            if(isset($_REQUEST['product_id'])){
              $product_id=$_REQUEST['product_id'];
            }else{
            	$product_id="";
            }

            if(isset($_REQUEST['order_status'])){
              $order_status=$_REQUEST['order_status'];
            }else{
              $order_status="";
            }

            if(isset($_REQUEST['payment_status'])){
              $payment_status=$_REQUEST['payment_status'];
            }else{
            	$payment_status='';
            }


            if(isset($_REQUEST['orderid'])){
              $orderid=$_REQUEST['orderid'];
            }else{
            	$orderid="";	
            }

            if(isset($_REQUEST['start'])){
              $start=$_REQUEST['start'];
            }else{
            	$start="0";	
            }


            if(isset($_REQUEST['offset'])){
              $offset=$_REQUEST['offset'];
            }else{
              $offset="0";	
            }



        $query="SELECT v.companyName as vendorName,p.title as productName,p.description as description,p.product_sku as productSku,p.price as productPrice,mc.title as categoryTitle,o.*,md.type as discountType ";
        $query.=" FROM `product_order` as o"; 
		$query.=" LEFT JOIN `master_discount_type` as md on md.id=o.discount_type_id"; 
		$query.=" LEFT JOIN `product` AS p on o.product_id=p.id";
		$query.=" LEFT JOIN `master_service_category` AS mc ON mc.id=p.category_id";
		$query.=" LEFT JOIN `generatingcompany` AS v ON v.PKCompanyRefNo=p.PKCompanyRefNo";
		$query.=" WHERE o.order_by<>0";
		if($vendor_id!=''){
			$query.=" AND v.PKCompanyRefNo='".$vendor_id."'";	
		}

		if($supplier_id!=''){
			$query.=" AND o.supplier_id='".$supplier_id."'";	
		}

		if($user_id!=''){
			$query.=" AND o.user_id='".$user_id."'";	
		}

		if($product_id!=''){
			$query.=" AND o.product_id='".$product_id."'";	
		}

		if($category_id!=''){
			$query.=" AND p.category_id='".$category_id."'";	
		}

		if($orderid!=''){
			$query.=" AND o.orderid='".$orderid."'";	
		}

		if($order_status!=''){
			$query.=" AND o.order_status='".$order_status."'";	
		}

		if($payment_status!=''){
			$query.=" AND o.payment_status='".$payment_status."'";	
		}

		$query.=" order by id DESC";    


		//Get Total Records
		$finalQuery=$query;


		if($offset>0){
			$query.=" LIMIT $start,$offset";    	
		}
	    $res=mysqli_query($conn,$query);
        if(mysqli_num_rows($res)>0){
          while($row=mysqli_fetch_assoc($res)){
            $result[]=$row;
          }
        }


        $resCount=mysqli_query($conn,$finalQuery);
		$count=mysqli_num_rows($resCount);
        $result['total']=$count;
        $result['start']=$start;
        $result['offset']=$offset;
        echo json_encode($result);
    break;  






    case 'orderpayment':
      $postData['order_id']=trimStr($_REQUEST['order_id']);
      $postData['user_id']=trimStr($_REQUEST['user_id']);
      $postData['payment_by']=trimStr($_REQUEST['payment_by']);
      $postData['total_amount']=trimStr($_REQUEST['total_amount']);
      $postData['amount']=trimStr($_REQUEST['amount']);

      if($postData['total_amount']==$postData['amount']){
        $postData['balance']=0;  
      }else{
        if($postData['amount']>$postData['total_amount']){
          $result=array('status'=>'failed','message'=>"Invalid amount, Payment amount should be less than total amount.");
          echo json_encode($result);die;
        }else{
          $postData['balance']=$postData['total_amount']-$postData['amount'];
        }
      }
      $postData['payment_mode']=trimStr($_REQUEST['payment_mode']);
      if($postData['balance']>0){
        $postData['payment_status']='Dues';
      }else if($postData['balance']==0){
        $postData['payment_status']='Paid';
      }else{
        $postData['payment_status']=trimStr($_REQUEST['payment_status']);
      }
      $postData['payment_date']=trimStr($_REQUEST['payment_date']);
      $postData['txn_no']=trimStr($_REQUEST['txn_no']);
      $postData['ipaddress']=trimStr($_REQUEST['ipaddress']);
      $postData['created_on']=date('Y-m-d H:i:s');
      if(!empty($postData)){
        $query="INSERT INTO ".PREFIX.PAYMENT." (
                            `order_id` ,
                            `user_id` ,
                            `payment_by` ,
                            `total_amount`,
                            `amount`,
                            `payment_mode`,
                            `balance`,
                            `payment_status` ,
                            `payment_date` ,
                            `txn_no`,
                            `ip_adddress`,
                            `created_date`
                              )VALUES (
                                  '".mysqlRealString($postData['order_id'])."',
                                  '".mysqlRealString($postData['user_id'])."',
                                  '".mysqlRealString($postData['payment_by'])."',
                                  '".mysqlRealString($postData['total_amount'])."',
                                  '".mysqlRealString($postData['amount'])."',
                                  '".mysqlRealString($postData['payment_mode'])."',
                                  '".mysqlRealString($postData['balance'])."',
                                  '".mysqlRealString($postData['payment_status'])."',
                                  '".mysqlRealString($postData['payment_date'])."',
                                  '".mysqlRealString($postData['txn_no'])."',
                                  '".mysqlRealString($postData['ipaddress'])."',
                                  '".mysqlRealString($postData['created_on'])."'
                                )";
          $res=mysqli_query($conn,$query);
          $ress=mysqli_insert_id($conn);      
          if($ress){
             $result=array('status'=>'success','orderid'=>$ress,'message'=>" successfully.");  
           }else{
             $result=array('status'=>'failed','last_id'=>"",'message'=>"Order not placed.");
          }
      }
      echo json_encode($result);die;
      break;



    default : echo "Try After sometime, method missing";
    break;
  }





  

  
?>