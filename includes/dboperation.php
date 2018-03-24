<?php
  class dboperation
  {
     private $con;
	 function __construct()
	 {
	    require_once dirname(__FILE__).'/dbconnect.php';
	  $db=new dbconnnect();
	  $this->con=$db->connect();
	 }
	 function registerUser($name,$phone,$email,$pin)
	 {
		 if(!$this->isUserExist($email))
		 {
			 $pass=md5($pin);
			 $stmt=$this->con->prepare("INSERT INTO users(user_name,user_email,user_phone,user_pin)VALUES(?,?,?,?)");
			 $stmt->bind_param("ssss",$name,$phone,$email,$pass);
			 if($stmt->execute())
			return USER_CREATED;
		
		return USER_CREATION_FAILED;
		}
		return USER_EXIST;
		 }
	 }
	 function userLogin($phone,$pin)
	 {
		 $pass=md5($pin);
		 $stmt=$this->con->prepare("SELECT user_id FROM users WHERE user_phone=? AND user_pin=? ");
		 $stmt->bind_param("ss",$phone,$pass);
		 $stmt->execute();
		 $stmt->store_result();
		 return $stmt->num_rows>0;
		 
	 }
	 function isUserExist($email)
	 {
		 $stmt=$this->con->prepare("SELECT * FROM users WHERE user_email=?");
		 $stmt->bind_param("s",$email);
		 $stmt->execute();
		 $stmt->store_result();
		return $stmt->num_rows>0;
	 }
  
?>