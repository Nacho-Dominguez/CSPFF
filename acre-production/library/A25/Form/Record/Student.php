<?php

class A25_Form_Record_Student extends A25_Form_Record
{
    public function __construct(
        A25_Record_Student $student,
        $returnUrl,
        $isReadOnly = false
    ) {
        $this->successMessage = 'Student Saved';

        if ($isReadOnly) {
            $student_id = new A25_Form_Element_Text('student_id');
            $this->addElement($student_id);

            $created = new A25_Form_Element_Text('created');
            $created->setLabel('Account Created');
            $this->addElement($created);
        }

        $userid = new A25_Form_Element_Text('userid');
        $userid->setLabel('Username');
        $userid->setRequired(true);

        if (!$student->exists()) {
            $userid->addValidator(new A25_Form_Validate_Unique($student, 'userid'));
        }

        $this->addElement($userid);

        $first_name = new A25_Form_Element_Text('first_name');
        $first_name->setRequired(true);
        $this->addElement($first_name);

        $middle_initial = new A25_Form_Element_Text('middle_initial');
        $middle_initial->setRequired(false);
        $this->addElement($middle_initial);

        $last_name = new A25_Form_Element_Text('last_name');
        $last_name->setRequired(true);
        $this->addElement($last_name);

        $address_1 = new A25_Form_Element_Text('address_1');
        $address_1->setRequired(false);
        $this->addElement($address_1);

        $address_2 = new A25_Form_Element_Text('address_2');
        $address_2->setRequired(false);
        $this->addElement($address_2);

        $city = new A25_Form_Element_Text('city');
        $city->setRequired(false);
        $this->addElement($city);

        $state = new A25_Form_Element_Select_State('state');
        $state->setRequired(false);
        $this->addElement($state);

        $zip = new A25_Form_Element_Text('zip');
        $zip->setRequired(false);
        $this->addElement($zip);

        $email = new A25_Form_Element_Text('email');
        $email->setRequired(false);
        $this->addElement($email);

        $home_phone = new A25_Form_Element_Text('home_phone');
        $home_phone->setRequired(false)
                ->setLabel('Primary Phone');
        $this->addElement($home_phone);

        $this->fireAfterHomePhone();

        $work_phone = new A25_Form_Element_Text('work_phone');
        $work_phone->setRequired(false)
                ->setLabel('Secondary Phone');
        $this->addElement($work_phone);

        $this->fireAfterWorkPhone();
        $this->fireAfterContactInfo();

        $license_status = new A25_Form_Element_Select_FromTable(
            'license_status',
            'jos_license_status',
            'status_id',
            'status_name'
        );
        $license_status->setRequired(false);
        $this->addElement($license_status);

        $this->fireAfterLicenseStatus();

        $license_state = new A25_Form_Element_Select_State('license_state');
        $license_state->setRequired(false)
                ->setLabel('Issuing State');
        $this->addElement($license_state);

        $date_of_birth = new A25_Form_Element_Date('date_of_birth');
        $date_of_birth->setRequired(true);
        $this->addElement($date_of_birth);

        $gender = new A25_Form_Element_Radio('gender');
        $gender->setLabel('Sex');
        $gender->setRequired(true)
                ->addMultiOptions(array('M'=>'Male','F'=>'Female'));
        $this->addElement($gender);

        $special_needs = new A25_Form_Element_Textarea('special_needs');
        $special_needs->setRequired(false);
        $this->addElement($special_needs);
        
        $this->fireAfterSpecialNeedsAdmin();

        parent::__construct($student, $returnUrl, $isReadOnly);
    }

    protected function redirect()
    {
        A25_DI::Redirector()->changeQueryString(
            'option=com_student&task=viewA&id=' . $this->_record->student_id,
            'Student Saved.'
        );
    }


    private function fireAfterHomePhone()
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_PhoneNumbers) {
                $listener->studentFormAfterHomePhone($this);
            }
        }
    }

    private function fireAfterWorkPhone()
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_PhoneNumbers) {
                $listener->studentFormAfterWorkPhone($this);
            }
        }
    }

    private function fireAfterContactInfo()
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_ContactInfo) {
                $listener->adminFormContactInfo($this);
            }
        }
    }

    private function fireAfterLicenseStatus()
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_AdminStudentForm) {
                $listener->studentFormAfterLicenseStatus($this);
            }
        }
    }

    private function fireAfterSpecialNeedsAdmin()
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_RegisterFormOtherInformation) {
                $listener->afterSpecialNeedsAdmin($this);
            }
        }
    }
}
