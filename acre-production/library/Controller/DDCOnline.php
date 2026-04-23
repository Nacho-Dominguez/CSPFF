<?php

class Controller_DDCOnline extends Controller
{
    public function executeTask()
    {
        $response = $this->registerStudent();
        
        //If any other error than user already exists, throw exception
        $error = $this->findKey($response, 'error');
        if ($error) {
            if ($error == 'Login id already exists') {
                //If user does exist, login instead of register
                $response = $this->logStudentIn();
            }
            else {
                throw new Exception($error . ' - Student Email: ' . $_POST['emailAddress']);
            }
        }
        
        //Redirect, and throw expection if redirect url not found
        $redirect = $this->findKey($response, 'redirectUrl');
        
        if ($redirect) {
            echo '<meta http-equiv="refresh" content="0;url=' . $redirect . '">';
        }
        else {
            $error = $this->findKey($response, 'error');
            throw new Exception($error . ' - Student Email: ' . $_POST['emailAddress']);
        }
    }
    
    private function registerStudent()
    {
        $postData = array(
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'addressDetails' => array(
                'addressLine1' => $_POST['addressLine1'],
                'addressLine2' => $_POST['addressLine2'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'zipCode' => $_POST['zipCode']
            ),
            'phoneDetails' => array(
                'phoneNumber' => $_POST['phoneNumber'],
                'countryCode' => '+1'
            ),
            'emailAddress' => $_POST['emailAddress'],
            'loginId' => $_POST['loginId'],
            'password' => $_POST['password'],
            'accessCode' => $_POST['accessCode']
            );
        
        return $this->sendRequest($postData, 'https://steve-api.nsc.org/api/v1/public/registration/student');
    }
    
    private function logStudentIn()
    {
        $postData = array(
            'loginId' => $_POST['loginId'],
            'password' => $_POST['password']
        );
        
        return $this->sendRequest($postData, 'https://steve-api.nsc.org/api/v1/public/student/login');
    }
    
    private function sendRequest($postData, $url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'x-api-key: ' . A25_DI::PlatformConfig()->xapikey,
                'client-url:' . A25_DI::ServerConfig()->onlineCourseUrl,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));
        
        $response = curl_exec($ch);
        return json_decode($response, TRUE);
    }
    
    private function findKey($response, $search)
    {
        foreach ($response as $key => $item) {
            if ($key == $search) {
                return $item;
            } elseif (is_array($item) && array_key_exists($search, $item)) {
                return $item[$search];
            }
        }
        return false;
    }
}
