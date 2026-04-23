<?php

namespace Acre\A25\Payments;

class SimFormGenerationData
{
    private $sequence;
    private $timestamp;

    public function fingerprint($amount)
    {
        return \AuthorizeNetSIM_Form::getFingerprint(
            \PlatformConfig::AUTHORIZE_NET_LOGIN,
            \PlatformConfig::AUTHORIZE_NET_TRAN_KEY,
            $amount,
            $this->sequence(),
            $this->timestamp()
        );
    }

    public function sequence()
    {
        if (empty($this->sequence)) {
            $this->sequence = rand(0, 999999) . time();
        }

        return $this->sequence;
    }

    public function timestamp()
    {
        if (empty($this->timestamp)) {
            $this->timestamp = time();
        }

        return $this->timestamp;
    }

    public function isTestRequest()
    {
        if (\ServerConfig::arePaymentsLive) {
            return 'FALSE';
        }

        return 'TRUE';
    }
}
