<?php

class A25_ErrorEmailBody
{
    public function generate($base_message)
    {
        return $base_message . "\n\n" . $this->referrer() . "\n\n"
        . $this->getDump() . "\n\n" . $this->postDump() . "\n\n"
        . $this->userId() . "\n\n" . $this->ipAddress();
    }

    private function referrer()
    {
        return 'REFERER: ' . $_SERVER['HTTP_REFERER'];
    }

    public function getDump()
    {
        $getdump = "GET:\n";
        foreach ($_GET as $key => $value) {
            $getdump .= "'$key' => '$value'\n";
        }
        return $getdump;
    }

    public function postDump()
    {
        $postdump = "POST:\n";
        foreach ($_POST as $key => $value) {
            $postdump .= "'$key' => '$value'\n";
        }
        return $postdump;
    }

    private function userId()
    {
        if (A25_DI::UserId() > 0) {
            return "\nAdministrative user ID: " . A25_DI::UserId() . "\n";
        }

        $student_id = A25_CookieMonster::getStudentIdFromCookie();

        if ($student_id > 0) {
            return "\nStudent ID: " . $student_id . "\n";
        }
    }

    private function ipAddress()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        return "IP Address: " . $ip . "\nHost Name: " . gethostbyaddr($ip);
    }
}
