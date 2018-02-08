<?php require('../config/config.inc.php');
  require_once('api.class.php'); 
  /*
  -- ------------------------------------------------
  -- Include User Class For Handling Amdin Activities
  -- ------------------------------------------------
  */
  require_once('user.class.php'); 


  require('validation_functions.php');
  $apiServiceObj= new apiService($db);
  $userServiceObj= new userService($db);
  $data=file_get_contents("php://input");
  $action=trim($_REQUEST['action']);

  switch($action){


    case 'getchecksum':
        if(!empty($_POST)){
          $str=SALT.'|'.TOKEN;
          //If this is Post Value. there is must be "user"
          //Key into array
          if(array_key_exists('user', $_POST)){
              foreach($_POST['user'] as $k=>$v){
                $str.='|'.$v;  
              }
              //Add From
              $str.="|".$_POST['from'].'|'.$_POST['ipaddress'];
          }else{
            foreach($_POST as $k=>$v){
              $str.='|'.$v;
            }
          }
           $res=array('status'=>'success','checksum'=>md5($str));
        }else{
          $res=array('status'=>'failed','checksum'=>"",'msg'=>'Invalid Post data');
        }
        echo json_encode($res);die;
    break;

    case 'webmasterlogin':
        $postData=$data;
        if(!empty($postData)){
        $dataArr=json_decode($postData,true);
          $loginData=(array)$dataArr['data']['user'];
          $postFields['email_address']=$loginData['email_address'];
          $postFields['password']=$loginData['password'];
          $userServiceObj->postFields=$postFields; 
          $res1=$userServiceObj->adminLogin();
          if(!empty($res1)){
              $res=json_encode(array('status'=>'success','user'=>$res1,'message'=>"Logged successfully."));
          }else{
              $res=json_encode(array('status'=>'failed','message'=>"Invalid Login Credentials."));
          }
              echo $res;
        }else{
                $res=json_encode(array('message'=>'Parameter mising.','error'=>'error'));
              echo $res;
        }
    break ;

    case 'addvendor':
        $postData=$data;

        if(!empty($postData)){
        $dataArr=json_decode($postData,true);

          $companyData=(array)$dataArr['data']['vendors'];
          if(array_key_exists('PKCompanyRefNo', $companyData)){
            $postFields['PKCompanyRefNo']=$companyData['PKCompanyRefNo'];
            }
          $postFields['companyName']=$companyData['companyName'];
          $postFields['zipNo']=$companyData['zipNo'];
          $postFields['comapnyAdress']=$companyData['comapnyAdress'];
          $postFields['companyEmailIds']=$companyData['companyEmailIds'];
          $postFields['cellNo']=$companyData['cellNo'];
          if(array_key_exists('compIsActive', $companyData)){
            $postFields['compIsActive']=$companyData['compIsActive'];
            }
            if($companyData['pasword']!=''){
            echo $postFields['pasword']=$companyData['pasword'];
            }
          $apiServiceObj->postFields=$postFields; 
          $res1=$apiServiceObj->saveCompanyData($apiServiceObj->postFields);
              $res=json_encode(array('status'=>'success','user'=>$res1));
          echo $res;
        }else{
                $res=array('message'=>'Parameter mising.','error'=>'error');
          return $res;
        }
    break ;

   
    case 'editvendor':  
        $postData=$_REQUEST['id'];
        $res1=$apiServiceObj->getCompanyDetailsById($postData);
            $res=json_encode(array('status'=>'success','user'=>$res1));
        echo $res;
    break ;


    

    


    case 'editsupplier':  
        $postData['id']=$_REQUEST['id'];
        $postData['vendor_id']=$_REQUEST['vendor_id'];
        $apiServiceObj->postFields=$postData;
        $res1=$apiServiceObj->getSupplierData($postData);
            $res=json_encode(array('status'=>'success','user'=>$res1));
        echo $res;
    break ;


    case 'updatepasswordsupplier':  
        $postData=$data;
        $apiServiceObj->postFields=$postData;
        $res1=$apiServiceObj->saveSupplierData($postData);
            $res=$res1;
            $res=json_encode(array('status'=>'success','user'=>$res1));
        return $res1; die;
    break ;


    case 'addclient':
        $postData=$data;
        if(!empty($postData)){
        $dataArr=json_decode($postData,true);
          $companyData=(array)$dataArr['data']['client'];
          if(array_key_exists('id', $companyData)){
            $postFields['id']=$companyData['id'];
            }

            if(array_key_exists('supplier_id', $companyData)){
            $postFields['supplier_id']=$companyData['supplier_id'];
            }
            if(array_key_exists('vendor_id', $companyData)){
            $postFields['vendor_id']=$companyData['vendor_id'];
            }
          $postFields['address']=$companyData['address'];
          $postFields['email_address']=$companyData['email_address'];
          $postFields['first_name']=$companyData['first_name'];
          $postFields['last_name']=$companyData['last_name'];
          $postFields['landmark']=$companyData['landmark'];
          $postFields['mobile']=$companyData['mobile'];
          $postFields['password']=md5($companyData['password']);
          $postFields['pincode']=$companyData['pincode'];
          $apiServiceObj->postFields=$postFields; 
          $res1=$apiServiceObj->saveClientData($apiServiceObj->postFields);
              $res=json_encode(array('status'=>'success','user'=>$res1));
          echo $res;
        }else{
                $res=array('message'=>'Parameter mising.','error'=>'error');
          return $res;
        }
    break ;


   




    case 'allclientlist': 
        $supplier_id=$_REQUEST['supplier_id'];
        $vendor_id=$_REQUEST['vendor_id'];
        $res1=$apiServiceObj->getAllClientList($supplier_id,$vendor_id);
            $res=json_encode(array('status'=>'success','user'=>$res1));
        echo $res;
    break ;



    case 'getclientdetails':  
        $supplier_id=$_REQUEST['supplier_id'];
        $vendor_id=$_REQUEST['vendor_id'];
        $id=$_REQUEST['client_id'];
        $res1=$apiServiceObj->getClientDetails($id,$supplier_id,$vendor_id);
            $res=json_encode(array('status'=>'success','user'=>$res1));
        echo $res;
    break ;





    /******************Get all Vendor List Start**********************/
    case 'allvendor': 
        if(isValidCheckSum($_REQUEST)){
          $res1=$apiServiceObj->getcompanyList();
          $res=json_encode(array('status'=>'success','user'=>$res1));
        }else{
          $res=json_encode(array('status'=>'failed','msg'=>"Invalid Checksum Value"));
        }
        echo $res;

    break ;
    /******************Get all Vendor List Ends**********************/





    /******************Supplier Can Update Profile Start****************/
	    case 'updatesupplierprofile':
        $postData=$data;
        if(!empty($postData)){
        $dataArr=json_decode($postData,true);
        //Validation for this Vendor
        if(isValidSupplierProfileData($dataArr)){
          $companyData=(array)$dataArr['data']['user'];
            if(array_key_exists('id', $companyData)){
              $postFields['id']=$companyData['id'];
            }

            //check here what data will be update here by vendor  
            if($dataArr['data']['user']['type']=='pass'){
               $postFields['oldpassword']=$companyData['oldpassword'];
               $postFields['password']=md5($companyData['password']);
               $postFields['id']=$companyData['id'];
               $apiServiceObj->postFields=$postFields;
	           $res1=$apiServiceObj->saveSupplierPassword();
	           $res=json_encode(array('status'=>'success','user'=>json_decode($res1)));
            }

            if($dataArr['data']['user']['type']=='profile'){
              	if(array_key_exists('PKCompanyRefNo', $companyData) && ($companyData['PKCompanyRefNo']!='')){
						if(array_key_exists('firstName', $companyData)){
							$postFields['fname']=$companyData['firstName'];	
						}

						if(array_key_exists('lastName', $companyData)){
							$postFields['lname']=$companyData['lastName'];	
						}
		
						if(array_key_exists('address', $companyData)){
							$postFields['address']=$companyData['address'];	
						}

						if(array_key_exists('landmark', $companyData)){
							$postFields['landmark']=$companyData['landmark'];	
						}

						if(array_key_exists('pincode', $companyData)){
							$postFields['pincode']=$companyData['pincode'];	
						}

						if(array_key_exists('mobile', $companyData)){
							$postFields['mobile']=$companyData['mobile'];	
						}
						
						if(array_key_exists('emailAddress', $companyData)){
							$postFields['emailAddress']=$companyData['emailAddress'];	
						}
		                $postFields['PKCompanyRefNo']=$companyData['PKCompanyRefNo'];
		                $postFields['id']=$companyData['id'];
		                $apiServiceObj->postFields=$postFields; 
		                $res1=$apiServiceObj->saveSupplierProfile();
		                $res=json_encode(array('status'=>'success','user'=>json_decode($res1)));
				}else{
					$res=json_encode(array('status'=>'failed','message'=>'invalid Input, user id is missing'));		
				}    
              }
              echo $res;
          }else{
                  $res=array('status'=>'failed','message'=>'invalid user id mising.');
            return $res;
          }
      }
    break ;
    /******************Vendor Can Update Profile Start****************/






 	
 	/*********************Get Supplier Details Of Vendor Start******************/
 	case 'supplierdetails': 
        $id=$_REQUEST['id'];
        $vendor_id=$_REQUEST['vendor_id'];
        $res1=$apiServiceObj->getSupplierDetailsById($id,$vendor_id);
            $res=json_encode(array('status'=>'success','user'=>$res1));
        echo $res;
    break ;
	/*********************Get Supplier Details Of Vendor Ends******************/
   



	/*********************All Supplier Of Vendor Start******************/
	case 'allsupplier': 
        $id=$_REQUEST['vendor_id'];
        $res1=$apiServiceObj->getSupplierList($id);
            $res=json_encode(array('status'=>'success','user'=>$res1));
        echo $res;
    break ;


    /*********************Add New Supplier From Vendor Start******************/
	case 'addsupplier':
        $postData=$data;
        if(!empty($postData)){
        $dataArr=json_decode($postData,true);
          $companyData=(array)$dataArr['data']['user'];
          if(array_key_exists('PKCompanyRefNo', $companyData)){
            $postFields['PKCompanyRefNo']=$companyData['PKCompanyRefNo'];
            }
          $postFields['first_name']=$companyData['first_name'];
          $postFields['last_name']=$companyData['last_name'];
          $postFields['email_address']=$companyData['email_address'];
          $postFields['address']=$companyData['address'];
          $postFields['landmark']=$companyData['locality'];
          $postFields['mobile']=$companyData['mobile'];
          //$postFields['password']=md5($companyData['password']);
          $postFields['pincode']=$companyData['pincode'];
          if(array_key_exists('password', $companyData)){
            $postFields['password']=$companyData['password'];
          }
          
          $apiServiceObj->postFields=$postFields;
          $res1=$apiServiceObj->saveSupplierData($apiServiceObj->postFields);
          echo $res1;
        }else{
                $res=array('message'=>'Parameter mising.','error'=>'error');
          return $res;
        }
    break ;
    /*********************Add New Supplier From Vendor Ends******************/






    
    /*********************Get all products From Vendor start******************/
    case 'allproduct':  
        $vendor_id=$_REQUEST['vendor_id'];
        $res1=$apiServiceObj->getAllProductList($vendor_id);
            $res=json_encode(array('status'=>'success','user'=>$res1));
        echo $res;
    break ;
    /*********************Get all products From Vendor ends******************/






    /******************Get products details From Vendor start****************/
    case 'getproduct':  
        $postData['prod_id']=trim($_REQUEST['prod_id']);
        $postData['vendor_id']=trim($_REQUEST['vendor_id']);
        $apiServiceObj->postFields=$postData;
        $res1=$apiServiceObj->getProductDetailsById();
        $res=json_encode(array('status'=>'success','user'=>$res1));
        echo $res;
    break ;
	/******************Get products details From Vendor ends****************/





	/******************Add New Products From Vendor Start****************/
	case 'addproduct':
            $postData=$data;
            if(!empty($postData)){
            $dataArr=json_decode($postData,true);
            if(isValidProductData($dataArr)){
              $saveData=(array)$dataArr['data']['user'];
                if(array_key_exists('id', $saveData)){
                  $postFields['id']=$saveData['id'];
                  }
                if(array_key_exists('vendorId', $saveData)){
                  $postFields[' PKCompanyRefNo']=$saveData['vendorId'];
                }
                $postFields['category_id']=$saveData['catID'];
                $postFields['unit_id']=$saveData['unitID'];
                $postFields['title']=$saveData['title'];
                $postFields['description']=$saveData['description'];
                $postFields['product_sku']=$saveData['sku'];
                $postFields['capacity']=$saveData['capacity'];
                $postFields['price']=$saveData['price'];
                $postFields['status']=$saveData['status'];
                $apiServiceObj->postFields=$postFields; 
                $res1=$apiServiceObj->saveProductData($apiServiceObj->postFields);
                    $res=json_encode(array('status'=>'success','user'=>json_decode($res1)));
                    echo $res; exit;
              }else{
                      $res=array('message'=>'Parameter mising.','error'=>'error');
                return $res;
              }
            }
        break ;
      	/******************Add New Products From Vendor Ends****************/





      	/******************Vendor Can Update Profile Start****************/
	    case 'updatevendorprofile':
        $postData=$data;
        if(!empty($postData)){
        $dataArr=json_decode($postData,true);
        //Validation for this Vendor
        if(isValidVendorProfileData($dataArr)){
          $companyData=(array)$dataArr['data']['user'];
            if(array_key_exists('PKCompanyRefNo', $companyData)){
              $postFields['PKCompanyRefNo']=$companyData['PKCompanyRefNo'];
            }

            //check here what data will be update here by vendor  
            if($dataArr['data']['user']['type']=='pass'){
               $postFields['oldpassword']=$companyData['oldpassword'];
               $postFields['password']=md5($companyData['password']);
               $postFields['PKCompanyRefNo']=$companyData['PKCompanyRefNo'];
	           $res1=$apiServiceObj->saveVendorPassword();
	           $res=json_encode(array('status'=>'success','user'=>json_decode($res1)));
            }

              if($dataArr['data']['user']['type']=='profile'){
              	if(array_key_exists('PKCompanyRefNo', $companyData) && ($companyData['PKCompanyRefNo']!='')){
						if(array_key_exists('firstName', $companyData)){
							$postFields['fname']=$companyData['firstName'];	
						}

						if(array_key_exists('lastName', $companyData)){
							$postFields['lname']=$companyData['lastName'];	
						}

						if(array_key_exists('companyName', $companyData)){
							$postFields['companyName']=$companyData['companyName'];	
						}

						if(array_key_exists('comapnyAdress', $companyData)){
							$postFields['comapnyAdress']=$companyData['comapnyAdress'];	
						}

						if(array_key_exists('pincode', $companyData)){
							$postFields['pincode']=$companyData['pincode'];	
						}

						if(array_key_exists('mobile', $companyData)){
							$postFields['mobile']=$companyData['mobile'];	
						}

						if(array_key_exists('panCard', $companyData)){
							$postFields['panCardNum']=$companyData['panCard'];	
						}

						if(array_key_exists('emailAddress', $companyData)){
							$postFields['companyEmailIds']=$companyData['emailAddress'];	
						}

            if(array_key_exists('compIsActive', $companyData)){
              $postFields['compIsActive']=$companyData['compIsActive'];  
            }
		                $postFields['PKCompanyRefNo']=$companyData['PKCompanyRefNo'];
		                $apiServiceObj->postFields=$postFields; 
		                $res1=$apiServiceObj->saveVendorProfile();
		                $res=json_encode(array('status'=>'success','user'=>json_decode($res1)));
				}else{
					$res=json_encode(array('status'=>'failed','message'=>'invalid Input, user id is missing'));		
				}    
              }
              echo $res;
          }else{
                  $res=array('status'=>'failed','message'=>'invalid user id mising.');
                  
            return $res;
          }
      }
    break ;
    /******************Vendor Can Update Profile Start****************/



    /******************Add New/ Register New Vendor By Admin Start****************/
    case 'addnewvendor':
        $postData=$data;
        if(!empty($postData)){
        $dataArr=json_decode($postData,true);
        //Validation for this Vendor
        if(isValidVendorData($dataArr)){
          $companyData=(array)$dataArr['data']['user'];
            if(array_key_exists('PKCompanyRefNo', $companyData)){
              $postFields['PKCompanyRefNo']=$companyData['PKCompanyRefNo'];
              }
            $postFields['fname']=$companyData['fname'];
            $postFields['lname']=$companyData['lname'];
            $postFields['companyName']=$companyData['compname'];
            $postFields['zipNo']=$companyData['pincode'];
            $postFields['comapnyAdress']=$companyData['address'];
            $postFields['companyEmailIds']=$companyData['emailaddress'];
            $postFields['mobile']=$companyData['mobile'];
            $postFields['pincode']=$companyData['pincode'];
            if(array_key_exists('compIsActive', $companyData)){
                $postFields['compIsActive']=$companyData['compIsActive'];
              }
            if($companyData['password']!=''){
                $postFields['pasword']=$companyData['password'];
            }
            $apiServiceObj->postFields=$postFields; 
            $res1=$apiServiceObj->saveCompanyData($apiServiceObj->postFields);
                $res=json_encode(array('status'=>'success','user'=>json_decode($res1)));
            echo $res;
          }else{
                  $res=array('message'=>'Parameter mising.','error'=>'error');
            return $res;
          }
      }
    break ;

    /******************Add New/ Register New Vendor By Admin Start****************/



    /******************Get List Of Master Tables Start****************/

    case 'getlist':
          $type=trim($_REQUEST['type']);
          $masterArr=array('unit','servicecategory','states','cities');
          if(in_array($type,$masterArr)){
            $res1=$apiServiceObj->getAllMasterList($type);
            $res=array('status'=>'success','data'=>$res1);
          }else{
            $res=array('status'=>'failed','message'=>'invalid input.');
          }
          echo json_encode($res);exit;
    break ;
    /******************Get List Of Master Tables Ends****************/




    /******************Get List All City Based On state Start****************/
    case 'getcitylist':
          $type=trim($_REQUEST['type']);
          $state_id=trim($_REQUEST['state_id']);
          $masterArr=array('cities');
          if(in_array($type,$masterArr)){
            $res1=$apiServiceObj->getAllCityList($type,$state_id);
            $res=array('status'=>'success','data'=>$res1);
          }else{
            $res=array('status'=>'failed','message'=>'invalid input.');
          }
          echo json_encode($res);exit;
    break ;
    /******************Get List All City Based On state Start****************/




    /******************Get List All Products Of Vendor****************/
    case 'getvendorproductlist':
          $params=array();
          $type=trim($_REQUEST['type']);
          if(isset($_REQUEST['start'])){
            $start=trim($_REQUEST['start']);
            $params['start']=$start;
          }

          if(isset($_REQUEST['offset'])){
            $offset=trim($_REQUEST['offset']);
            $params['offset']=$offset;
          }

          $vendorId=trim($_REQUEST['vendor_id']);
          $params['vendorId']=$vendorId;
          if($type=='products'){
            $res1=$apiServiceObj->getAllVendorProductList($params);
            $res=array('status'=>'success','data'=>$res1);
          }else{
            $res=array('status'=>'failed','message'=>'invalid input.');
          }
          echo json_encode($res);exit;
    break;
    /******************Get List All Products Of Vendor****************/



    /******************Get Vendor Profile Details****************/
    case 'getvendorprofile': 
          $id=trim($_REQUEST['id']);
          if($id!=''){
            $res1=$apiServiceObj->getVendorDetails($id);
            $res=array('status'=>'success','data'=>$res1);
          }else{
            $res=array('status'=>'failed','message'=>'invalid input.');
          }
          echo json_encode($res);exit;
    break ;
    /******************Get Vendor Profile Details****************/



	/******************Vendor Login Methods****************/
    case 'login':
       $postData=$data;
       if(!empty($postData)){
       $dataArr=json_decode($postData,true);
       if(isset($dataArr['data']['user']['mobile'])){
         if($dataArr['data']['user']['mobile']=='' && $dataArr['data']['user']['email_address']==''){
            $res=array('status'=>'failed','message'=>'Please enter mobile or email address.');
            echo json_encode($res); exit;
         }
       }
       if($dataArr['data']['user']['password']==''){
         $res=array('status'=>'failed','message'=>'Please enter password.');
         echo json_encode($res); exit;
       }
       if($dataArr['data']['user']['mobile']!='' || $dataArr['data']['user']['email_address']!=''){
            $postFields['mobile']=$dataArr['data']['user']['mobile'];
            $postFields['email_address']=$dataArr['data']['user']['email_address'];
            $postFields['password']=$dataArr['data']['user']['password'];
            $postFields['userType']=$dataArr['data']['user']['userType'];
            $apiServiceObj->postFields=$postFields; 
            $res=$apiServiceObj->vendorLogin();
            //Set All the Session for website  
            if($res['status']=='success'){
              $_SESSION['userData']['userType']=1;
              $_SESSION['userData']['user']=$res['user']['data'];
            }
            //$res=array('status'=>'failed','message'=>'Please enter mobile or email address.');
            echo json_encode($res); exit;
       }
      }else{
                $res=array('message'=>'Parameter mising.','error'=>'error');
          return $res;
        }
    break ;
    /******************Vendor Login Methods****************/



    /******************Generate Order Methods****************/
    case 'createorder':
    	   $postData=$data;
        if(!empty($postData)){
        	$dataArr=json_decode($postData,true);
        	$orderData=(array)$dataArr['data']['user'];
          $orderData['ipaddress']=$dataArr['data']['ipaddress'];
        	$apiServiceObj->postFields=$orderData; 
        	$res=$apiServiceObj->createOrderData();
        	echo json_encode($res); exit;
      	}
        

    break;
    /******************Generate Order Methods****************/




    /******************Update Order Methods****************/
    case 'updateorder':
         $postData=$data;
        if(!empty($postData)){
          $dataArr=json_decode($postData,true);
          $orderData=(array)$dataArr['data']['user'];
          $apiServiceObj->postFields=$orderData; 
          $res=$apiServiceObj->updateOrderData();
          echo json_encode($res); exit;
        }
        

    break;
    /******************Update Order Methods****************/





     /******************Update Order Methods****************/
    case 'orderpayment':
         $postData=$data;
        if(!empty($postData)){
          $dataArr=json_decode($postData,true);
          $orderData=(array)$dataArr['data']['user'];
          $orderData['ipaddress']=$dataArr['data']['ipaddress'];
          $apiServiceObj->postFields=$orderData; 
          $res=$apiServiceObj->OrderPayment();
          echo json_encode($res); exit;
        }
        

    break;
    /******************Update Order Methods****************/





    /******************Get Order List Methods****************/
    case 'getorderlist':
        if($_REQUEST['action']=='getorderlist'){
            if(isset($_REQUEST['vendor_id'])){
              $params['vendor_id']=$_REQUEST['vendor_id'];
            }

            if(isset($_REQUEST['supplier_id'])){
              $params['supplier_id']=$_REQUEST['supplier_id'];
            }

            if(isset($_REQUEST['user_id'])){
              $params['user_id']=$_REQUEST['user_id'];
            }

            if(isset($_REQUEST['date'])){
              $params['date']=$_REQUEST['date'];
            }

            if(isset($_REQUEST['product_id'])){
              $params['product_id']=$_REQUEST['product_id'];
            }

            if(isset($_REQUEST['order_status'])){
              $params['order_status']=$_REQUEST['order_status'];
            }

            if(isset($_REQUEST['payment_status'])){
              $params['payment_status']=$_REQUEST['payment_status'];
            }

            if(isset($_REQUEST['orderid'])){
              $params['orderid']=$_REQUEST['orderid'];
            }

            if(isset($_REQUEST['category_id'])){
              $params['category_id']=$_REQUEST['category_id'];
            }

            if(isset($_REQUEST['start'])){
              $params['start']=$_REQUEST['start'];
            }

            if(isset($_REQUEST['offset'])){
              $params['offset']=$_REQUEST['offset'];
            }

            $apiServiceObj->postFields=$params;
            $res1=$apiServiceObj->getOrderListData();
            $resultArr=json_decode($res1,true);
            if($resultArr['total']>0){
              $res=array('status'=>'success','data'=>$resultArr);
            }else{
              $res=array('status'=>'faild','data'=>"","message"=>"No result found.");
            }  
            
          }else{
            $res=array('status'=>'failed','message'=>'invalid input.');
          }
        echo json_encode($res); exit;
    break;
    /****************Get Order List Methods***************/


    default : echo "Try After sometime, method missing";
    break;
  }


  
?>