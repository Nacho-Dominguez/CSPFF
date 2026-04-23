<?php

class A25_Plugin_SecondaryContactName implements
    A25_ListenerI_Doctrine,
    A25_ListenerI_ContactInfo
{
    public function studentFormContactInfo()
    {
        echo '<tr>
    <td class="formlabel">
        <label for="secondary_contact">Secondary Contact Name:</label>
    </td>
    <td>
        <input type="text" name="secondary_contact" id="secondary_contact"
            size="30" maxlength="80" class="inputbox"/>
    </td>
</tr>';
    }

    public function adminFormContactInfo(A25_Form_Record_Student $form)
    {
        $secondary_contact = new A25_Form_Element_Text('secondary_contact');
        $secondary_contact->setRequired(false)
            ->setLabel('Secondary Contact Name');
        $form->addElement($secondary_contact);
    }

    public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
    {
        if (!$doctrineRecord instanceof A25_Record_Student) {
            return;
        }

        $doctrineRecord->hasColumn('secondary_contact', 'string', 255, array(
            'type' => 'string',
            'length' => 255,
            'fixed' => false,
            'unsigned' => false,
            'primary' => false,
            'default' => '',
            'notnull' => false,
            'autoincrement' => false,
        ));
    }
}
