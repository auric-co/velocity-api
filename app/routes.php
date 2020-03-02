<?php
//Hpme Route
$app->get('/', App\Action\HomeAction::class)
    ->setName('Velocity API');
//System
include_once dirname(__FILE__). '/../app/src/system/System.php';
include_once dirname(__FILE__). '/../app/src/system/Admin.php';
include_once dirname(__FILE__). '/../app/src/system/Mail.php';

$op = new System();
$admin = new Admin();
$mail = new Mail();

$app->get('/clients/points/leaderboard', function($request, $response)use($op){
    
});


//clients routes
$app->group('/api/client', function ($group)use($op){

    $group->post('/login', function ($request, $response)use($op){
        $params = $request->getParsedBody();
        $op->setEmail($params['email']);
        $op->setPassword($params['password']);
        $login = $op->CompanyLogin();
        return $response->withJson($login)->withStatus($login['statusCode']);

    });
    
    $group->post('/update/password', function ($request, $response)use($op){
        $params = $request->getParsedBody();
        $op->setPassword($params['password']);
        $op->setToken($params['token']);
        if($params['newPassword'] == $params['confirmPassword']){
            $op->setNewPassword($params['newPassword']);
        }else{
            return $response->withJson()->withStatus();
        }
    
        $login = $op->CompanyUpdatePassword();
        return $response->withJson($login)->withStatus($login['statusCode']);

    });

    $group->post('/members', function ($request, $response)use($op){
        $params = $request->getParsedBody();
        $op->setToken($params['token']);
        $members = $op->allMembers();
        return $response->withJson($members)->withStatus($members['statusCode']);
    });

    $group->post('/members/points/leaderboard', function ($request, $response)use($op){
        $params = $request->getParsedBody();
        $op->setToken($params['token']);
        $leaderboard = $op->memberLeaderboard();
        return $response->withJson($leaderboard)->withStatus($leaderboard['statusCode']);
    });

    $group->put('/register/employee', function ($request, $response)use($op){
        $params = $request->getParsedBody();
        $token = $params['token'];
        $op->setToken($token);
        $name = $op->validateParameter('First Name', $params['name'], STRING);
        $surname = $op->validateParameter('Last Name', $params['surname'], STRING);
        $position = $op->validateParameter('Position', $params['position'], STRING);
        $email = $op->validateParameter('Email', $params['email'], 'email');
        $sex = $op->validateParameter('Gender', $params['sex'], STRING);

        if ($email['success']){
            $op->setEmail($email['data']);
        }else{
            return $response->withJson($email)->withStatus($email['statusCode']);
        }


        if ($name['success']){
            $op->setName($name['data']);
        }else{
            return $response->withJson($name)->withStatus($name['statusCode']);
        }

        if ($surname['success']){
            $op->setLastName($surname['data']);
        }else{
            return $response->withJson($surname)->withStatus($surname['statusCode']);
        }

        if ($position['success']){
            $op->setDept($position['data']);
        }else{
            return $response->withJson($position)->withStatus($position['statusCode']);
        }

        if ($sex['success']){
            $op->setGender($sex['data']);
        }else{
            return $response->withJson($sex)->withStatus($sex['statusCode']);
        }

        $add = $op->saveMember();

        return $response->withJson($add)->withStatus($add['statusCode']);

    });

    $group->get('/wellness/classes', function ($request, $response)use($op){

    });

    $group->post('/activities/data/bar/{activity}', function ($request, $response)use($op){
        $activity = $request->getAttribute('activity');
        $params = $request->getParsedBody();
        $op->setToken($params['token']);
        $op->setId($activity);
        $data = $op->getBarData();

        return $response->withJson($data)->withStatus($data['statusCode']);
    });

    $group->post('/activities/data/chart/{activity}', function ($request, $response)use($op){
        $activity = $request->getAttribute('activity');
        $params = $request->getParsedBody();
        $op->setToken($params['token']);
        $op->setId($activity);
        $data = $op->getChartData();

        return $response->withJson($data)->withStatus($data['statusCode']);
    });

    $group->post('/activities/data/pie/{activity}', function ($request, $response)use($op){
        $activity = $request->getAttribute('activity');
        $params = $request->getParsedBody();
        $op->setToken($params['token']);
        $op->setId($activity);
        $data = $op->getPieData();

        return $response->withJson($data)->withStatus($data['statusCode']);
    });

});

$app->post("/mail/contact/send", function($request, $response)use($mail){
    $params = $request->getParsedBody();
    $mail->setMessage($params['message']);
    $mail->setEmail($params['email']);
    $mail->setSubject($params['subject']);
    $mail->setName($params['name']);
    $send = $mail->formCapture();
    return $response
    ->withJson($send)
    ->withStatus($send['statusCode']);
});

