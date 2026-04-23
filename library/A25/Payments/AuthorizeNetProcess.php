<?php

namespace Acre\A25\Payments;

/*
 * This class manages the payments through Authorize.net.  To change it to a
 * different account, x_login and x_tran_key are key.
 */
class AuthorizeNetProcess
{
    /*
	 * The API Login ID for the Authorize.net account
	 */
    public $x_login = null;
    /*
	 * The Transaction Key for the Authorize.net account
	 */
    public $x_tran_key = null;
    public $x_version = null;

    public $x_test_request = null;

    public $x_delim_data = null;
    public $x_delim_char = null;
    public $x_encap_char = null;
    public $x_relay_response = null;
    public $x_duplicate_window = null;

    public $x_first_name = null;
    public $x_last_name = null;
    public $x_company = null;
    public $x_address = null;
    public $x_city = null;
    public $x_state = null;
    public $x_zip = null;
    public $x_country = null;
    public $x_phone = null;
    public $x_fax = null;
    public $x_cust_id = null;
    public $x_customer_ip = null;
    public $x_customer_tax_id = null;

    public $x_email = null;
    public $x_email_customer = null;
    public $x_merchant_email = null;
    public $x_invoice_num = null;
    public $x_description = null;

    public $x_amount = null;
    public $x_currency_code = null;
    public $x_method = null;
    public $x_type = null;

    public $x_card_num = null;
    public $x_exp_date = null;
    public $x_card_code = null;

    public $post_url = 'https://secure2.authorize.net/gateway/transact.dll';
    public $response = null;

    public $error = null;

    public function __construct()
    {
        $this->x_login = \PlatformConfig::AUTHORIZE_NET_LOGIN;
        $this->x_tran_key = \PlatformConfig::AUTHORIZE_NET_TRAN_KEY;
        $this->x_version = '3.1';

        if (\ServerConfig::arePaymentsLive) {
            $this->x_test_request = 'FALSE';
        } else {
            $this->x_test_request = 'TRUE';
        }
        $this->x_email_customer = 'TRUE';

        $this->x_delim_data = 'TRUE';
        $this->x_delim_char = '|';
        $this->x_encap_char = '';
        $this->x_relay_response = 'FALSE';
        $this->x_duplicate_window = 120;

        $this->x_method = 'CC';
        $this->x_type = 'AUTH_CAPTURE';
    }

    /**
    * Binds instance of class to the $_POST
    * @author Christiaan van Woudenberg
    * @version August 28, 2006
    *
    *   @param array $hash named array
    *   @return null|string null if operation was satisfactory
    */
    public function bind($array, $ignore = '')
    {
        if (!is_array($array)) {
            $this->error = strtolower(get_class($this))."::bind failed.";
            return false;
        } else {
            return \A25_Functions::bindArrayToObject($array, $this, $ignore);
        }
    }

    /**
     * Checks the $auth object for consistency
     *
     * @param null
     * @return boolean
     */
    public function check()
    {
        // check for valid login name
        if (strlen(trim($this->x_login)) == 0) {
            $this->error = "Authorize.net login name cannot be empty.";
            return false;
        }

        // check for valid transaction key
        if (strlen(trim($this->x_tran_key)) == 0) {
            $this->error = "Authorize.net transaction key cannot be empty.";
            return false;
        }

        $this->x_customer_ip = $_SERVER['REMOTE_ADDR'];
        return true;
    }


    /**
     * Function to charge credit card
     *
     * @throws Exception
     * @return boolean
     */
    public function process()
    {
        $fieldstr = '';
        foreach ($this as $key => $val) {
            if (strpos($key, 'x_') !== false && $val != null) {
                $fieldstr .= '&' . $key . '=' . urlencode($val);
            }
        }

        // Remove leading &
        $fieldstr = substr($fieldstr, 1);

        //open cURL session
        $ch = curl_init($this->post_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldstr); // set the fields to post
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // make sure we get the response back
        // Set the location of authorize.net's SSL certificate:
        curl_setopt($ch, CURLOPT_CAINFO, \ServerConfig::webRoot .
                '/administrator/components/com_pay/ca-bundle.crt');
        \ServerConfig::setCurlProxy($ch);

        //execute cURL POST
        $buffer = curl_exec($ch);
        if (!$buffer) {
            throw new Exception("cURL POST failed.  cURL error #: " .
                curl_errno($ch) . ". Message: " . curl_error($ch));
        }

        //close cURL session
        curl_close($ch); // close our session

        $response = explode($this->x_delim_char, $buffer); // create an array of the response values

        $this->response = array();
        $response_keys = array (
            'x_response_code', 'Response Subcode', 'Response Reason Code', 'Response Reason Text',
            'Approval Code', 'AVS Result Code', 'x_trans_id', 'x_invoice_num', 'Description',
            'x_amount', 'Method', 'Transaction Type', 'x_cust_id', 'x_first_name',
            'x_last_name', 'Company', 'Billing Address', 'City', 'State',
            'Zip', 'Country', 'Phone', 'Fax', 'Email', 'Ship to First Name', 'Ship to Last Name',
            'Ship to Company', 'Ship to Address', 'Ship to City', 'Ship to State',
            'Ship to Zip', 'Ship to Country', 'Tax Amount', 'Duty Amount', 'Freight Amount',
            'Tax Exempt Flag', 'PO Number', 'MD5 Hash', 'Card Code (CVV2/CVC2/CID) Response Code',
            'Cardholder Authentication Verification Value (CAVV) Response Code'
        );

        for ($i=0; $i<(count($response_keys)-1); $i++) {
            $this->response[$response_keys[$i]] = $response[$i];
        }

        if ($this->response['x_response_code'] != 1) {
            $this->error = 'There was an error charging your credit card:<br/><br/>'
                . $this->response['Response Reason Text']
                . '<br/><br/>Please ensure your information is correct and submit your '
                . 'card for payment again.<br/><br/>'
                . 'You may also call an ' . \PlatformConfig::agency
                . ' representative at ' . \PlatformConfig::phoneNumber
                . ' for further assistance.';

            return false;
        }

        return true;
    }

    /**
     * Returns error message, prepared for javascript alert
     *
     * @return string
     */
    public function getError()
    {
        return str_replace(array( "\n", "'" ), array( '\n', "\'" ), $this->error);
    }
}
