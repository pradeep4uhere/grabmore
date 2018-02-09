<?php class vendorService{
 
  //The MySQL DB class to use
  private $db = NULL;
  
  //The user's userinfo in an array
  public $userinfo = NULL;
  
  //a string holding the cookie prefix
  private $cookie_prefix = '';
  
  //an entry holding the DB wildcard
  private $table_wildcard = '';
  
  //user is logged into the system
  private $logged_in = false;
  
  //user privelige level
  private $user_level = 0;
  
  //logout hash - used to verify logout requests
  private $logout_hash = '';
  
  //array to hold all of the errors 
  private $errors = NULL;
  

  public $api_url = NULL;
  
  public $postFields = NULL;

 
  //The various user levels
  const LEVEL_PENDING     = 0; //User is still pending email confirmation
  const LEVEL_USER        = 1; //Standard user with normal privaleges
  const LEVEL_MODERATOR   = 2; //Special case users with higher privaleges


  /*
  -- ------------------------------------------------
  -- Construction Of this class, Check Initialize
  -- ------------------------------------------------
  */
  public function __construct($db){
    $this->db=&$db;
    $this->session_id=md5(session_id());
    $this->token=TOKEN;
    $this->api_url='';
    $this->postFields='';

  }



  /*
  -- ------------------------------------------------
  -- If Session set then return true, means logged 
  -- ------------------------------------------------
  */
  public static function isLogin(){
    if(isset($_SESSION['login_mode'])){
      return true;
    }else{
      return false;
    }
  }




  /*
  -- ------------------------------------------------
  -- Admin Login 
  -- ------------------------------------------------
  */
  function adminLogin(){
    $res = array('status'=>'error','user'=>'');
    $postdata=$this->postFields;
    $username=$postdata['username'];
    $password=md5($postdata['password']);
    $details=$this->getUserDetails($username,$password);
    if(!empty($details)){
    //$this->setSessionDetails($details[0]);
      $res=array('status'=>'success','user'=>$details[0]);
    }else{
      $res=array('status'=>'error','user'=>"");  
    }
    return $res;
  }

  
  /*
  -- ------------------------------------------------
  -- setSessionDetails  
  -- ------------------------------------------------
  */
  private function setSessionDetails($details){
    if(!empty($details)){
      $_SESSION['id']=$details['id'];
      $_SESSION['mobile']=$details['mobile'];
      $_SESSION['email_address']=$details['companyEmailIds'];
      $_SESSION['first_name']=$details['fname'];
      $_SESSION['last_name']=$details['lname'];
      $_SESSION['login_mode']='Vendor';
      $_SESSION['userType']='1';
      $_SESSION['role_id']='1';
    }
  }


  /*
  -- ------------------------------------------------
  -- getUserDetails  
  -- ------------------------------------------------
  */
  public function getUserDetails($username,$password){
    $result=array();
    // $arrayVariable["column name"] = formatted SQL value
    if($this->isValidEmail($username)){
      $values["companyEmailIds"] = MySQL::SQLValue($username);  
    }else if($this->isValidMobile($username)){
       $values["mobile"] = MySQL::SQLValue($username);   
    }else{
       $values["mobile"] = MySQL::SQLValue($username);   
    }
    $values["pasword"]  = MySQL::SQLValue($password);
    try {
        $this->db->SelectRows(VENDOR, $values);
        //echo MySQL::BuildSQLSelect(VENDOR, $values);
        $array=$this->db->RecordsArray(MYSQLI_ASSOC);
    }catch(Exception $e) {
        // If an error occurs, rollback and show the error
        $array=$e->getMessage();
    }
    return $array;
  } 



  /*
  -- -------------------------------------------------
  -- Validate Mobile Number and Email address present?    
  -- -------------------------------------------------
  */
  public function isPresentVendor($type,$value){
    if($type=='mobile'){
      $values["mobile"] = MySQL::SQLValue($value); 
    }
    if($type=='email'){
      $values["companyEmailIds"] = MySQL::SQLValue($value); 
    }
    $this->db->SelectRows(VENDOR, $values);
    if($this->db->RowCount()){
      return true;
    }else{
      return false;
    }
  }

  


  /*
  -- ------------------------------------------------
  -- Validate Mobile Number, Number should be 10 digit   
  -- ------------------------------------------------
  */
  private function isValidMobile($mobile){
    if($mobile!=''){
      if (!preg_match('/^[0-9]{10}+$/', $mobile)) {
         return false;
      }else{
        return true;
      }
    }

  }




  /*
  -- ------------------------------------------------
  -- Validate Email Address   
  -- ------------------------------------------------
  */
  private function isValidEmail($email){
    if($email!=''){
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         return false;
      }else{
        return true;
      }
    }

  }




  /*
  -- ------------------------------------------------
  -- Validate Url Address   
  -- ------------------------------------------------
  */
  private function isValidUrl($website){
    if($website!=''){
      if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
        return false;
      }else{
        return true;
      }
    }

  }






  // function getUserInfo(){
  //     $api_url='http://localhost/api/v1/xml/search&?rooms=1&token=8d75e20b9e2e2c96dfc94a284fde249d&location_id=2&locality_id=&landmark_id=&checkin_date=2017-03-17&facility=1&checkout_date=2017-03-18&occupant_adult=1&occupant_child=0&distributor_id=22785&sendMultiRate=1&markupShow=1&nationality=India&sendRatePlan=1&access_type=agent_panel&access_user_type=subagent&access_user_id=22785&t=1489055701';
  //     $request=array();
  //     $curl_handle = curl_init();
  //         curl_setopt($curl_handle, CURLOPT_URL, $api_url);
  //         curl_setopt($curl_handle, CURLOPT_ENCODING, "gzip");
  //         curl_setopt($curl_handle, CURLOPT_POST, true);
  //         if (get_magic_quotes_gpc() && isset($request)) {
  //             array_walk_recursive($request, 'self::removeSlashes');
  //         }
  //         //echo $api_url.'&'.http_build_query($request); die;
  //         curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($request));
  //         curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
  //         $response = curl_exec($curl_handle);
  //         $response='1001';
  //         $this->id='1111';
  //         $this->email_address='pradeep.kumar@traviate.com';
  //         //$this->id='';
  //         //$this->email_address='';
  //         $userInfo=array('id'=>$this->id,'email_address'=>$this->email_address,'data'=>$response);
  //         return $userInfo;
  //   }


    protected static function removeSlashes(&$value) {
        $value = stripslashes($value);
    }

    protected static function changeQuotes(&$value) {
        $value = stripslashes($value);
        $value = str_replace('"', '&quot;', $value);
    }

} 
?>
