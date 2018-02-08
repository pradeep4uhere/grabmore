<?php class apiService{

  public $url;

  public function __construct(){
    $this->session_id=md5(session_id());
    $this->totken='ASDSGFDKDJ565SKDNKIOERU2389423JB42378RY23B';
    $this->postFields=array();
    $this->email_address='';
    $this->id='';
    $this->api_url='';
    $this->postFields='';
    
  }

  function logout(){
    unset($_SESSION);
    unset($_SESSION['id']);
    header('location:login.php');
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
    if(!empty($resultArr[0])){
      $id=$resultArr[0]['id'];
      $email_address=$resultArr[0]['email_address'];
      $this->setSessionParameters($resultArr[0]);
      $res = array('status'=>'success','user'=>$resultArr[0]);
    }
    echo json_encode($res);
    die;
  }


  //Set Session for Logged User
  private function setSessionParameters($result){
    $_SESSION['id']=$result['id'];
    $_SESSION['userData']=$result;
    $_SESSION['session_id']=session_id().$result['id'];
    $_SESSION['token']=TOKEN;
  }



  function getApiInfo(){
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
