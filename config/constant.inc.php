<?php session_start(); 
if($_SERVER['HTTP_HOST']=='localhost'){
	define('SERVER_URL','http://localhost/prs/');
}else{
	error_reporting(0);
	define('SERVER_URL','https://prssystem.000webhostapp.com/');
}
define('TOKEN','ASDSGFDKDJ565SKDNKIOERU2389423JB42378RY23B');

define('LOGIN_URL',SERVER_URL.'API/demo.php?action=login');
define('ADMIN_LOGIN_URL',SERVER_URL.'API/getvendordetails.json');
define('ADD_VENDOR_URL',SERVER_URL.'API/demo.php?action=addvendor');
define('ALL_VENDOR_URL',SERVER_URL.'API/demo.php?action=allvendor');
define('DETAILS_VENDOR_URL',SERVER_URL.'API/demo.php?action=getvendordetails');
define('UPDATE_VENDOR_URL',SERVER_URL.'API/demo.php?action=putvendordetails');
define('UPDATE_VENDOR_PROFILE_URL',SERVER_URL.'API/demo.php?action=putvendorprofiledetails');

define('ADD_SUPPLIER_URL',SERVER_URL.'API/demo.php?action=addsupplier');
define('ALL_SUPPLIER_URL',SERVER_URL.'API/demo.php?action=allsupplier');
define('UPDATE_PWD_SUPPLIER_URL',SERVER_URL.'API/demo.php?action=updatepassword');
define('DETAILS_SUPPLIER_URL',SERVER_URL.'API/demo.php?action=supplierdetails');
define('UPDATE_SUPPLIER_PROFILE_URL',SERVER_URL.'API/demo.php?action=putsupplierprofiledetails');
define('UPDATE_SUPPLIER_URL',SERVER_URL.'API/demo.php?action=putsupplierdetails');


define('ADD_CLIENT_URL',SERVER_URL.'API/demo.php?action=addclient');
define('ALL_CLIENT_URL',SERVER_URL.'API/demo.php?action=allclient');
define('SUPPLIER_DETAILS_URL',SERVER_URL.'API/demo.php?action=supplierdetails');
define('DETAILS_CLIENT_URL',SERVER_URL.'API/demo.php?action=getclientdetails');


define('ADD_PRODUCT_URL',SERVER_URL.'API/demo.php?action=addproduct');
define('ALL_PRODUCT_URL',SERVER_URL.'API/demo.php?action=allproduct');
define('GET_PRODUCT_URL',SERVER_URL.'API/demo.php?action=getproduct');




define('GET_ALL_MASTER_URL',SERVER_URL.'API/demo.php?action=getalmasterlist');
define('GET_ALL_CITY_URL',SERVER_URL.'API/demo.php?action=getcitylist');


define('CREATE_ORDER_URL',SERVER_URL.'API/demo.php?action=createorder');
define('GET_ORDER_LIST_URL',SERVER_URL.'API/demo.php?action=getorderlist');
define('UPDATE_ORDER_URL',SERVER_URL.'API/demo.php?action=updateorder');
define('ORDER_PAYMENT_URL',SERVER_URL.'API/demo.php?action=orderpayment');


if($_SERVER['HTTP_HOST']=='localhost'){
	define('DB_HOST','localhost');
	define('DB_USER','root');
	define('DB_PASS','connect123');
	define('DB_DATABASE','prssystem');

}else{

	define('DB_HOST','localhost');
	define('DB_USER','id1161287_prssystem');
	define('DB_PASS','id1161287_prssystem');
	define('DB_DATABASE','id1161287_prssystem');
}

/**Database Table Name**/
define('PREFIX','');
define('ADMIN','admin');
define('VENDOR','generatingcompany');
define('SUPPLIER','supplier');
define('PRODUCT','product');
define('MEASUREMENT_UNIT','master_measurement_unit');
define('MASTER_CATEGORY','master_service_category');
define('MASTER_STATE','states');
define('MASTER_CITY','cities');
define('ORDER_BY','product_order');
define('PAYMENT','order_payment');



 /*
  -- ------------------------------------------------
  -- Salt, this is private key which used by third
  -- Party
  -- ------------------------------------------------
  */
  define('SALT','HRFSCHGGFD');
  define('CHECKSUM_VALIDATE',true);


?>
