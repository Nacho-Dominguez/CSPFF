<?php

class A25_SmsSender
{
  private $client;
  
  private static $instance;
  
  public function __construct()
  {
    $sid = 'YOUR_TWILIO_ACCOUNT_SID'; // Your Account SID from www.twilio.com/user/account
    $token = 'YOUR_TWILIO_AUTH_TOKEN'; // Your Auth Token from www.twilio.com/user/account

    // Required for class Services_Twilio
    require_once ServerConfig::webRoot . '/third-party/twilio/Services/Twilio.php';
    $this->client = new Services_Twilio($sid, $token);
  }
  
  public static function instance()
  {
    if(self::$instance == null)
      self::$instance = new A25_SmsSender();
    return self::$instance;
  }
  
  public static function setInstance($instance)
  {
    self::$instance = $instance;
  }
  
    public function send($message, $to_number)
    {
        if(!$this->isValidNumber($to_number)) {
            return false;
        }
        $from_number = A25_DI::PlatformConfig()->twilioPhoneNumber;
        try {
            $this->create(
            $from_number,
            $to_number,
            $message
        ); 
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
  
  protected function isValidNumber($number)
  {
    if(preg_match('/\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}/', $number))
      return true;
    return false;
  }
  
  /**
   * 
   * @param string $from_number - a valid Twilio number
   * @param string $to_number
   * @param string $message
   */
  protected function create($from_number, $to_number, $message)
  {
    $this->client->account->messages->create(
            array('From' => $from_number,
                'To' => $to_number,
                'Body' => $message)
    );
  }
}
