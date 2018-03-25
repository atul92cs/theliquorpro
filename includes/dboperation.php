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
			/* $pass=md5($pin);*/
			 $stmt=$this->con->prepare("INSERT INTO users(user_name,user_email,user_phone,user_pin)VALUES(?,?,?,?)");
			 $stmt->bind_param("ssss",$name,$email,$phone,$pin);
			 if($stmt->execute())
			return USER_CREATED;
		
		return USER_CREATION_FAILED;
		}
		return USER_EXIST;
		 }
	 
	 function userLogin($phone,$pin)
	 {
		/* $pass=md5($pin);*/
		 $stmt=$this->con->prepare("SELECT user_id FROM users WHERE user_phone=? AND user_pin=?");
		 $stmt->bind_param("ss",$phone,$pin);
		 $stmt->execute();
		 $stmt->store_result();
		 return $stmt->num_rows>0;
		 
	 }
	 function isUserExist($email)
	 {
		 $stmt=$this->con->prepare("SELECT user_id FROM users WHERE user_email=?");
		 $stmt->bind_param("s",$email);
		 $stmt->execute();
		 $stmt->store_result();
		 return $stmt->num_rows>0;
	 }
	 function getUserbyphone($phone)
	 {
		 $stmt=$this->con->prepare("SELECT user_id,user_name,user_email FROM users WHERE user_phone=?");
		 $stmt->bind_param("s",$phone);
		 $stmt->execute();
		 $stmt->bind_result($id,$name,$email);
		 $user=array();
		 $stmt->fetch();
		 $user['Id']=$id;
		 $user['Name']=$name;
		 $user['Phone']=$phone;
		 return $user;
		 
	 }
	 function getProducts()
	 {
		 $stmt=$this->con->prepare("SELECT * FROM products");
		 $stmt->execute();
		 $stmt->bind_result($id,$name,$price,$pic,$quantity,$category);
		 $product=array();
		 while($stmt->fetch())
		 {
			 $temp=array();
			$temp['productId']=$id;
			$temp['productName']=$name;
			$temp['productImage']=$image;
			$temp['productPrice']=$price;
			$temp['productCategory']=$category;
			array_push($product,$temp);
		 }
		 return $product;
	 }
	 function getProductDetails($id)
	 {
		 $stmt=$this->con->prepare("SELECT * FROM products WHERE product_id=? ");
		 $stmt->bind_param("s",$id);
		 $stmt->execute();
		 $stmt->bind_result($id,$name,$price,$pic,$quantity,$category);
		 $product=array();
          while($stmt->fetch())
		 {
			 $temp=array();
			$temp['productId']=$id;
			$temp['productName']=$name;
			$temp['productImage']=$image;
			$temp['productPrice']=$price;
			$temp['productCategory']=$category;
			array_push($product,$temp);
		 }
		 return $product;
		 
		 
	 }
	 function getProductsbyCat($category)
	 {
		 $stmt=$this->con->prepare("SELECT product_id,product_name,product_price,product_pic,product_quantity FROM products WHERE product_category=?");
		 $stmt->bind_param("s",$category);
		 $stmt->execute();
		 $stmt->bind_result($id,$name,$price,$pic,$quantity);
		 $product=array();
		  while($stmt->fetch())
		 {
			 $temp=array();
			$temp['productId']=$id;
			$temp['productName']=$name;
			$temp['productImage']=$image;
			$temp['productPrice']=$price;
			
			array_push($product,$temp);
		 }
		 return $product;
	 }
	 function getSpecProduct($name)
	 {
		 $stmt=$this->con->prepare("SELECT product_id,product_price,product_quantity FROM products WHERE product_name=?");
		 $stmt->bind_param("s",$name);
		 $stmt->execute();
		 $stmt->bind_result($id,$price,$quantity);
		 $product=array();
		  while($stmt->fetch())
		 {
			 $temp=array();
			$temp['productId']=$id;
			$temp['productPrice']=$price;
			$temp['productQuantity']=$quantity;
			array_push($product,$temp);
		 }
		 return $product;
		 
	 }
  }
?>