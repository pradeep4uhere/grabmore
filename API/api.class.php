<?php
class apiService{

  public $url;
  public $db;

  public function __construct(){
    $this->session_id=md5(session_id());
    $this->totken='ASDSGFDKDJ565SKDNKIOERU2389423JB42378RY23B';
    $this->postFields=array();
    $this->email_address='';
    $this->id='';
    $this->api_url='';
    $this->postFields='';
    
  }


  function dbConnect(){
    $conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_DATABASE); 
    //$rid=mysql_select_db(DB_DATABASE) or die('Mysql not Connect');
	if (!$conn) {
	    echo "Error: Unable to connect to MySQL." . PHP_EOL;
	    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	    exit;
	}
    $this->db=$conn;
    return $conn;


  }


  function logout(){
    session_destroy();
    header('location:login.php');
  }


  



  /*
   * @Author: Pradeep Kumar
   * @Description: To Login into Vendor
   */
  function vendorLogin(){
    $res = array('status'=>'failed','user'=>'');
    $postdata=$this->postFields;
    $api_url=LOGIN_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    $resultArr=json_decode($result,true);
    if(!empty($resultArr)){
      $status=$resultArr['status'];
      if($status=='success'){
        
      }
      $res = array('status'=>$status,'user'=>$resultArr);
    }
    return $res;
    die;

  }



















  // User Login Info
  function userLogin(){
    $res = array('status'=>'error','user'=>'');
    $postdata=$this->postFields;
    $api_url=LOGIN_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    $resultArr=json_decode($result,true);
    if(!empty($resultArr)){
      $status=$resultArr['status'];
      if($status=='success'){
        if(array_key_exists('companyEmailIds', $resultArr['data'])){
          $email_address=$resultArr['data']['companyEmailIds']; 
        }else{
          $email_address=$resultArr['data']['email_address']; 
        }
        $resultArr['data']['userType']=$postFields['userType'];
        $this->setSessionParameters($resultArr['data']);
      }
      $res = array('status'=>'success','user'=>$resultArr);
    }
    return json_encode($res);
    die;
  }


  //Set Session for Logged User
  private function setSessionParameters($result){
    
    $_SESSION['userData']=$result;
    if(array_key_exists('PKCompanyRefNo', $result)){
      $_SESSION['id']=$result['PKCompanyRefNo'];  
    }
    if(array_key_exists('id', $result)){
      $_SESSION['id']=$result['id'];  
    }


    if(array_key_exists('companyName', $result)){
      $_SESSION['first_name']=$result['companyName']; 
    }
    if(array_key_exists('first_name', $result)){
      $_SESSION['first_name']=$result['first_name'].' '.$result['last_name']; 
    }
    $_SESSION['session_id']=session_id().$_SESSION['id'];
    $_SESSION['token']=TOKEN;
  }


  //Save Company Data
  public function saveCompanyData($companyData){
    $result=array();
    $conn= $this->dbConnect();
    $postdata=$this->postFields;

    $api_url=ADD_VENDOR_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;

    $postData=$postdata;
    if(!empty($postData)){
      if(array_key_exists('PKCompanyRefNo', $postData)){
        $query="UPDATE PREFIX.VENDOR SET `companyName` = '".$postdata['companyName']."',
        `comapnyAdress` = '".$postdata['comapnyAdress']."',
        `pincode` = '".$postdata['pincode']."',
        `mobile` = '".$postdata['mobile']."',
        `companyEmailIds` = '".$postdata['companyEmailIds']."',
        `compIsActive` = '".$postdata['compIsActive']."',
        `pasword` = '".md5($postdata['pasword'])."' WHERE ".PREFIX.VENDOR.".`PKCompanyRefNo` =".$postdata['PKCompanyRefNo']."";
              $res=mysqli_query($conn,$query);
              //$ress=mysqli_affected_rows();
              }else{
                if(array_key_exists('pasword',$postdata)){
                 	$query="INSERT INTO ".PREFIX.VENDOR." (`fname`,`lname`,`companyName` ,`comapnyAdress` ,`pincode` ,`mobile` ,`companyEmailIds`,`pasword`)VALUES ('".$postdata['fname']."','".$postdata['lname']."','".$postdata['companyName']."', '".$postdata['comapnyAdress']."','".$postdata['pincode']."','".$postdata['mobile']."','".$postdata['companyEmailIds']."','".md5($postdata['pasword'])."')";
                }else{
                $query="INSERT INTO ".PREFIX.VENDOR." (`fname`,`lname`,`companyName` ,`comapnyAdress` ,`pincode` ,`mobile` ,`companyEmailIds`)VALUES ('".$postdata['fname']."','".$postdata['lname']."','".$postdata['companyName']."', '".$postdata['comapnyAdress']."','".$postdata['pincode']."','".$postdata['mobile']."','".$postdata['companyEmailIds']."')";
                }
                $res=mysqli_query($conn,$query);
                $ress=mysqli_insert_id($conn);
              }
              $result=array('status'=>'success','last_id'=>$ress);
            }
            return json_encode($result);die;
            //$this->postFields=$postFields;
            //$result=$this->curlExec();
            //echo $resultArr=json_encode($result);die;
  }



  //Save Company Data
  public function saveSupplierData($supplierData){
    $result=array();
    $postdata=$this->postFields;
    $api_url=ADD_SUPPLIER_URL;
    if(!is_array($postdata)){
      $postFields=json_decode($postdata,true);
    }
    $this->api_url=$api_url;
    //$this->postFields=$postFields['data']['supplier'];
    $this->postFields=$postdata;
    $result=$this->curlExec();
    return $resultArr=$result; die;
  }


  //Get Supplier Data
  function getSupplierData(){
    $postdata=$this->postFields;
    $api_url=DETAILS_SUPPLIER_URL;
    $this->api_url=$api_url.'&id='.$postdata['id'].'&vendor_id='.$postdata['vendor_id'];
    $result=$this->curlGetExec();
    echo $resultArr=$result; die;
  }




  function getcompanyList(){
    $api_url=ALL_VENDOR_URL;
    $this->api_url=$api_url;
    $result=$this->curlGetExec();
    echo $resultArr=$result; die;
  }






  function getCompanyDetailsById($id){
    $api_url=DETAILS_VENDOR_URL;
    $this->api_url=$api_url.'&id='.$id;
    $result=$this->curlGetExec();
    echo $resultArr=$result; die;

  }



  function getSupplierList($id=null){
    $vendor_id=0;
    if($id!=''){
      $vendor_id=$id;
    }
    $api_url=ALL_SUPPLIER_URL;
    $this->api_url=$api_url.'&id='.$vendor_id;
    $result=$this->curlGetExec();
    echo $resultArr=$result; die;
  }


  /*Get All Master Unit List*/
  function getAllMasterList($type){
      $api_url=GET_ALL_MASTER_URL;
      //Here Type can be multiple data i.e type=unit&ip=172&checksum=
      $this->api_url=$api_url.'&type='.$type;
      $result=$this->curlGetExec();
      $res=json_decode($result);
      return $res;
    }



  /*Get All Master Unit List*/
  function getAllCityList($type,$state_id){
      $api_url=GET_ALL_CITY_URL;
      $this->api_url=$api_url.'&type='.$type.'&state_id='.$state_id;
      $result=$this->curlGetExec();
      $res=json_decode($result);
      return $res;
    }



  /* 
   * @Auhtor: Pradeep Kumar
   * @Description: Update Supplier Password Update
   * @return: Json List
   */
  public function saveSupplierPassword(){
    $result=array();
    $conn= $this->dbConnect();
    $postdata=$this->postFields;
    $api_url=UPDATE_SUPPLIER_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    echo $result;die;
  }




   /* 
   * @Auhtor: Pradeep Kumar
   * @Description: Update Vendor Password Update
   * @return: Json List
   */
  public function saveVendorPassword(){
    $result=array();
    $conn= $this->dbConnect();
    $postdata=$this->postFields;
    $api_url=UPDATE_VENDOR_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    echo $result;die;
  }



  /* 
   * @Auhtor: Pradeep Kumar
   * @Description: Update Vendor profile Update
   * @return: Json List
   */
  public function saveVendorProfile(){
    $result=array();
    $conn= $this->dbConnect();
    $postdata=$this->postFields;
    $api_url=UPDATE_VENDOR_PROFILE_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    echo $result;die;
  }






  /* 
   * @Auhtor: Pradeep Kumar
   * @Description: Get All Vendor Prodcut List
   * @return: Json List
   */
  function getAllVendorProductList($params){
      $urlStr='';
      $this->postFields=$params;
      $vendorId=$params['vendorId'];
      $api_url=ALL_PRODUCT_URL;
      $urlStr.=$api_url.'&vendor_id='.$vendorId;
       if(array_key_exists('start', $params)){
        $start=$params['start'];
        $urlStr.="&start=$start";
      }else{
        $urlStr.="&start=0";
      }

      if(array_key_exists('offset', $params)){
        $offset=$params['offset'];
        $urlStr.="&offset=$offset";
      }else{
        $urlStr.="&offset=0";
      }
      $this->api_url=$urlStr;
      $result=$this->curlGetExec();
      if(count(json_decode($result))){
        $res=array('status'=>'success','data'=>json_decode($result));
      }else{
        $res=array('status'=>"success","data"=>"null","message"=>"no record found");
      }
      echo json_encode($res); die;
    }





  /*Get Vendor Details By Id*/
  function getVendorDetails($id){
    if($id!=''){
      $api_url=DETAILS_VENDOR_URL;
      $this->api_url=$api_url.'&id='.$id;
      $result=$this->curlGetExec();
      $res=json_decode($result);
      return $res;

    }
  }





  /* 
   * @Auhtor: Pradeep Kumar
   * @Description: Update Vendor profile Update
   * @return: Json List
   */
  public function saveSupplierProfile(){
    $result=array();
    $conn= $this->dbConnect();
    $postdata=$this->postFields;
    $api_url=UPDATE_SUPPLIER_PROFILE_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    echo $result;die;
  }





  //Save Company Data
  public function saveClientData($supplierData){
    $result=array();
    $postdata=$this->postFields;
    $api_url=ADD_CLIENT_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    echo $resultArr=json_encode($result);die;
  }



  //Get Details of the Suplier
  function getSupplierDetailsById($id,$vendor_id){
    $vendor_id=0;
    if($vendor_id!=''){
      $vendor_id=$id;
    }

    if($id!=''){
      $id=$id;
    }

    $api_url=SUPPLIER_DETAILS_URL;
    $this->api_url=$api_url.'&id='.$id.'&vendor_id='.$vendor_id;
    $result=$this->curlGetExec();
    echo $resultArr=$result; die;
  }



  function getAllClientList($supplier_id=null,$vendor_id=null){
    if($supplier_id!=''){
      $supplier_id=$supplier_id;
    }

    if($vendor_id!=''){
      $vendor_id=$vendor_id;
    }
    $api_url=ALL_CLIENT_URL;
    $this->api_url=$api_url.'&vendor_id='.$vendor_id.'&supplier_id='.$supplier_id;
    $result=$this->curlGetExec();
    echo $resultArr=$result; die;

  }



  function getClientDetails($id,$supplier_id=null,$vendor_id=null){
    if($supplier_id!=''){
      $supplier_id=$supplier_id;
    }

    if($vendor_id!=''){
      $vendor_id=$vendor_id;
    }
    $api_url=DETAILS_CLIENT_URL;
    $this->api_url=$api_url.'&vendor_id='.$vendor_id.'&supplier_id='.$supplier_id.'&id='.$id;
    $result=$this->curlGetExec();
    echo $resultArr=$result; die;

  }


  //Add Product By Vendor
  //Created Date: 23-01-2018
  public function saveProductData(){
    $result=array();
    $postdata=$this->postFields;
    $api_url=ADD_PRODUCT_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    echo $result;die;
  }




  function getAllProductList($vendor_id=null){
    if($vendor_id!=''){
      $vendor_id=$vendor_id;
    }
    $this->postFields=$vendor_id;
    $api_url=ALL_PRODUCT_URL;
    $this->api_url=$api_url.'&vendor_id='.$vendor_id;
    $result=$this->curlGetExec();
    echo $resultArr=$result; die;

  }

  //Get Product Details by Id
  function getProductDetailsById(){
    $vendor_id=$this->postFields['vendor_id'];
    $id=$this->postFields['prod_id'];
    $api_url=GET_PRODUCT_URL;
    $this->api_url=$api_url.'&vendor_id='.$vendor_id.'&prod_id='.$id;
    $result=$this->curlGetExec();
    echo $resultArr=$result; die;

  }



  /*
   * @Author: Pradeep Kumar
   * @Description: To Create Order
   */
  public function createOrderData(){
    $result=array();
    $postdata=$this->postFields;
    $api_url=CREATE_ORDER_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    echo $result;die;
  }
  


  /*
   * @Author: Pradeep Kumar
   * @Description: To Create Order Payment
   */
  public function OrderPayment(){
    $result=array();
    $postdata=$this->postFields;
    $api_url=ORDER_PAYMENT_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    echo $result;die;
  }
  



  /*
   * @Author: Pradeep Kumar
   * @Description: To Create Order
   */
  public function updateOrderData(){
    $result=array();
    $postdata=$this->postFields;
    $api_url=UPDATE_ORDER_URL;
    $postFields=$postdata;
    $this->api_url=$api_url;
    $this->postFields=$postFields;
    $result=$this->curlExec();
    echo $result;die;
  }





  /*
   * @Author: Pradeep Kumar
   * @Description: To Create Order
   */
  public function getOrderListData(){
    $postFields=$this->postFields;
    $api_url=GET_ORDER_LIST_URL;
    $this->api_url=$api_url.'&'.http_build_query($postFields);
    $result=$this->curlGetExec();
    return $resultArr=$result; die;


  }


  



  function getApiInfo(){
    $api_url='http://localhost/api/v1/xml/search&?rooms=1&token=8d75e20b9e2e2c96dfc94a284fde249d&location_id=2&locality_id=&landmark_id=&checkin_date=2017-03-17&facility=1&checkout_date=2017-03-18&occupant_adult=1&occupant_child=0&distributor_id=22785&sendMultiRate=1&markupShow=1&nationality=India&sendRatePlan=1&access_type=agent_panel&access_user_type=subagent&access_user_id=22785&t=1489055701';
    //$api_url=$this->$api_url;
    //$request=$this->postFields;
    
    $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $api_url);
    curl_setopt($curl_handle, CURLOPT_ENCODING, "gzip");
        curl_setopt($curl_handle, CURLOPT_POST, true);
        if (get_magic_quotes_gpc() && isset($request)) {
            array_walk_recursive($request, 'self::removeSlashes');
        }
        //echo $api_url.'&'.http_build_query($request); die;
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($request));
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl_handle);
        $response='1001';
        $this->id='1111';
        $this->email_address='pradeep.kumar@traviate.com';
        //$this->id='';
        //$this->email_address='';
        $userInfo=array('id'=>$this->id,'email_address'=>$this->email_address,'data'=>$response);
        return $userInfo;
  }



  public function curlGetExec(){
    $request=array();
    $api_url=$this->api_url;
    $request=$this->postFields;
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $api_url);
    curl_setopt($curl_handle, CURLOPT_ENCODING, "gzip");
    curl_setopt($curl_handle, CURLOPT_HTTPGET, 1);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl_handle);
    return $response;
  }




  public function curlExec(){
    $request=array();
    $api_url=$this->api_url;
    $request=$this->postFields;
    $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $api_url);
        curl_setopt($curl_handle, CURLOPT_ENCODING, "gzip");
        curl_setopt($curl_handle, CURLOPT_POST, true);
        if (get_magic_quotes_gpc() && isset($request)) {
            array_walk_recursive($request, 'self::removeSlashes');
        }
        //echo $api_url.'&'.http_build_query($request); die;
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($request));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl_handle);
        return $response;
  }



  protected static function removeSlashes(&$value) {
        $value = stripslashes($value);
    }

    protected static function changeQuotes(&$value) {
        $value = stripslashes($value);
        $value = str_replace('"', '&quot;', $value);
    }

} 

?>