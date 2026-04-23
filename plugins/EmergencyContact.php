<?php

class A25_Plugin_EmergencyContact implements
    A25_ListenerI_Doctrine,
    A25_ListenerI_ContactInfo
{
    public function studentFormContactInfo()
    {
        echo '<tr>
    <td class="formlabel">
        <label for="emergency_contact">Emergency Contact Name:</label>
    </td>
    <td>
        <input type="text" name="emergency_contact" id="emergency_contact"
            size="30" maxlength="80" class="inputbox required"
            tmt:required="true" tmt:errorclass="invalid"
            tmt:message="Please enter an emergency contact person"/>
    </td>
</tr>
<tr>
    <td class="formlabel">
        <label for="emergency_phone">Emergency Contact Phone:</label>
    </td>
    <td>
        <input type="text" name="emergency_phone" id="emergency_phone"
            size="30" maxlength="80" class="inputbox required"
            tmt:required="true" tmt:errorclass="invalid"
            tmt:message="Please enter an emergency contact phone number"/>
    </td>
</tr>';
    }

    public function adminFormContactInfo(A25_Form_Record_Student $form)
    {
        $emergency_contact = new A25_Form_Element_Text('emergency_contact');
        $emergency_contact->setRequired(true)
            ->setLabel('Emergency Contact Name');
        $form->addElement($emergency_contact);

        $emergency_phone = new A25_Form_Element_Text('emergency_phone');
        $emergency_phone->setRequired(true)
            ->setLabel('Emergency Contact Phone');
        $form->addElement($emergency_phone);
    }

    public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
    {
        if (!$doctrineRecord instanceof A25_Record_Student) {
            return;
        }

        $doctrineRecord->hasColumn('emergency_contact', 'string', 255, array(
            'type' => 'string',
            'length' => 255,
            'fixed' => false,
            'unsigned' => false,
            'primary' => false,
            'default' => '',
            'notnull' => true,
            'autoincrement' => false,
        ));
        $doctrineRecord->hasColumn('emergency_phone', 'string', 255, array(
            'type' => 'string',
            'length' => 255,
            'fixed' => false,
            'unsigned' => false,
            'primary' => false,
            'default' => '',
            'notnull' => true,
            'autoincrement' => false,
        ));
    }
}
