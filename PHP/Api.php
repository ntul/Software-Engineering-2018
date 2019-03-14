<?php 
 
 //getting the dboperation class
 require_once 'DbOperation.php';
 
 //function validating all the paramters are available
 //we will pass the required parameters to this function 
 function isTheseParametersAvailable($params){
 //assuming all parameters are available 
 $available = true; 
 $missingparams = ""; 
 
 foreach($params as $param){
 if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
 $available = false; 
 $missingparams = $missingparams . ", " . $param; 
 }
 }
 
 //if parameters are missing 
 if(!$available){
 $response = array(); 
 $response['error'] = true; 
 $response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';
 
 //displaying error
 echo json_encode($response);
 
 //stopping further execution
 die();
 }
 }
 
 //an array to display response
 $response = array();
 
 //if it is an api call 
 //that means a get parameter named api call is set in the URL 
 //and with this parameter we are concluding that it is an api call
 if(isset($_GET['apicall'])){
 
 switch($_GET['apicall']){
 
 //the CREATE operation
 //if the api call value is 'createuser'
 //we will create a record in the database
 case 'createUser':
 //first check the parameters required for this request are available or not 
 isTheseParametersAvailable(array('ID','Username','First_Name','Last_Name'));
 
 //creating a new dboperation object
 $db = new DbOperation();
 
 //creating a new record in the database
 $result = $db->createUser(
 $_POST['ID'],
 $_POST['Username'],
 $_POST['First_Name'],
 $_POST['Last_Name']
 );
 
 
 //if the record is created adding success to response
 if($result){
 //record is created means there is no error
 $response['error'] = false; 
 
 //in message we have a success message
 $response['message'] = 'User addedd successfully';
 
 //and we are getting all the users from the database in the response
 $response['users'] = $db->getUser();
 }else{
 
 //if record is not added that means there is an error 
 $response['error'] = true; 
 
 //and we have the error message
 $response['message'] = 'Some error occurred please try again';
 }
 
 break; 

 //the loginNewUser operation
 //if the api call value is 'loginNewUser'
 //we will create a record in the database
 case 'loginNewUser':
 //first check the parameters required for this request are available or not 
 isTheseParametersAvailable(array('Username','EmailID','Password'));
 
 //creating a new dboperation object
 $db = new DbOperation();
 
 //creating a new record in the database
 $result = $db->loginNewUser(
 $_POST['Username'],
 $_POST['EmailID'],
 $_POST['Password']
 );
 
 
 //if the record is created adding success to response
 if($result){
 //record is created means there is no error
 $response['error'] = false; 
 
 //in message we have a success message
 $response['message'] = 'User addedd successfully';
 

 }else{
 
 //if record is not added that means there is an error 
 $response['error'] = true; 
 
 //and we have the error message
 $response['message'] = 'Some error occurred please try again';
 }
 
 break; 
 
 //the login operation
 case 'loginUser':
 isTheseParametersAvailable(array('Username','Password'));
 $db = new DbOperation();
 $result = $db->loginUser(
 $_POST['Username']
 );
 

 if(password_verify($_POST['Password'], $result) && $_POST['Username']!='admin'){
 $response['error'] = false; 
 $response['message'] = 'Login successful';
 $response['transactions'] = $db->getTransactions($_POST['Username']);
 }
 elseif(password_verify($_POST['Password'], $result) && $_POST['Username']=='admin'){
 $response['error'] = false; 
 $response['message'] = 'Admin Login successful';
 $response['login'] = $db->getLogins();   
 $response['User_details'] = $db->getUsers(); 
 }
 else{
 $response['error'] = true; 
 $response['message'] = 'Wrong password';
 }
 break; 
 
 
 //the login operation
 case 'getTransactions':
 isTheseParametersAvailable(array('Username'));
 $db = new DbOperation();
 $result = $db->loginUser(
 $_POST['Username']
 );
 
 $response['error'] = false; 
 $response['message'] = 'Login successful';
 $response['transactions'] = $db->getTransactions($_POST['Username']);


 break; 
 
 
//the addTransaction operation
 //if the call is getuser
 case 'addTransaction':
isTheseParametersAvailable(array('Username', 'Password', 'Trans_Category', 'Trans_Desc', 'Amount', 'Trans_Date'));
 $db = new DbOperation();
$result = $db->loginUser(
 $_POST['Username']
 );
 
 if(password_verify($_POST['Password'],$result)){
 $response['error'] = false; 
 $response['message'] = 'Transaction added successfully';
 $db->addTransaction(
 $_POST['Username'], 
 $_POST['Trans_Category'],
 $_POST['Trans_Desc'],
 $_POST['Amount'],
 $_POST['Trans_Date']);
 $response['transactions'] = $db->getTransactions($_POST['Username']);
 }else{
 $response['error'] = true; 
 $response['message'] = 'WrongPassword';
 }
 break; 
 
 //the READ operation
 //if the call is getuser
 case 'getUser':
 $db = new DbOperation();
 $response['error'] = false; 
 $response['message'] = 'Request successfully completed';
 $response['users'] = $db->getUser();
 break; 
 

 //the admin UPDATE operation
 case 'adminUpdate':
 isTheseParametersAvailable(array('Table', 'Column', 'NewValue', 'Key', 'KeyValue'));
 $db = new DbOperation();
 $result = $db->adminUpdate(
 $_POST['Table'],
 $_POST['Column'],
 $_POST['NewValue'],
 $_POST['Key'],
 $_POST['KeyValue']
 );
 
 if($result){
 $response['error'] = false; 
 $response['message'] = 'Update successfull';
 }else{
 $response['error'] = true; 
 $response['message'] = 'Some error occurred please try again';
 }
 break; 
 
 
 //the UPDATE operation
 case 'updateUser':
 isTheseParametersAvailable(array('ID','Username','First_Name','Last_Name'));
 $db = new DbOperation();
 $result = $db->updateUser(
 $_POST['ID'],
 $_POST['Username'],
 $_POST['First_Name'],
 $_POST['Last_Name']
 );
 
 if($result){
 $response['error'] = false; 
 $response['message'] = 'User updated successfully';
 $response['users'] = $db->getUser();
 }else{
 $response['error'] = true; 
 $response['message'] = 'Some error occurred please try again';
 }
 break; 
 
 //the delete operation
 case 'deleteUser':
 
 //for the delete operation we are getting a GET parameter from the url having the id of the record to be deleted
 if(isset($_GET['id'])){
 $db = new DbOperation();
 if($db->deleteUser($_GET['id'])){
 $response['error'] = false; 
 $response['message'] = 'User deleted successfully';
 $response['users'] = $db->getUser();
 }else{
 $response['error'] = true; 
 $response['message'] = 'Some error occurred please try again';
 }
 }else{
 $response['error'] = true; 
 $response['message'] = 'Nothing to delete, provide an id please';
 }
 break; 
 }
 
 }else{
 //if it is not api call 
 //pushing appropriate values to response array 
 $response['error'] = true; 
 $response['message'] = 'Invalid API Call';
 }
 
 //displaying the response in json structure 
 echo json_encode($response);
