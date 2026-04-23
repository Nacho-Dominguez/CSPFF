<?php
class A25_Hasher
{
  public function generateSalt()
  {
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $salt = '';
    
    for ($i = 0; $i < 9; $i++)
    {
      $salt .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    
    return $salt;
  }
  public function hash($salt_prefix, $password)
  {
    if (strlen($salt_prefix) != 9)
      throw new Exception("salt_prefix must be 9 characters");
    
    $salt = '$2a$10$' . $salt_prefix . 'XZ4Y57no8uzHL';
    $hash = crypt($password, $salt);
    
    // crypt() returns $salt followed by the hashed password, so we need to
    // remove $salt
    return substr($hash, 29);
  }
}
