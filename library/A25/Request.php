<?php
class A25_Request
{
  protected function fullUrl()
  {
    $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    $pageURL .= $_SERVER["SERVER_NAME"];
    if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
      {
         $pageURL .= ":" . $_SERVER["SERVER_PORT"];
      } 
    $pageURL .= $_SERVER["REQUEST_URI"];
    return $pageURL;
  }
  public function uri()
  {
    $http = A25_DI::ServerConfig()->httpUrl;
    $https = A25_DI::ServerConfig()->httpsUrl;
    $url = $this->fullUrl();
    
    if (substr($url, 0, strlen($http)) == $http)
    {
      $url = substr($url, strlen($http));
    }
    else if (substr($url, 0, strlen($https)) == $https)
    {
      $url = substr($url, strlen($https));
    }
    
    if ($url[0] != '/')
    {
      $url = '/' . $url;
    }
    
    return $url;
  }
}
