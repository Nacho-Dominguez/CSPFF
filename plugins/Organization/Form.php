<?php

class A25_Plugin_Organization_Form extends A25_Form_Record
{
	public function __construct(JosOrganization $organization, $returnUrl,
			$isReadOnly = false)
    {
		$this->successMessage = 'Organization Saved';

		if($isReadOnly) {
			$organization_id = new A25_Form_Element_Text('organization_id');
			$this->addElement($organization_id);
		}

		$name = new A25_Form_Element_Text('name');
        $name->setRequired(true);
		$this->addElement($name);

		$password = new A25_Form_Element_Text('password');
		$this->addElement($password);
		
        parent::__construct($organization, $returnUrl, $isReadOnly);
    }

	protected function redirect()
	{
		A25_DI::Redirector()->redirectBasedOnRealPath(
			'/ViewOrganization?id=' . $this->_record->organization_id,
			'Organization Saved.'
		);
	}
}
