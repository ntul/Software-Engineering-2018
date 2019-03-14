<?php
 
class DbOperation
{
    //Database connection link
    private $con;
 
    //Class constructor
    function __construct()
    {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/DbConnect.php';
 
        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();
 
        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }
    
/*
 * The new user login operation
 */
 function loginNewUser($Username, $EmailID, $Password){
 $hashedPassword = password_hash($Password, PASSWORD_BCRYPT);
 $stmt = $this->con->prepare("INSERT INTO login (Username, EmailID, Password) VALUES (?, ?, ?)");
 $stmt->bind_param("sss",$Username, $EmailID, $hashedPassword);
 if($stmt->execute())
 return true; 
 return false; 
 }
 
 /*
 * The login operation
 */
 function loginUser($Username){
 $stmt = $this->con->prepare("SELECT Password FROM login WHERE Username = ?");
 $stmt->bind_param('s',$Username);
 $stmt->execute();
 $stmt->bind_result($Password);
 $stmt->fetch();
 return $Password; 
 
 }
 
  /*
 * The getLogins operation
 */
 function getLogins(){
 $stmt = $this->con->prepare("SELECT * FROM login");
 $stmt->execute();
 $result = $stmt->get_result();
 $rows = [];

 while ($row = $result->fetch_assoc()) {
        $rows [] = $row;
 }

 return $rows; 
 }
 
  /*
 * The getUsers operation
 */
 function getUsers(){
 $stmt = $this->con->prepare("SELECT * FROM User_details");
 $stmt->execute();
 $result = $stmt->get_result();
 $rows = [];

 while ($row = $result->fetch_assoc()) {
        $rows [] = $row;
 }

 return $rows; 
 }
 
 /*
 * The read operation
 * When this method is called it is returning all the existing record of the database
 */
 function getTransactions($Username){
 $stmt = $this->con->prepare("SELECT ID FROM User_details WHERE Username = ?");
 $stmt->bind_param('s',$Username);
 $stmt->execute();   
 $stmt->bind_result($Id);    
 $stmt->fetch();
 
$stmt->close();


 $stmt = $this->con->prepare("SELECT * FROM Trans_Details WHERE ID = ? ORDER BY Trans_date ASC");
 $stmt->bind_param('s',$Id);
 $stmt->execute();
 $stmt->bind_result($TransactionID, $ID, $Trans_Type, $Trans_Category, $Trans_Desc, $Amount, $Trans_Date);



 $transactions = array(); 
 
 while($stmt->fetch()){
 $transaction  = array();
 $transaction['TransactionID'] = $TransactionID; 
 $transaction['ID'] = $ID; 
 $transaction['Trans_Type'] = $Trans_Type; 
 $transaction['Trans_Desc'] = $Trans_Desc; 
 $transaction['Amount'] = $Amount; 
 $transaction['Trans_Date'] = $Trans_Date; 
 
 array_push($transactions, $transaction); 
 }
 
 return $transactions; 
 }
 
 /*
 * The add Transaction
 * When this method is called it is returning all the existing record of the database
 */
 function addTransaction($Username, $Trans_Category, $Trans_Desc, $Amount, $Trans_Date){
 $stmt = $this->con->prepare("SELECT ID FROM User_details WHERE Username = ?");
 $stmt->bind_param('s',$Username);
 $stmt->execute();   
 $stmt->bind_result($Id);    
 $stmt->fetch();
 
 $stmt->close();


 $Trans_Type="CASH";
 $stmt = $this->con->prepare("INSERT INTO Trans_Details (ID, Trans_Type, Trans_Category, Trans_Desc, Amount, Trans_Date) VALUES (?,?,?,?,?,?) ");
 $stmt->bind_param('isssis',$Id, $Trans_Type, $Trans_Category, $Trans_Desc, $Amount, $Trans_Date);
 $stmt->execute();
return true;
 }
 
 /*
 * The create operation
 * When this method is called a new record is created in the database
 */
 function createUser($ID, $Username, $First_Name, $Last_Name){
 $stmt = $this->con->prepare("INSERT INTO Users (ID, Username, First_Name, Last_Name) VALUES (?, ?, ?, ?)");
 $stmt->bind_param("ssss", $ID, $Username, $First_Name, $Last_Name);
 if($stmt->execute())
 return true; 
 return false; 
 }
 
 /*
 * The read operation
 * When this method is called it is returning all the existing record of the database
 */
 function getUser(){
 $stmt = $this->con->prepare("SELECT ID, Username, First_Name, Last_Name FROM Users");
 $stmt->execute();
 $stmt->bind_result($ID, $Username, $First_Name, $Last_Name);
 
 $users = array(); 
 
 while($stmt->fetch()){
 $user  = array();
 $user['ID'] = $ID; 
 $user['Username'] = $Username; 
 $user['First_Name'] = $First_Name; 
 $user['Last_Name'] = $Last_Name; 
 
 array_push($users, $user); 
 }
 
 return $users; 
 }
 
 /*
 * The admin update operation
 * When this method is called the record with the given id is updated with the new given values
 */
 function adminUpdate($Table, $Column, $NewValue, $Key, $KeyValue){
 $stmt = $this->con->query("UPDATE $Table SET $Column = '$NewValue' WHERE $Key = '$KeyValue'");
 //$stmt->bind_param('sssss',$Table, $Column, $NewValue, $Key, $KeyValue);
 if($stmt)
 return true;
 return false; 
 }
 
 /*
 * The update operation
 * When this method is called the record with the given id is updated with the new given values
 */
 function updateUser($ID, $Username, $First_Name, $Last_Name){
 $stmt = $this->con->prepare("UPDATE Users SET Username = ?, First_Name = ?, Last_Name = ? WHERE ID = ?");
 $stmt->bind_param('ssss',$Username, $First_Name, $Last_Name, $ID);
 if($stmt->execute())
 return true; 
 return false; 
 }
 
 
 /*
 * The delete operation
 * When this method is called record is deleted for the given id 
 */
 function deleteUser($ID){
 $stmt = $this->con->prepare("DELETE FROM Users WHERE ID = ? ");
 $stmt->bind_param("s", $ID);
 if($stmt->execute())
 return true; 
 
 return false; 
 }
}