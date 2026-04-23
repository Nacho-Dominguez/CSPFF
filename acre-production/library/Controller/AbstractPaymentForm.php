<?php

abstract class Controller_AbstractPaymentForm extends Controller
{
    public function executeTask()
    {
        $student = A25_CookieMonster::getStudentFromCookie();

        $student->addLateFeeToAccountBalance($student->getAccountBalance());
        $balance = $student->getAccountBalance();
        if ($balance <= 0) {
            echo "Your account is already paid in full.";
            exit();
        }

        $form = $this->createForm($student);
        $form->run($student);
    }

    abstract protected function createForm($student);
}
