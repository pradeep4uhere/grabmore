<?php class Auth{

  //The id of the user.
  public static $id='';
  
  //The first Name of the user.
  public $first_name;

  //The last Name of the user.
  public $last_name;

  //The mobile number of the user.
  public $mobile;

  //The email address of the user.
  public $email_address;

  //The login mode either admin/vendor/user.
  public $login_mode;

  //The user type  admin/vendor/user.
  public $user_type;
 
  /*
  -- ------------------------------------------------
  -- Construction Of this class, Check Initialize
  -- ------------------------------------------------
  */
  public function __construct(){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
  }


  /*
  -- ------------------------------------------------
  -- Set All the Session here
  -- ------------------------------------------------
  */
  public function setAuthValues($sessionArr){
    if($sessionArr['Auth']['userType']==1){
      $_SESSION['id']=$sessionArr['Auth']['user']['PKCompanyRefNo'];
      $_SESSION['first_name']=$sessionArr['Auth']['user']['fname'];
      $_SESSION['last_name']=$sessionArr['Auth']['user']['lname'];
      $_SESSION['mobile']=$sessionArr['Auth']['user']['mobile'];
      $_SESSION['email_address']=$sessionArr['Auth']['user']['companyEmailIds'];
      $_SESSION['login_mode']=$sessionArr['Auth']['login_mode'];
      $_SESSION['user_type']=$sessionArr['Auth']['user_type'];
    }
  }


  public static function getID(){
    return $_SESSION['id'];
  }

  public static function getFirstName(){
    return $_SESSION['first_name'];
  }

  public static function getLastName(){
    return $_SESSION['last_name'];
  }

  public static function getMobile(){
    return $_SESSION['mobile'];
  }

  public static function getEmailAddress(){
    return $_SESSION['email_address'];
  }

  public static function getLoginMode(){
    return $_SESSION['login_mode'];
  }


  public static function getUserType(){
    return $_SESSION['user_type'];
  }

  public static function isLogin(){
    if(isset($_SESSION['id'])){
      return true;
    }else{
      return false;
    }
  }

} 
?>
