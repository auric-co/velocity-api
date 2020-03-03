<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 11/10/2019
 * Time: 11:19 PM
 */
include_once dirname(__FILE__) . '/vendor/autoload.php';

use Firebase\JWT\JWT;
class Users extends System
{
    protected $message;
    protected $subject;


    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }


    public function __construct()
    {
        return parent::__construct();
    }

    public function Login(){

        try{

            $email = $this->getEmail();
            $pwd = $this->getPassword();

            $stmt = $this->pdo ->prepare("SELECT * FROM `users` WHERE `email` = :user");
            $stmt->execute(array(':user' =>$email));

            $row = $stmt-> fetch(PDO::FETCH_ASSOC);

            if ($row['status'] == '2'){
                $hash = $row['password'];

                $options = array('cost' => 11);

                if (password_verify($pwd, $hash)) {

                    if (password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {

                        $newHash = password_hash($pwd, PASSWORD_DEFAULT, $options);

                        $sql = $this->pdo->prepare("UPDATE `users` SET `password` = '$newHash' WHERE `email`='$email'");
                        $sql->execute();
                    }

                    $client = array(
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'surname' => $row['surname'],
                        'email' => $row['email'],
                        'member_number' => $row['member_number'],
                        'msisdn' => $row['msisdn'],
                        'age' => $row['age'],
                        'weight' => $row['weight'],
                        'height' => $row['height'],
                        'profile' => $row['profile'],
                        'points' => $row['points'],
                        'company' => $this->Company($row['company']),
                        'status' => $row['status']
                        );

                    $paylod = [
                        'iat' => time(),
                        'iss' => $this->domain(),
                        'exp' => time() + (60*60*24*30),
                        'userId' => $row['id']
                    ];
                    $token = JWT::encode($paylod, SECRETE_KEY);
                    return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message' => 'Login successful','user' => $client, 'token' => $token);

                }else{
                    $data = array('success' => false, 'statusCode' => UNAUTHORISED, 'error'=> array('type' => "LOGIN_ERROR", 'message' => 'Invalid Login Credentials'));
                    return $data;
                }
            }elseif ($row['status'] == '0'){
                //not registered
                return array('success' => false, 'statusCode' => NOT_FOUND, 'error'=> array('type' => "SERVER_ERROR", 'message' => 'Account not Found. Please register'));
            }elseif ($row['status'] == '1'){
                //activate account
                return array('success' => false, 'statusCode' => FORBIDEN, 'error'=> array('type' => "SERVER_ERROR", 'message' => 'Account not Activated. Verify email to activate account'));
            }else{
                //email blocked
                 return array('success' => false, 'statusCode' => FORBIDEN, 'error'=> array('type' => "SERVER_ERROR", 'message' => 'Login forbidden for this email'));
            }

        }catch (\Exception $e){
            return array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => 'Server error.'));

        }
    }

    public function Register(){
        try{

            $company = $this->getId();
            $email = $this->getEmail();
            $options = array('cost' => 10);
            $newHash = password_hash($this->getPassword(), PASSWORD_DEFAULT, $options);
            $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `company` = '$company' AND `email`= '$email'");

            if ($stmt->execute()) {
                if ($stmt->rowCount() !== 0){
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row['status'] == '0'){
                        //register;
                        $code = rand(1000, 9999);
                        $upd = $this->pdo->prepare("UPDATE `users` SET  `password` = '$newHash' , `status` = '1' , `token` = '$code' WHERE `email` = '$email'");
                        if ($upd->execute()){
                            //sent activation email
                            $msq = "Your registration was successful. To complete registration please verify email to activate account. Your activation code is <strong>".$code."</strong>";
                            $this->setMessage($msq);
                            $send = $this->activationEmail();
                            return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Registration Successful. Visit email to for verification code to activate account');
                        }else{
                            return array(
                                'success' => false,
                                'statusCode' => INTERNAL_SERVER_ERROR,
                                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Registration Failed")
                            );
                        }
                    }elseif ($row['status'] == '1'){
                        //already registered, activate
                        return array(
                            'success' => false,
                            'statusCode' => FORBIDEN,
                            'status' => 'activate',
                            'error' => array('type' => 'REGISTRATION_EXCEPTION', 'message' => "Already Registered. Please verify email to activate account")
                        );
                    }elseif ($row['status'] == '2'){
                        //please login
                        return array(
                            'success' => false,
                            'statusCode' => FORBIDEN,
                            'status' => 'login',
                            'error' => array('type' => 'REGISTRATION_EXCEPTION', 'message' => "Already registered. Please login")
                        );
                    }else{
                        // email banned
                        return array(
                            'success' => false,
                            'statusCode' => FORBIDEN,
                            'status' => 'blocked',
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "User email registration forbidden")
                        );
                    }
                }else{
                    return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "User email for selected company not found. Registration forbidden")
                    );
                }

            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Internal server error")
                );
            }

        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

    public function Verify(){
        try{
            $email = $this->getEmail();
            $code = $this->getConfirmation();
            $check = $this->pdo->prepare("SELECT `token` FROM `users` WHERE `email`='$email' AND `token`='$code' ");
            if ($check->execute() && $check->rowCount() == '1'){
                $row = $check->fetch(PDO::FETCH_ASSOC);
                if ($row['token'] == $code){

                    $stmt = $this->pdo->prepare("UPDATE `users` SET `status` = '2' WHERE `email` = '$email' AND `token` = '$code'");
                    if ($stmt->execute()){
                        return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message'=> 'Activation Successful. Please login and enjoy');
                    }else{
                        return array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Internal server error")
                        );
                    }
                }else{
                    return array(
                        'success' => false,
                        'statusCode' => INTERNAL_SERVER_ERROR,
                        'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Activation code error for email ".$email)
                    );
                }
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Internal server error")
                );
            }
        }catch (Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
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

    public function Leaderboard()
    {
        try{

            $payload = JWT::decode($this->getToken(), SECRETE_KEY, ['HS256']);
            $user = $payload->userId;
            $get = $this->pdo->prepare("SELECT `id`,`company`, `name` , `surname`, `points` FROM `users` WHERE `id` ='$user'");
            if ($get->execute()){
                $rw = $get->fetch(PDO::FETCH_ASSOC);
                $id = $rw['company'];
                $stmt = $this->pdo->prepare("SELECT `id`, `name`, `surname`, `points` FROM `users` WHERE `company` = '$id' AND `id` != '$user' ORDER BY `points` DESC LIMIT 0,5");
                $all = array();
                $user = array(
                    'id' => $rw['id'],
                    'name' => $rw['name'],
                    'surname' => $rw['surname'],
                    'points' => $rw['points']
                );
                array_push($all, $user);
                if ($stmt->execute()) {
                    while($row = $stmt-> fetch(PDO::FETCH_ASSOC)){

                        $members = array(
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'surname' => $row['surname'],
                            'points' => $row['points']
                        );
                        array_push($all, $members);
                    }
                }
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'leaderboard'=> $all);
            }

        }catch (\Exception $e){
            return array(
                'success' => false,
                'statusCode' => INTERNAL_SERVER_ERROR,
                'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => $e->getMessage())
            );
        }
    }

    public function Company($id){
        $stmt = $this->pdo->prepare("SELECT * FROM `company` WHERE `id` = '$id'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'logo' => $row['logo_thumbn'],
            'status' => $row['status']
        );
        return $data;
    }

    public function activationEmail(){
        try{
            $this->mail->addAddress($this->getEmail());
            $this->mail->setFrom("noreply@velocityhealth.co.za");
            $this->mail->Subject = "Verify email to activate account";
            $this->mail->isHTML(true);
            $message = $this->getMessage();
            $body = '
                    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
                    <html>
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
                    <link rel="apple-touch-icon" sizes="180x180" href="images/icons/favicon.png">
                    <link rel="icon" type="image/png" sizes="32x32" href="http://velocityhealth.co.za/images/icons/favicon.png">
                    <link rel="icon" type="image/png" sizes="16x16" href="http://velocityhealth.co.za/images/icons/favicon.png">
                    <title>Account Activation - Velocity Wellness</title>
                    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
                    <style type="text/css">
                    html { -webkit-text-size-adjust: none; -ms-text-size-adjust: none;}
                    
                        @media only screen and (min-device-width: 750px) {
                            .table750 {width: 750px !important;}
                        }
                        @media only screen and (max-device-width: 750px), only screen and (max-width: 750px){
                          table[class="table750"] {width: 100% !important;}
                          .mob_b {width: 93% !important; max-width: 93% !important; min-width: 93% !important;}
                          .mob_b1 {width: 100% !important; max-width: 100% !important; min-width: 100% !important;}
                          .mob_left {text-align: left !important;}
                          .mob_soc {width: 50% !important; max-width: 50% !important; min-width: 50% !important;}
                          .mob_menu {width: 50% !important; max-width: 50% !important; min-width: 50% !important; box-shadow: inset -1px -1px 0 0 rgba(255, 255, 255, 0.2); }
                          .mob_center {text-align: center !important;}
                          .top_pad {height: 15px !important; max-height: 15px !important; min-height: 15px !important;}
                          .mob_pad {width: 15px !important; max-width: 15px !important; min-width: 15px !important;}
                          .mob_div {display: block !important;}
                        }
                       @media only screen and (max-device-width: 550px), only screen and (max-width: 550px){
                          .mod_div {display: block !important;}
                       }
                        .table750 {width: 750px;}
                    </style>
                    </head>
                    <body style="margin: 0; padding: 0;">
                    
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #f3f3f3; min-width: 350px; font-size: 1px; line-height: normal;">
                        <tr>
                        <td align="center" valign="top">   			
                            <!--[if (gte mso 9)|(IE)]>
                             <table border="0" cellspacing="0" cellpadding="0">
                             <tr><td align="center" valign="top" width="750"><![endif]-->
                            <table cellpadding="0" cellspacing="0" border="0" width="750" class="table750" style="width: 100%; max-width: 750px; min-width: 350px; background: #f3f3f3;">
                                <tr>
                                   <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
                                    <td align="center" valign="top" style="background: #ffffff;">
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
                                         <tr>
                                            <td align="right" valign="top">
                                               <div class="top_pad" style="height: 25px; line-height: 25px; font-size: 23px;">&nbsp;</div>
                                            </td>
                                         </tr>
                                      </table>
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                         <tr>
                                            <td align="left" valign="top">
                                               <div style="height: 20px; line-height: 20px; font-size: 18px;">&nbsp;</div>
                                               <font face="\'Source Sans Pro\', sans-serif" color="#585858" style="font-size: 16px; line-height: 32px;">
                                               '.nl2br($message).'
                                               </font>
                                               <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
                                            </td>
                                         </tr>
                                      </table>
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="90%" style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border-color: #e8e8e8; border-bottom: none; border-left: none; border-right: none;">
                                         <tr>
                                            <td align="left" valign="top">
                                               <div style="height: 15px; line-height: 15px; font-size: 13px;">&nbsp;</div>
                                            </td>
                                         </tr>
                                      </table>
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                         <tr>
                                            <td align="center" valign="top">
                                               <!--[if (gte mso 9)|(IE)]>
                                               <table border="0" cellspacing="0" cellpadding="0">
                                               <tr><td align="center" valign="top" width="50"><![endif]-->
                                               <div style="display: inline-block; vertical-align: top; width: 50px;">
                                                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                                     <tr>
                                                        <td align="center" valign="top">
                                                           <div style="height: 13px; line-height: 13px; font-size: 11px;">&nbsp;</div>
                                                           <div style="display: block; max-width: 50px;">
                                                           </div>
                                                        </td>
                                                     </tr>
                                                  </table>
                                               </div><!--[if (gte mso 9)|(IE)]></td><td align="left" valign="top" width="390"><![endif]--><div class="mob_div" style="display: inline-block; vertical-align: top; width: 62%; min-width: 260px;">
                                                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                                     <tr>
                                                        <td width="18" style="width: 18px; max-width: 18px; min-width: 18px;">&nbsp;</td>
                                                        <td class="mob_center" align="left" valign="top">
                                                           <div style="height: 13px; line-height: 13px; font-size: 11px;">&nbsp;</div>
                                                           <font face="\'Source Sans Pro\', sans-serif" color="#000000" style="font-size: 19px; line-height: 23px; font-weight: 600;">
                                                              <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #000000; font-size: 19px; line-height: 23px; font-weight: 600;">Administrator</span>
                                                           </font>
                                                           <div style="height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</div>
                                                           <font face="\'Source Sans Pro\', sans-serif" color="#7f7f7f" style="font-size: 19px; line-height: 23px;">
                                                              <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #7f7f7f; font-size: 19px; line-height: 23px;">Account activation</span>
                                                           </font>
                                                        </td>
                                                        <td width="18" style="width: 18px; max-width: 18px; min-width: 18px;">&nbsp;</td>
                                                     </tr>
                                                  </table>
                                               </div><!--[if (gte mso 9)|(IE)]></td><td align="left" valign="top" width="177"><![endif]--><div style="display: inline-block; vertical-align: top; width: 177px;">
                                                  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                                     <tr>
                                                        <td align="center" valign="top">
                                                           <div style="height: 13px; line-height: 13px; font-size: 11px;">&nbsp;</div>
                                                           <div style="display: block; max-width: 177px;">
                                                              <img src="http://velocityhealth.co.za/images/icons/logo.png" alt="img" width="177" border="0" style="display: block; width: 177px; max-width: 100%;" />
                                                           </div>
                                                        </td>
                                                     </tr>
                                                  </table>
                                               </div>
                                               <!--[if (gte mso 9)|(IE)]>
                                               </td></tr>
                                               </table><![endif]-->
                                               <div style="height: 30px; line-height: 30px; font-size: 28px;">&nbsp;</div>
                                            </td>
                                         </tr>
                                      </table>
                    
                                      <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
                                         <tr>
                                            <td align="center" valign="top">
                                               <div style="height: 34px; line-height: 34px; font-size: 32px;">&nbsp;</div>
                                               <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                                  <tr>
                                                     <td align="center" valign="top">
                                                        <font face="\'Source Sans Pro\', sans-serif" color="#868686" style="font-size: 17px; line-height: 20px;">
                                                           <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #868686; font-size: 17px; line-height: 20px;">Copyright &copy; '.date("Y").' Velocity Wellness. All&nbsp;Rights&nbsp;Reserved.</span>
                                                        </font>
                                                        <div style="height: 3px; line-height: 3px; font-size: 1px;">&nbsp;</div>
                                                        <font face="\'Source Sans Pro\', sans-serif" color="#1a1a1a" style="font-size: 17px; line-height: 20px;">
                                                           <span style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px;"><a href="#" target="_blank" style="font-family: \'Source Sans Pro\', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px; text-decoration: none;">admin@velocityhealth.co.za</a> &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>
                                                        </font>
                                                        <div style="height: 35px; line-height: 35px; font-size: 33px;">&nbsp;</div>
                                                     </td>
                                                  </tr>
                                               </table>
                                            </td>
                                         </tr>
                                      </table>  
                    
                                   </td>
                                   <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
                                </tr>
                             </table>
                             <!--[if (gte mso 9)|(IE)]>
                             </td></tr>
                             </table><![endif]-->
                          </td>
                       </tr>
                    </table>
                    </body>
                    </html>
        ';

            $this->mail->Body = $body;

            if ($this->mail->send()) {
                return true;
            }else{
                return false;
            }
        }catch (\PHPMailer\PHPMailer\Exception $e){
            return false;
        }
    }

    public function ActivationCode()
    {
        try {
            $email = $this->getEmail();
            $code = rand(1000, 9999);
            $upd = $this->pdo->prepare("UPDATE `users` SET  `token` = '$code' WHERE `email` = '$email'");
            if ($upd->execute()) {
                //sent activation email
                $msq = "You requested a new verification code. Enter the verification code to activate account. Your activation code is <strong>" . $code . "</strong>";
                $this->setMessage($msq);
                $send = $this->activationEmail();
                return array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'message' => 'New verification code has been sent. Activate account now');
            }else{
                return array(
                    'success' => false,
                    'statusCode' => INTERNAL_SERVER_ERROR,
                    'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => "Verification code request error Failed")
                );
            }
        } catch (\Exception $e) {
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error' => array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }
    }

}