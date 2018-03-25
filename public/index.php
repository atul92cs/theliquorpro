<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../vendor/autoload.php';
require_once '../includes/dboperation.php';
$app= new \Slim\App(['settings'=>['displayErrorDetails'=>true]]);
$app->get('/test',function(Request $req,Response $res){
	    $result="it works.app is updated";
		$res->getBody()->write(json_encode(array($result)));
}); 
$app->post('/register',function(Request $req,Response $res){
	if(isTheseParametersAvailable(array('name','email','phone','pin')))
	{
		$requestedBody=$req->getParsedBody();
		$name=$requestedBody['name'];
		$email=$requestedBody['email'];
		$phone=$requestedBody['phone'];
		$pin=$requestedBody['pin'];
		$db=new dboperation();
		$responseData=array();
		$result=$db->registerUser($name,$phone,$email,$pin);
		if($result==USER_CREATED)
		{
			$responseData['error']=false;
			$responseData['Message']='User Registered Successfully';
		}
		else if($result==USER_CREATION_FAILED)
		{
			$responseData['error']=true;
			$responseData['Message']='Error: User Creation failed, please try again';
		}
		else if($result==USER_EXISTS)
		{
			$responseData['error']=true;
			$responseData['Message']='Error:user already exists';
		}
		$res->getBody()->write(json_encode($responseData));
	}
});
$app->post('/login',function(Request $req,Response $res){
	if(isTheseParametersAvailable(array('phone','pin')))
	{
		$requestedBody=$req->getParsedBody();
		$phone=$requestedBody['phone'];
		$pin=$requestedBody['pin'];
		$db=new dboperation();
		$responseData=array();
		$result=$db->userLogin($phone,$pin);
		
		 if($result==true)
		{
			$responseData['error']=false;
			$responseData['User']=$db->getUserbyphone($phone);
		}
		 else
		{
			 $responseData['error']=true;
			 $responseData['Message']='Error:Please try again';
			
		}
	  $res->getBody()->write(json_encode($responseData));	 
	}
	
});
$app->get('/products',function(Request $req,Response $res){
	 $db=new dboperation();
	 $products=$db->getProducts();
	 $res->getBody()->write(json_encode(array("Products"=>$products)));
	
});
function isTheseParametersAvailable($required_fields)
 {
	  $error=false;
	  $error_fields="";
	  $request_params=$_REQUEST;
	  foreach($required_fields as $field)
	  {
		  if(!isset($request_params[$field])||strlen(trim($request_params[$field]))<=0)
		  {
			  $error=true;
			  $error_fields=$field.',';
			  
		  }
	  }
	  if($error)
	  {
		  $response=array();
		  $response["error"]=true;
		  $response["message"]='Required Field(s)'.substr($error_fields,0,-2).'is missing or empty';
		  echo json_encode($response);
		  return false;
	  }
	  return true;
 }

$app->run();
