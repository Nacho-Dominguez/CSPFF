<?php

class A25_Redirector
{
    public function __construct()
    {
    }

    public function redirect($url, $msg = '', $code = 303)
    {
        $url = $this->prefix($url, $_SERVER['REQUEST_URI']);

        if (!preg_match('#/administrator/#', $url)) {
            $url = A25_Link::convertToSEF($url);
        }

        $this->redirectAbsolute($url, $msg, $code);
    }

    public function redirectWithoutSef($url, $msg = '', $code = 303)
    {
        $url = $this->prefix($url, $_SERVER['REQUEST_URI']);

        $this->redirectAbsolute($url, $msg, $code);
    }

    public function redirectBasedOnRealPath($url, $msg = '', $code = 303)
    {
        $this->redirectAbsolute($this->createUrlForRealPath($url), $msg, $code);
    }

    public function redirectBasedOnSiteRoot($url, $msg = '', $code = 303)
    {
        $url = ServerConfig::currentUrl() . $url;

        $this->redirectAbsolute($url, $msg, $code);
    }

    public function redirectAbsolute($url, $msg = '', $code = 303)
    {
        $url = A25_Link::removeDoubleSlashes($url);

        require_once(dirname(__FILE__) . '/../../includes/joomlaClasses.php');
        mosRedirect($url, $msg, $code);
    }

    public function changeQueryString($newQuerystring, $msg, $code = 303)
    {
        $url = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI'])
             . '?' . $newQuerystring;

        $this->redirectAbsolute($url, $msg, $code);
    }

    public function redirectUsingJavascriptLocationReplace($link)
    {
        $output = '<a href="' . $link
            . '">Click here if you are not automatically redirected</a>';
        $output .= '<script type="text/javascript">
            window.location.replace("' . $link
            . '")</script>';
        echo $output;
    }

    protected function prefix($url, $requestUri)
    {
        if (preg_match('/http/', $url)) {
            return $url;
        }

        $prefix = preg_replace('#content/.*#', '', $requestUri);
        $prefix = preg_replace('#component/.*#', '', $prefix);
        $prefix = preg_replace('#[^/]+\.php.*#', '', $prefix);
        return A25_Link::removeDoubleSlashes($prefix . $url);
    }

    public function createUrlForRealPath($url)
    {
        $admin = '';
        if (preg_match('#/administrator#', $_SERVER['REQUEST_URI'])) {
            $admin = '/administrator/';
        }
        $url = ServerConfig::currentUrl() . $admin . $url;
        return $url;
    }
}
