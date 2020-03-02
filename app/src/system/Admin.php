<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9/25/2019
 * Time: 2:27 PM
 */
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use \PHPMailer\PHPMailer\PHPMailer;
class Admin extends System
{

    protected $secret;
    protected $status;


    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function __construct()
    {
        return parent::__construct();
    }

    public function Login(){

        try{

            $email = $this->getEmail();
            $pwd = $this->getPassword();

            $query="SELECT * FROM `admin` WHERE `email` = :ptname";
            $stmt = $this->pdo ->prepare($query);
            $stmt->execute(array(':ptname' =>$email));

            $row = $stmt-> fetch(PDO::FETCH_ASSOC);

            $hash = $row['password'];

            $options = array('cost' => 11);

            if (password_verify($pwd, $hash)) {

                if (password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {

                    $newHash = password_hash($pwd, PASSWORD_DEFAULT, $options);

                    $sql = "UPDATE `admin` SET `password` = '$newHash' WHERE `email`='$email'";
                    $db_insert = mysqli_query($this->con, $sql);
                }


                $paylod = [
                    'iat' => time(),
                    'iss' => $this->domain(),
                    'exp' => time() + (60*60*8),
                    'permissions' => $row['perm_lvl'],
                    'userId' => $row['id']
                ];
                $token = JWT::encode($paylod, SECRETE_KEY);
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message' => 'Login successful', 'token' => $token);

            }else{
                $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Invalid Login Credentials'));
                return $data;
            }
        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }
    
    public function CompanyPassword($co){
         $stmt = $this->pdo->prepare("SELECT * FROM `company_password` WHERE `company`='$co' AND `status`='1' ");

        if ($stmt->execute()) {
            $pwd = "";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pwd = $row['password'];
            }
            
            return $pwd;
        }else{
            return "";
        }
    }

     public function saveCompanyPass(){
        $options = array('cost' => 10);
        $pwd = password_hash($this->getPassword(), PASSWORD_DEFAULT, $options);
        $co = $this->getId();
        $stmt = $this->pdo->prepare("INSERT INTO `company_password`(`id`, `company`, `password`, `status`) VALUES ('','$co','$pwd','1')");

        if ($stmt->execute()) {
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Password Saved Successfully');
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Password saving failed")
                );
            }
    }
    
    public function companies(){
        $stmt = $this->pdo->prepare("SELECT * FROM `company` WHERE 1");

        if ($stmt->execute()) {
            $all = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $co = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'msisdn' => $row['msisdn'],
                    'logo' => $row['logo_thumbn'],
                    'status' => $row['status']
                );

                array_push($all, $co);
            }