$app->post("/newsletter/subscribe", function($request, $response)use($op){
    $params = $request->getParsedBody();
    $name = $op->validateParameter('Name', $params['name'], STRING, false);
    $email = $op->validateParameter('Email', $params['email'], 'email');
    if($params['name']){
        if ($name['success'] == true){
         $op->setName($name['data']);
        }else{
            return $request
                ->withJson($name)->withStatus($name['statusCode']);
        }
    }
    

    if ($email){
        $op->setEmail($email['data']);
    }else{
        return $request
            ->withJson($email)->withStatus($email['statusCode']);
    }
    $subscribe = $op->newsletterSubscribe();
    return $response
    ->withJson($subscribe)
    ->withStatus($subscribe['statusCode']);
});


$app->group('/admin', function ($group)use($admin){

    $group->post('/login', function ($request, $response)use($admin){
        $params = $request->getParsedBody();
        $admin->setEmail($params['email']);
        $admin->setPassword($params['password']);
        $login = $admin->Login();
        return $response->withJson($login)->withStatus($login['statusCode']);

    });

    $group->get('/clients', function ($request, $response)use($admin){
        $clients = $admin->companies();
        return $response->withJson($clients)->withStatus($clients['statusCode']);
    });

    $group->put('/register/client/member', function ($request, $response)use ($admin){
        $params = $request->getParsedBody();
        $name = $admin->validateParameter('First Name', $params['name'], STRING);
        $surname = $admin->validateParameter('Last Name', $params['surname'], STRING);
         $msisdn = $admin->validateParameter('Mobile', $params['msisdn'], STRING, false);
        $member_number = $admin->validateParameter('Member Number', $params['member_number'], STRING, false);
        $age = $admin->validateParameter('Age', $params['age'], STRING, false);
        $weight = $admin->validateParameter('Weight', $params['weight'], STRING,false);
        $height = $admin->validateParameter('Height', $params['height'], STRING,false);
        $bmi = $admin->validateParameter('BMI', $params['bmi'], STRING,false);
        $co = $admin->validateParameter('Company', $params['company'], STRING);
        
        if(!empty($params['msisdn'])){
            if ($msisdn['success']){
                $admin->setMsisdn($msisdn['data']);
            }else{
                return $response->withJson($msisdn)->withStatus($msisdn['statusCode']);
            }
        }else{
            $admin->setMsisdn("263712".rand(100,900)."--9");
        }
        
        
        if(!empty($params['email'])){
            $email = $admin->validateParameter('Email', $params['email'], 'email', false); 
            if ($email['success']){
                $admin->setEmail($email['data']);
            }else{
                return $response->withJson($email)->withStatus($email['statusCode']);
            }
        }else{
             $admin->setEmail($surname['data']."@example.com");
        }

        if ($age['success']){
            $admin->setAge($age['data']);
        }else{
            return $response->withJson($age)->withStatus($age['statusCode']);
        }

        if ($weight['success']){
            $admin->setWeight($weight['data']);
        }else{
            return $response->withJson($weight)->withStatus($weight['statusCode']);
        }

        if ($height['success']){
            $admin->setHeight($height['data']);
        }else{
            return $response->withJson($height)->withStatus($height['statusCode']);
        }

        if ($bmi['success']){
            $admin->setBmi($bmi['data']);
        }else{
            return $response->withJson($bmi)->withStatus($bmi['statusCode']);
        }

        if ($member_number['success']){
            $admin->setMemberNum($member_number['data']);
        }else{
            return $response->withJson($member_number)->withStatus($member_number['statusCode']);
        }

        if ($co['success']){
            $admin->setId($co['data']);
        }else{
            return $response->withJson($co)->withStatus($co['statusCode']);
        }

        if ($name['success']){
            $admin->setName($name['data']);
        }else{
            return $response->withJson($name)->withStatus($name['statusCode']);
        }

        if ($surname['success']){
            $admin->setLastName($surname['data']);
        }else{
            return $response->withJson($surname)->withStatus($surname['statusCode']);
        }


        $add = $admin->saveMember();

        return $response->withJson($add)->withStatus($add['statusCode']);
    });
    
    $group->put('/register/client', function ($request, $response)use ($admin){
        $params = $request->getParsedBody();
        
        $name = $admin->validateParameter('Company Name', $params['name'], STRING);
        $email = $admin->validateParameter('Email', $params['email'], 'email');
        
        $msisdn = $admin->validateParameter('Mobile', $params['msisdn'], STRING, false);
        
        $logo = $admin->validateParameter('Logo', $params['logo'], STRING);

        if ($email['success']){
            $admin->setEmail($email['data']);
        }else{
            return $response->withJson($email)->withStatus($email['statusCode']);
        }

        if ($msisdn['success']){
            $admin->setMsisdn($msisdn['data']);
        }else{
            return $response->withJson($msisdn)->withStatus($msisdn['statusCode']);
        }

        if ($name['success']){
            $admin->setName($name['data']);
        }else{
            return $response->withJson($name)->withStatus($name['statusCode']);
        }

        if ($logo['success']){
            $admin->setThumbnail($logo['data']);
        }else{
            return $response->withJson($logo)->withStatus($logo['statusCode']);
        }
        
        $admin->setPassword("password2020");

        $add = $admin->saveClient();

        return $response->withJson($add)->withStatus($add['statusCode']);
    });
    
    $group->get('/client/members/{id}',function($request, $response)use($admin){
        $id = $request->getAttribute('id');
        $admin->setId($id);
        $members = $admin->clientMembers();
        return $response->withJson($members)->withStatus($members['statusCode']);
    });
    $group->get('/member/details/{member}', function($request, $response)use($admin){
            $member = $request->getAttribute('member');
            $admin->setId($member);
            $member = $admin->memberDetails();
            return $response->withJson($member)->withStatus($member['statusCode']);
    });
    $group->patch('/update/client/member/{member}', function ($request, $response)use ($admin){
            $params = $request->getParsedBody();
            $member = $request->getAttribute('member');
            
            $admin->setId($member);
            $name = $admin->validateParameter('First Name', $params['name'], STRING);
            $surname = $admin->validateParameter('Last Name', $params['surname'], STRING);
            $email = $admin->validateParameter('Email', $params['email'], 'email');
            $msisdn = $admin->validateParameter('Mobile', $params['msisdn'], STRING);
            $member_number = $admin->validateParameter('Member Number', $params['member_number'], STRING, false);
            $age = $admin->validateParameter('Age', $params['age'], STRING, false);
            $weight = $admin->validateParameter('Weight', $params['weight'], STRING,false);
            $height = $admin->validateParameter('Height', $params['height'], STRING,false);
            $bmi = $admin->validateParameter('BMI', $params['bmi'], STRING,false);
    
            if ($email['success']){
                $admin->setEmail($email['data']);
            }else{
                return $response->withJson($email)->withStatus($email['statusCode']);
            }
    
            if ($msisdn['success']){
                $admin->setMsisdn($msisdn['data']);
            }else{
                return $response->withJson($msisdn)->withStatus($msisdn['statusCode']);
            }
    
            if ($age['success']){
                $admin->setAge($age['data']);
            }else{
                return $response->withJson($age)->withStatus($age['statusCode']);
            }
    
            if ($weight['success']){
                $admin->setWeight($weight['data']);
            }else{
                return $response->withJson($weight)->withStatus($weight['statusCode']);
            }
    
            if ($height['success']){
                $admin->setHeight($height['data']);
            }else{
                return $response->withJson($height)->withStatus($height['statusCode']);
            }
    
            if ($bmi['success']){
                $admin->setBmi($bmi['data']);
            }else{
                return $response->withJson($bmi)->withStatus($bmi['statusCode']);
            }
    
            if ($member_number['success']){
                $admin->setMemberNum($member_number['data']);
            }else{
                return $response->withJson($member_number)->withStatus($member_number['statusCode']);
            }
    
            if ($name['success']){
                $admin->setName($name['data']);
            }else{
                return $response->withJson($name)->withStatus($name['statusCode']);
            }
    
            if ($surname['success']){
                $admin->setLastName($surname['data']);
            }else{
                return $response->withJson($surname)->withStatus($surname['statusCode']);
            }
    
    
            $add = $admin->memberUpdate();
    
            return $response->withJson($add)->withStatus($add['statusCode']);
        });
        
        
    $group->get('/wellness/activities', function($request, $response)use($admin){
        $activities = $admin-> wellnessActivities();
        return $response->withJson($activities)->withStatus($activities['statusCode']);
    });
    
    $group->get('/wellness/activities/classes/{activity}', function($request, $response)use($admin){
        $id = $request->getAttribute('activity');
        $admin->setId($id);
        $classes = $admin-> activityClasses();
        return $response->withJson($classes)->withStatus($classes['statusCode']);
    });
    
    $group->get('/wellness/classes', function($request, $response)use($admin){
    
        $classes = $admin-> Classes();
        return $response->withJson($classes)->withStatus($classes['statusCode']);
    });
    
    $group->get('/wellness/activity/class/register/{activity}/{company}/{session}', function($request,$response)use($admin){
        $co = $request->getAttribute('company');
        $act = $request->getAttribute('activity');
        $session = $request->getAttribute('session');
        $admin->setId($co);
        $admin->setSession_id($session);
        $admin->setClass_id($act);
        $members = $admin->ClassRegister();
        return $response->withJson($members)->withStatus($members['statusCode']);
        
    });
    
    $group->put('/wellness/activity/class/register/mark', function($request, $response)use($admin){
        $params = $request->getParsedBody();
        $session = $params['session'];
        $class = $params['class'];
        $member = $params['member'];
        $status = $params['attend']; // 1 yes, 2 no,
        $admin->setClass_id($class);
        $admin->setSession_id($session);
        $admin->setId($member);
        $admin->setStatus($status);
        
        $attend = $admin->Attendance();
        return $response->withJson($attend)->withStatus($attend['statusCode']);
        
    });
    
    
    
    
    
    
    
    
    
    
    
    
    
});