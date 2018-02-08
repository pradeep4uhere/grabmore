<?php class userService{
  public $email_address;
  public $id;

  public function __construct(){
    $this->session_id=md5(session_id());
    $this->totken='ASDSGFDKDJ565SKDNKIOERU2389423JB42378RY23B';
    $this->email_address='';
    $this->id='';
    //$this->id='1111';
        //$this->email_address='pradeep.kumar@traviate.com';
  }

  function goToHome(){
    if($this->email_address!='' && $this->id!=''){
      header('location:index.php');
    }else{
      return false;
    }
  }

  function checkSession(){
    if($this->isLogin()){
      return true;
    }else{
      header('location:login.php');
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

