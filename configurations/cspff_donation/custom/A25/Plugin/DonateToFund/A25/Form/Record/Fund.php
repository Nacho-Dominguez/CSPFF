<?php

class A25_Form_Record_Fund extends A25_Form_Record
{
    public function __construct(A25_Record_Fund $fund)
    {
        $this->successMessage = 'Fund Saved';

        $name = new A25_Form_Element_Text('name');
        $name->setRequired(true);
        $name->setAttrib('autofocus', 'autofocus');
        $this->addElement($name);

        $is_active = new A25_Form_Element_Radio_IsActive(
            'is_active',
            A25_DI::DB()
        );
        $is_active->setRequired(true);
        $this->addElement($is_active);

        parent::__construct($fund, $returnUrl, $isReadOnly);
    }

    protected function redirect()
    {
        A25_DI::Redirector()->redirectBasedOnSiteRoot(
            '/administrator/view-funds',
            $this->successMessage
        );
    }
}
