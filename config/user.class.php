<?php class userService{
 
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
    echo "<pre>";
    print_r($this->db);
    die;
  }








  function goToHome(){
    if($this->email_address!='' && $this->id!=''){
      return true;
      //header('location:index.php');
    }else{
      return false;
    }
  }

  function checkSession(){
    if(isset($_SESSION['id'])){
      return true;
    }else{
      return false;
      //header('location:login.php');
    }
  }



  function isLogin(){
    if($this->email_address!='' && $this->id!=''){
      return true;
    }else{
      return false;
    }
  }


  function getUserInfo(){
    $api_url='http://localhost/api/v1/xml/search&?rooms=1&token=8d75e20b9e2e2c96dfc94a284fde249d&location_id=2&locality_id=&landmark_id=&checkin_date=2017-03-17&facility=1&checkout_date=2017-03-18&occupant_adult=1&occupant_child=0&distributor_id=22785&sendMultiRate=1&markupShow=1&nationality=India&sendRatePlan=1&access_type=agent_panel&access_user_type=subagent&access_user_id=22785&t=1489055701';
    $request=array();
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


  protected static function removeSlashes(&$value) {
        $value = stripslashes($value);
    }

    protected static function changeQuotes(&$value) {
        $value = stripslashes($value);
        $value = str_replace('"', '&quot;', $value);
    }

} 
?>
