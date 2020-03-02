<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9/25/2019
 * Time: 2:16 PM
 *
*/
include_once dirname(__FILE__) . '/constants.php';
include_once dirname(__FILE__) . '/vendor/autoload.php';
include_once dirname(__FILE__) . '/Database.php';
use Firebase\JWT\JWT;
use \PHPMailer\PHPMailer\PHPMailer;

class System
{
    protected $pdo;
    protected $con;
    protected $email;
    protected $password;
    protected $newPassword;
    protected $confirmPassword;
    protected $age;
    protected $height;
    protected $weight;
    protected $bmi;
    protected $pin;
    protected $newPin;
    protected $name;
    protected $msisdn;
    protected $permission;
    protected $lastName;
    protected $mobile;
    protected $address;
    protected $town;
    protected $token;
    protected $category;
    protected $desc;
    protected $price;
    protected $nationID;
    protected $dob;
    protected $gender;
    protected $memberNum;
    protected $id;
    protected $amount;
    protected $maritalStatus;
    protected $package;
    protected $method;
    protected $starting;
    protected $ending;
    protected $confirmation;
    protected $start;
    protected $end;
    protected $bill;
    protected $dept;
    protected $mail;
    protected $country;
    protected $city;
    protected $thumbnail;
    protected $class_id;
    protected $session_id;
    protected $status;
    
    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }
    
    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }
    
    /**
     * @return mixed
     */
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }
    
    /**
     * @param mixed $confirmPassword
     */
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;
    }
    
    
    /**
     * @return mixed
     */
    public function getClass_id()
    {
        return $this->class_id;
    }
    
    /**
     * @param mixed $class_id
     */
    public function setClass_id($class_id)
    {
        $this->class_id = $class_id;
    }
    
    /**
     * @return mixed
     */
    public function getSession_id()
    {
        return $this->session_id;
    }
    
    /**
     * @param mixed $session_id
     */
    public function setSession_id($session_id)
    {
        $this->session_id = $session_id;
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
    

    /**
     * @return mixed
     */
    public function getDept()
    {
        return $this->dept;
    }
    
    
    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }
    
    /**
     * @param mixed $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }


    /**
     * @param mixed $dept
     */
    public function setDept($dept)
    {
        $this->dept = $dept;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }


    /**
     * @return mixed
     */
    public function getBmi()
    {
        return $this->bmi;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @param mixed $bmi
     */
    public function setBmi($bmi)
    {
        $this->bmi = $bmi;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }
    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @param mixed $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return mixed
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return mixed
     */
    public function getEnding()
    {
        return $this->ending;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * @param mixed $maritalStatus
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;
    }

    /**
     * @return mixed
     */
    public function getMemberNum()
    {
        return $this->memberNum;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @return mixed
     */
    public function getMsisdn()
    {
        return $this->msisdn;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getNationID()
    {
        return $this->nationID;
    }

    /**
     * @return mixed
     */
    public function getNewPin()
    {
        return $this->newPin;
    }

    /**
     * @return mixed
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return mixed
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return mixed
     */
    public function getStarting()
    {
        return $this->starting;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param mixed $bill
     */
    public function setBill($bill)
    {
        $this->bill = $bill;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @param mixed $confirmation
     */
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;
    }

    /**
     * @param mixed $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * @param mixed $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @param mixed $ending
     */
    public function setEnding($ending)
    {
        $this->ending = $ending;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @param mixed $memberNum
     */
    public function setMemberNum($memberNum)
    {
        $this->memberNum = $memberNum;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @param mixed $msisdn
     */
    public function setMsisdn($msisdn)
    {
        $this->msisdn = $msisdn;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $nationID
     */
    public function setNationID($nationID)
    {
        $this->nationID = $nationID;
    }

    /**
     * @param mixed $newPin
     */
    public function setNewPin($newPin)
    {
        $this->newPin = $newPin;
    }

    /**
     * @param mixed $package
     */
    public function setPackage($package)
    {
        $this->package = $package;
    }

    /**
     * @param mixed $pin
     */
    public function setPin($pin)
    {
        $this->pin = $pin;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @param mixed $starting
     */
    public function setStarting($starting)
    {
        $this->starting = $starting;
    }

    /**
     * @param mixed $town
     */
    public function setTown($town)
    {
        $this->town = $town;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->SMTPAuth = true;
        $this->mail->Username = "admin@velocityhealth.co.za";
        $this->mail->Password = "VAdmin@9087";
        $this->mail->SMTPSecure = "TLS"; //ssl
        $this->mail->Port = 587; //465

        $db = new Database();
        $this->pdo = $db->PDO();
        $this->con = $db->mysqli();
    }

    public function domain(){
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $uri = 'https://';
        } else {
            $uri = 'http://';
        }

        $uri .= $_SERVER['HTTP_HOST'];
        return $uri;
    }

    public function escape_data ($data) {

        if (function_exists('mysqli_real_escape_string')) {
            $data = mysqli_real_escape_string ($this->con, trim($data));
            $data = strip_tags($data);
        } else {
            $data = mysqli_escape_string ($this->con, trim($data));
            $data = strip_tags($data);
        }
        return $data;

    }

    public function newsletterSubscribe(){
        try{

            $email = $this->getEmail();
            $name = $this->getName();

            $query="INSERT INTO `newsletter_emails`(`id`, `email`, `name`, `status`) VALUES ('','$email','$name','1')";
            $stmt = $this->pdo ->prepare($query);
            if($stmt->execute()){
                return  array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message' => 'Email saved');
            }else{
                return array('success' => false, 'statusCode' => 500, 'error'=> array('type' => "NEWSLETTER_ERROR", 'message' => 'Email subscription failed'));
            
            }
            
        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
        
    }

    public function validateParameter($fieldName, $value, $dataType, $required = true){

        $data = "";

        if ($required == true) {

            if (empty($value) == true || $value == "") {
                $data = array('message' => $fieldName . ' cannot be empty');
            }

        }

        switch ($dataType) {
            case BOOLEAN:
                if (!is_bool($value)) {
                    $data .= array('message' => $fieldName . ' should be boolean');
                }
                break;

            case INTEGER:
                if (!is_numeric($value)) {
                    $data = array('message' => $fieldName . ' should be integer');
                }
                break;
            case 'pin':
                if (!is_string($value)) {
                    $data = array('message' => $fieldName . ' should be string');

                }
                if (!preg_match('%^[0-9]\S{4,}$%', stripslashes(trim($value)))) {
                    $data = array('message' => 'Pin should be atleast 4 numbers only');

                }
                break;
            case "mobile":
                //validate mobile number here and add 00263
                if (!is_string($value)) {
                    $data = array('message' => $fieldName . ' should be valid mobile number');

                }
                break;
            case "email":
                if (!preg_match ('%^[A-Za-z0-9._\%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$%', stripslashes(trim($value)))) {
                    $data = array('message' => $fieldName . ' is not a valid email');
                }
                break;
            case STRING:
                if (!is_string($value)) {
                    $data = array('message' => $fieldName . ' should be string');

                }
                break;

            default:
                $data = array('message' => 'Datatype not valid');

                break;

        }

        if ($data == ""){
            return array('success' => true, 'data' => $this->escape_data($value));
        }else{
            return array('success' => false, 'statusCode' => FORBIDEN, 'error'=> array('type' => "PARAMETER_ERROR", 'message' => $data['message']));
        }

    }

    public function createString($len){
        $string = "1qay2wsx3edc4rfv5tgb6zhn7ujm8ik9ollpAQWSXEDCVFRTGBNHYZUJMKILOP";
        return substr(str_shuffle($string), 0, $len);
    }

    public function CompanyLogin(){

        try{

            $email = $this->getEmail();
            $pwd = $this->getPassword();

            $query="SELECT * FROM `company` WHERE `email` = :ptname";
            $stmt = $this->pdo ->prepare($query);
            $stmt->execute(array(':ptname' =>$email));

            $row = $stmt-> fetch(PDO::FETCH_ASSOC);

            $hash = $row['password'];

            $options = array('cost' => 11);

            if (password_verify($pwd, $hash)) {

                if (password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {

                    $newHash = password_hash($pwd, PASSWORD_DEFAULT, $options);

                    $sql = "UPDATE `company` SET `password` = '$newHash' WHERE email='$email'";
                    $db_insert = mysqli_query($this->con, $sql);
                }


                $paylod = [
                    'iat' => time(),
                    'iss' => $this->domain(),
                    'exp' => time() + (60*60*8),
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
    
    public function CompanyUpdatePassword(){

        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;
            $pwd = $this->getPassword();
            $new  = $this->getNewPassword();

            $query="SELECT * FROM `company` WHERE `id` = :ptname";
            $stmt = $this->pdo ->prepare($query);
            $stmt->execute(array(':ptname' =>$id));

            $row = $stmt-> fetch(PDO::FETCH_ASSOC);

            $hash = $row['password'];

            $options = array('cost' => 10);

            if (password_verify($pwd, $hash)) {
                
                $newHash = password_hash($new, PASSWORD_DEFAULT, $options);

                $sql = "UPDATE `company` SET `password` = '$newHash' WHERE `id`='$id'";
                $db_insert = mysqli_query($this->con, $sql);
                if($db_insert){
                    // delete the cookie saved and prompt relogin
                     return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message' => 'Password Updated successfully');
                }else{
                    $data = array('success' => false, 'statusCode' => NOT_MODIFIED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'New Password Could not be saved'));
                    return $data;
                }
               

            }else{
                $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Invalid Old Password'));
                return $data;
            }
        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

    public function memberLogin(){
        try{

            $email = $this->getEmail();
            $pwd = $this->getPassword();

            $query="SELECT * FROM `users` WHERE `email` = :ptname";
            $stmt = $this->pdo ->prepare($query);
            $stmt->execute(array(':ptname' =>$email));

            $row = $stmt-> fetch(PDO::FETCH_ASSOC);

            $hash = $row['password'];

            $options = array('cost' => 11);
            
            if(empty($hash) || $hash = ""){
                
            }else{
                
                if (password_verify($pwd, $hash)) {

                    if (password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {
    
                        $newHash = password_hash($pwd, PASSWORD_DEFAULT, $options);
    
                        $sql = "UPDATE `users` SET `password` = '$newHash' WHERE email='$email'";
    
                    }
    
                    $db_insert = mysqli_query($this->con, $sql);
                    $paylod = [
                        'iat' => time(),
                        'iss' => $this->domain(),
                        'exp' => time() + (60*60*8),
                        'userId' => $row['id']
                    ];
                    $token = JWT::encode($paylod, SECRETE_KEY);
                    return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message' => 'Login successful', 'token' => $token);
    
                }else{
                    $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Invalid Login Credentials'));
                    return $data;
                }
            }

        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

    public function saveMember(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $company = $payload->userId;
            $name = $this->getName();
            $surname = $this->getLastName();
            $email = $this->getEmail();
            $msisdn = $this->getMsisdn();
            $pwd = $this->getPassword();
            $country = $this->getCountry();
            $city = $this->getCity();
            $gender = $this->getGender();
            $stmt = $this->pdo->prepare("INSERT INTO `users`(`id`, `name`, `surname`, `email`, `msisdn`, `password`, `profile`, `points`,`gender`, `company`, `country`, `city`, `date_created`, `status`) VALUES ('','$name','$surname','$email','$msisdn','$pwd','','0', '$gender','$company','$country','$city',now(),'0')");

            if ($stmt->execute()) {
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Employee added Successfully');
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Employee addition failed")
                );
            }

        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

    public function saveMembers(){
        //fine tune this one
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $company = $payload->userId;
            $name = $this->getName();
            $surname = $this->getLastName();
            $email = $this->getEmail();
            $msisdn = $this->getMsisdn();
            $pwd = $this->getPassword();
            $country = $this->getCountry();
            $city = $this->getCity();
            $gender = $this->getGender();
            $stmt = $this->pdo->prepare("INSERT INTO `users`(`id`, `name`, `surname`, `email`, `msisdn`, `password`, `profile`, `points`,`gender`, `company`, `country`, `city`, `date_created`, `status`) VALUES ('','$name','$surname','$email','$msisdn','$pwd','','0', '$gender','$company','$country','$city',now(),'0')");

            if ($stmt->execute()) {
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Employee added Successfully');
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Employee addition failed")
                );
            }

        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

    public function allMembers(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;
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
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Retrieving members failed")
                );
            }
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function memberLeaderboard(){
        try{
            
        $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;
        $stmt = $this->pdo->prepare("SELECT `name`, `surname`, `points` FROM `users` WHERE `company` = '$id' ORDER BY `points` DESC LIMIT 0,5");
            $all = array();
            if ($stmt->execute()) {

                while($row = $stmt-> fetch(PDO::FETCH_ASSOC)){
                    $user = array(
                        'name' => $row['name'],
                        'surname' => $row['surname'],
                        'points' => $row['points']
                    );
                    array_push($all, $user);
                }
            }
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'leaderboard'=> $all);
        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function memberUpdate(){

    }

    public function addCompany(){

    }

    public function companyUpdate(){

    }

    public function companyDetails(){

    }

    public function CompanyPassword($id){
        return "";
    }

    public function getAttendance($session){

        $sql = "SELECT * FROM `attendance` WHERE `class_sess`='$session' AND `attend`= '1' ";
        $qry =  mysqli_query($this->con, $sql);
        $count =  mysqli_num_rows($qry);
        if($count != 0 && $count > 0){
            return $count;
        }else{
            return 0;
        }
    }

    public function getSession($dt, $class){

        $sess = "SELECT * FROM `class_sessions` WHERE `classID`='$class' AND `dt`='$dt' ";
        $ssesql =mysqli_query($this->con, $sess);

        $sessrr = mysqli_fetch_assoc($ssesql);
        if (mysqli_num_rows($ssesql) == 1){
            return $sessrr['id'];
        }else{
            return 0;
        }

    }

    public function co($co){

        $sql = "SELECT * FROM `company` WHERE `email`='$co' ";
        $qry = mysqli_query($this->con, $sql);
        if(mysqli_num_rows($qry) == 1){
            return mysqli_fetch_assoc($qry);
        }else{
            return false;
        }
    }

    public function getBarData(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $co = $payload->userId;
            $results = "";
            $at = array();
            $lb = array();
            $className = "";
            $activity = $this->getId();

            $sql = "SELECT * FROM `classes` WHERE `co`='$co' AND `type`='$activity' ORDER BY `id` ASC LIMIT 0,1";
            $qry = mysqli_query($this->con, $sql);
            if (mysqli_num_rows($qry) != 0) {
                while ($rs = mysqli_fetch_assoc($qry)) {
                    $class = $rs['id'];
                    $className = $rs['name'];
                    $dt = date("Y-m-d");
                    $day = date('Y-m-d', strtotime('+13 day', strtotime($dt)));
                    $slq = "SELECT * FROM `class_sessions` WHERE `classID`='$class' AND `dt`<= '$day' ORDER BY `dt` ASC LIMIT 0,6";
                    $slqr = mysqli_query($this->con, $slq);
                    if (mysqli_num_rows($slqr) > 0) {

                        while ($rss = mysqli_fetch_assoc($slqr)) {
                            $dt = $rss['dt'];

                            $session = $this->getSession($dt, $class);
                            $attend =  $this->getAttendance($session);
                            array_push($at, $attend);
                            array_push($lb, $dt);


                        }
                    }
                }
                $asql = "SELECT * FROM `activitiz` WHERE `id`='$activity'";
                $aqry = mysqli_query($this->con, $asql);
                $ars = mysqli_fetch_assoc($aqry);
                $actName = $ars['nmt'];
                $results = array(
                    'sessions'=> $lb,
                    'attend' => $at,
                    'name' => $className,
                    'activity' => $actName
                );


            }
            return array('success' => false, 'statusCode' => SUCCESS_RESPONSE, 'chart' => $results);
        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

    public function getChartData(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $co = $payload->userId;
            $results = "";
            $at = array();
            $lb = array();
            $className = "";
            $dates= array();
            $labels = array("January", "February","March", "April","May","June","July","August","September","October","November","December");

            $activity = $this->getId();

            $sql = "SELECT * FROM `classes` WHERE `co`='$co' AND `type`='$activity' ORDER BY `id` ASC LIMIT 0,1";
            $qry = mysqli_query($this->con, $sql);
            if (mysqli_num_rows($qry) != 0) {
                $rs = mysqli_fetch_assoc($qry);
                for ($x = 0; $x <= count($labels) - 1; $x++) {

                    $class = $rs['id'];
                    $className = $rs['name'];
                    $dat = date("Y-m-d",strtotime($labels[$x]));
                    $slq = "SELECT * FROM `class_sessions` WHERE `classID`='$class' AND MONTH(`dt`) = MONTH('$dat') AND YEAR(`dt`) = YEAR(now())";
                    $attend = 0;
                    $slqr = mysqli_query($this->con, $slq);
                    if (mysqli_num_rows($slqr) > 0) {

                        while ($rss = mysqli_fetch_assoc($slqr)) {

                            $dt = $rss['dt'];
                            $session = $this->getSession($dt, $class);
                            $atsql = "SELECT * FROM `attendance` WHERE `class_sess`='$session' AND `attend`='1' ";
                            $atqry = mysqli_query($this->con, $atsql);
                            $attend += mysqli_num_rows($atqry);

                        }
                    }
                    array_push($at, $attend);
                    array_push($lb, date("M", strtotime($dat)));

                }
                $asql = "SELECT * FROM `activitiz` WHERE `id`='$activity'";
                $aqry = mysqli_query($this->con, $asql);
                $ars = mysqli_fetch_assoc($aqry);
                $actName = $ars['nmt'];
                $results = array(
                    'sessions'=> $lb,
                    'attend' => $at,
                    'name' => $className,
                    'activity' => $actName
                );


            }

           return array('success' => false, 'statusCode' => SUCCESS_RESPONSE, 'chart' => $results);

        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

    public function getPieData(){
        try{
            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $co = $payload->userId;
            $results = "";
            $at = array();
            $cl = array();
            $ses = array();
            $dt = "";
            $activity = $this->getId();
            $sql = "SELECT * FROM `classes` WHERE `co`='$co' AND `type`='$activity'";
            $qry = mysqli_query($this->con, $sql);
            if (mysqli_num_rows($qry) != 0){
                while($rs = mysqli_fetch_assoc($qry)){
                    $name = $rs['name'];
                    $class = $rs['id'];
                    $attend = "";
                    $dt = date("Y-m-d");
                    $day = date('Y-m-d', strtotime('-1 day', strtotime($dt)));
                    $slq = "SELECT * FROM `class_sessions` WHERE `classID`='$class' AND `dt` <= '$day' ORDER BY `dt` ASC LIMIT 0,1";
                    $slqr = mysqli_query($this->con, $slq);
                    if (mysqli_num_rows($slqr) > 0) {

                        while ($rss = mysqli_fetch_assoc($slqr)) {
                            $dt = $rss['dt'];

                            $session = $this->getSession($dt, $class);
                            $atsql = "SELECT * FROM `attendance` WHERE `class_sess`='$session' AND `attend`='1' ";
                            $atqry = mysqli_query($this->con, $atsql);

                            $attend .= mysqli_num_rows($atqry); //accumulate this foreach class


                        }
                    }
                    array_push($cl, $name);
                    array_push($at, $attend);
                    array_push($ses, $dt);


                }
                $asql = "SELECT * FROM `activitiz` WHERE `id`='$activity'";
                $aqry = mysqli_query($this->con, $asql);
                $ars = mysqli_fetch_assoc($aqry);
                $actName = $ars['nmt'];
                $results = array(
                    'aClasses'=> $cl,
                    'attend' => $at,
                    'session'=> $dt,
                    'activity' => $actName
                );
            }

            return array('success' => false, 'statusCode' => SUCCESS_RESPONSE, 'chart' => $results);

        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }

    }

}