            return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'company' => $all);
        }else{
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Retrieving companies failed")
            );
        }
    }

    public function saveMember(){

        try{

            $company = $this->getId();
            $name = $this->getName();
            $surname = $this->getLastName();
            $email = $this->getEmail();
            $msisdn = $this->getMsisdn();
            $pwd = $this->CompanyPassword($company);
            $member = $this->getMemberNum();
            $age = $this->getAge();
            $height = $this->getHeight();
            $weight = $this->getWeight();
            $bmi = $this->getBmi();
            
            $stmt = $this->pdo->prepare("INSERT INTO `users`(`id`, `member_number`, `name`, `surname`, `email`, `msisdn`, `password`, `age`, `weight`, `height`, `bmi`, `profile`, `points`, `company`, `country`, `city`, `date_created`, `status`) VALUES ('','$member','$name','$surname','$email','$msisdn','$pwd','$age','$weight','$height','$bmi','','','$company','','',now(),'0')");

            if ($stmt->execute()) {
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Member added Successfully');
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Member addition failed")
                );
            }

        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }
    
    public function saveClient(){
        $name = $this->getName();
        $email = $this->getEmail();
        $logo = $this->getThumbnail();
        $msisdn = $this->getMsisdn();
        $options = array('cost' => 10);
        $password = password_hash($this->getPassword(), PASSWORD_DEFAULT, $options);
        $stmt = $this->pdo->prepare("INSERT INTO `company`(`id`, `name`, `email`, `msisdn`, `password`, `logo_thumbn`, `status`, `date_added`) VALUES ('','$name','$email','$msisdn','$password','$logo','1',now())");

            if ($stmt->execute()) {
                
                //send email to email
                
                
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Client added Successfully');
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Client addition failed")
                );
            }
        
        
        
        
    }

    public function clientMembers(){
         $id = $this->getId();
         $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `company` = '$id'");

        $all = array();
        if ($stmt->execute()) {
    
            while($row = $stmt-> fetch(PDO::FETCH_ASSOC)){
                $user = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'surname' => $row['surname'],
                    'member_number' => $row['member_number'],
                    'email' => $row['email'],
                    'msisdn' => $row['msisdn'],
                    'age' => $row['age'],
                    'weight' => $row['weight'],
                    'height' => $row['height'],
                    'bmi' => $row['bmi'],
                    'gender' => $row['gender'],
                    'points' => $row['points'],
                    'date_created' => $row['date_created'],
                    'status' => $row['status']
                );
    
                array_push($all, $user);
            }
    
            return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'members'=> $all);
        }else{
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Retrieving companies failed")
            );
        }
        
        
    }
    
    public function memberDetails(){
        
        try{
            $member = $this->getId();
            $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `id` = '$member'");
            if ($stmt->execute()) {
                $temp = "";
                 while($row = $stmt-> fetch(PDO::FETCH_ASSOC)){
                     $temp = array(
                         'id' => $row['id'],
                         'name' => $row['name'], 
                         'surname' => $row['surname'],
                         'email' => $row['email'],
                         'member_number' => $row['member_number'],
                         'country' => $row['country'],
                         'weight' => $row['weight'],
                         'height' => $row['height'],
                         'city' => $row['city'],
                         'bmi' => $row['bmi'],
                         'profile' => $row['profile'],
                         'points' => $row['points'],
                         'msisdn' => $row['msisdn'],
                         'company' => $row['company'],
                         'age' => $row['age']
                         );
                     
                 }
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'details'=> $temp);
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Failed to retrieve list of wellness activities")
                );
            }

        }catch (\Exception $e){
            return array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
        }
        
    }
    
    public function memberUpdate(){
        try{

            $member = $this->getId();
            $name = $this->getName();
            $surname = $this->getLastName();
            $email = $this->getEmail();
            $msisdn = $this->getMsisdn();
            $member_no = $this->getMemberNum();
            $age = $this->getAge();
            $height = $this->getHeight();
            $weight = $this->getWeight();
            $bmi = $this->getBmi();
            
            $stmt = $this->pdo->prepare("UPDATE `users` SET `member_number`= '$member_no',`name`= '$name',`surname`='$surname',`email`='$email',`msisdn`= '$msisdn',`age`= '$age',`weight`= '$weight',`height`= '$height',`bmi`= '$bmi'  WHERE `id` = '$member'");

            if ($stmt->execute()) {
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Member Updated Successfully');
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Member Update failed")
                );
            }

        }catch (\Exception $e){
            return array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
        }
    }
      
    public function wellnessActivities(){
         try{
            $stmt = $this->pdo->prepare("SELECT * FROM `activitiz` WHERE 1");

            if ($stmt->execute()) {
                $act = array();
                 while($row = $stmt-> fetch(PDO::FETCH_ASSOC)){
                     $temp = array('id' => $row['id'], 'name' => $row['nmt'], 'details' => $row['dsc'], 'image' => $row['img'], 'points' => $row['pts'], 'post' => $row['post']);
                     array_push($act, $temp);
                 }
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'activities'=> $act);
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Failed to retrieve list of wellness activities")
                );
            }

        }catch (\Exception $e){
            return array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
        }
    }
      
    public function activityClasses(){
        try{
            $id = $this->getId();
            $stmt = $this->pdo->prepare("SELECT * FROM `classes` WHERE `type`='$id'");

            if ($stmt->execute()) {
                $act = array();
                
                 while($row = $stmt-> fetch(PDO::FETCH_ASSOC)){
                     $temp = array('id' => $row['id'], 'name' => $row['name'], 'company' =>  $this->Company($row['co']), 'location' => $row['location'], 'details' => $row['dsc'],'date_start'=> $row['dt_s'],'date_end'=> $row['dt_e'], 'image' => $row['thumbn'], 'post' => $row['actv']);
                     array_push($act, $temp);
                 }
                 
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'classes'=> $act);
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Failed to retrieve list of wellness classes")
                );
            }

        }catch (\Exception $e){
            return array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
        }
    }
      
    public function Company($id){
        try{
            $stmt = $this->pdo->prepare("SELECT * FROM `company` WHERE `id`='$id' ");
            
            if ($stmt->execute()) {
                $act = array();
                
                 while($row = $stmt-> fetch(PDO::FETCH_ASSOC)){
                     $temp = array('id' => $row['id'], 'name' => $row['name'], 'email' => $row['email'], 'msisdn' => $row['msisdn'],'image' => $row['logo_thumbn'], 'post' => $row['status']);
                     array_push($act, $temp);
                 }
                return $act;
            }else{
                return array();
            }

        }catch (\Exception $e){
            return array();
        }
    }
      
    public function Classes(){
        
        try{
            $id = $this->getId();
            $stmt = $this->pdo->prepare("SELECT * FROM `classes` WHERE 1");

            if ($stmt->execute()) {
                $act = array();
                
                 while($row = $stmt-> fetch(PDO::FETCH_ASSOC)){
                     $temp = array('id' => $row['id'], 'name' => $row['name'], 'company' => $this->Company($row['co']), 'location' => $row['location'], 'details' => $row['dsc'],'date_start'=> $row['dt_s'],'date_end'=> $row['dt_e'], 'image' => $row['thumbn'], 'post' => $row['actv']);
                     array_push($act, $temp);
                 }
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'classes'=> $act);
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Failed to retrieve list of wellness classes")
                );
            }

        }catch (\Exception $e){
            return array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
        }
        
        
        
    }
    
    public function ClassRegister(){
        try{
            $company = $this->getId();
            $class = $this->getClass_id();
            $session = $this->getSession_id();
            $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `company` = '$company' ");
            $all = array();
            if ($stmt->execute()) {
        
                while($row = $stmt-> fetch(PDO::FETCH_ASSOC)){
                    $user = array(
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'surname' => $row['surname'],
                        'member_number' => $row['member_number'],
                        'email' => $row['email'],
                        'msisdn' => $row['msisdn'],
                        'age' => $row['age'],
                        'weight' => $row['weight'],
                        'height' => $row['height'],
                        'bmi' => $row['bmi'],
                        'gender' => $row['gender'],
                        'points' => $row['points'],
                        'attend' =>$this->checkAttend($row['id'],$session),
                        'date_created' => $row['date_created'],
                        'status' => $row['status']
                    );
        
                    array_push($all, $user);
                }
                
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'members'=> $all);
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Failed to retrieve list of Mmebers for class")
                );
            }

        }catch (\Exception $e){
            return array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
        }
    }
      
    public function checkAttend($member, $session){
        
        try{
            $stmt = $this->pdo->prepare("SELECT `attend` FROM `attendance` WHERE `member`= '$member' AND `class_sess`= '$session' ");
           
            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($row){
                     return $row['attend']; 
                }else{
                    return false;
                }
               
            }else{
                return null;
            }

        }catch (\Exception $e){
            return null;
        }
    }
      
    public function Attendance(){
        $member = $this->getId();
        $class = $this->getClass_id();
        $session = $this->getSession_id();
        $attend = $this->getStatus();
        try{
            $attendance = $this->checkAttend($member, $session);
            if($attendance == false){
                
                $stmt = $this->pdo->prepare("INSERT INTO `attendance`(`id`, `member`, `classID`, `class_sess`, `attend`) VALUES ('','$member','$class','$session','$attend')");
                   
                if ($stmt->execute()) {
                    if($attend == '1'){
                        $this->addPoints(2);
                        $attd = true;
                    }else{
                        $attd = false;
                    }
                    return array('success' => true, 'attend' => $attd, 'statusCode' => SUCCESS_RESPONSE);
                }else{
                     return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Failed to save Session Attendance")
                    );
                }
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Member already saved attendance. please refresh and use the update feature")
                );
            }
           

        }catch (\Exception $e){
            return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Failed to retrieve list of Mmebers for class")
                );
        }
    }
    
    public function getActivityPoints($activity){
        try{
            
            $stmt = $this->pdo->prepare("SELECT `pts` FROM `activitiz` WHERE `id` = '$activity' ");
           
            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    return $row['pts']; 
            }else{
                return null;
            }

        }catch (\Exception $e){
            return null;
        }
    }
    
    public function addPoints($num){
        try{
            $member = $this->getId();
            $stmt = $this->pdo->prepare("SELECT `points` FROM `users` WHERE `id` = '$member' ");
            
            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $new = $row['points'] + $num;// can use activitiz table pts 
                 
                $stmt = $this->pdo->prepare("UPDATE `users` SET `points` = '$new' WHERE `id` = '$member' ");
                $all = array();
                if ($stmt->execute()) {
                    //log points for session
                    return true;
                }else{
                    return null;
                }

               
            }else{
                return null;
            }

        }catch (\Exception $e){
            return null;
        }
    }
      
      
      
      
      
      

}