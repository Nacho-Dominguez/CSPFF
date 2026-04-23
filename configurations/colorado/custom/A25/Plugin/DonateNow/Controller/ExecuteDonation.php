<?php

require_once dirname(__FILE__)
        . '/../../../administrator/components/com_pay/pay.class.php';

class Controller_ExecuteDonation extends Controller
{
    public function executeTask()
    {
        $this->checkForm();

        $transaction_id = $this->processWithAuthorizeNet();

        $recorder = new A25_RecordDonation(new A25_AimDonationRedirect());
        $recorder->recordAndRedirect($transaction_id);
    }

    private function checkForm()
    {
        $form = new Zend_Form();
        $elementMaker = new A25_ElementMaker_Donation($form);
        $elementMaker->elements();

        if (!$form->isValid($_POST)) {
            throw new Exception('Invalid data entered.  Please check your entries.');
        }
    }

    private function processWithAuthorizeNet()
    {
        $auth = new \Acre\A25\Payments\AuthorizeNetProcess();

        $auth->x_amount = $_POST['x_amount'];
        $auth->x_card_num = $_POST['card_number'];
        $auth->x_exp_date = sprintf("%02d", (int) $_POST['expiration_month'])
                . sprintf("%02d", (int) substr($_POST['expiration_year'], 2));
        $auth->x_card_code = $_POST['cvv_number'];

        $auth->x_description = 'Donation to ' . PlatformConfig::agency;
        if ($_POST['benefactor']) {
            $auth->x_description .= ' from ' . $_POST['benefactor'];
        }

        if (!$auth->check()) {
            throw new A25_Exception_DataConstraint($auth->getError());
        }

        if (!$auth->process()) {
            throw new A25_Exception_DataConstraint($auth->getError());
        }

        return $auth->response['x_trans_id'];
    }
}
