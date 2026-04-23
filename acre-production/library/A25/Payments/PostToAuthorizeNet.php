<?php

namespace Acre\A25\Payments;

class PostToAuthorizeNet
{
    public function process($appendDescription = "")
    {
        $auth = new AuthorizeNetProcess();
        $_POST['x_exp_date'] = sprintf("%02d", (int) $_POST['expMonth'])
                . sprintf("%02d", (int) substr($_POST['expYear'], 2));

        if (!$auth->bind($_POST)) {
            throw new \A25_Exception_DataConstraint($auth->getError());
        }

        $auth->x_description = 'Payment for ' . \PlatformConfig::courseTitle . $appendDescription;

        if (!$auth->check()) {
            throw new \A25_Exception_DataConstraint($auth->getError());
        }

        if (!$auth->process()) {
            throw new \A25_Exception_DataConstraint($auth->getError());
        }

        return $auth->response;
    }
}
