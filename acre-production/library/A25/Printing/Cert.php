<?php
namespace Acre\A25\Printing;

abstract class Cert extends \A25_StrictObject implements
    CertInterface
{
    protected $listeners;
    protected $generator;

    public function __construct($generator, $listeners)
    {
        $this->generator = $generator;
        $this->listeners = $listeners;
    }

    public function generate(\A25_Record_Enroll $enroll)
    {
        $this->printText($enroll);
        $this->fireAfterCompletionDate($enroll);
        $this->generator->output();
    }
    abstract protected function printText(\A25_Record_Enroll $enroll);

    public function writeToLicenseNumberLines($text)
    {
        throw new Exception('Not implemented');
    }
    public function bigTextTop($text)
    {
        throw new Exception('Not implemented');
    }
    public function bigTextMiddle($text)
    {
        throw new Exception('Not implemented');
    }
    public function bigTextBottom($text)
    {
        throw new Exception('Not implemented');
    }

    protected function fireAfterCompletionDate(\A25_Record_Enroll $enroll)
    {
        foreach ($this->listeners as $listener) {
            if ($listener instanceof \A25_ListenerI_CertPdf) {
                $listener->afterCompletionDate($this, $enroll);
            }
        }
    }
}
