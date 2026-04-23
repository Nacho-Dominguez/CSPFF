<?php
class A25_Plugin_OnlinePrerequisites implements
    A25_ListenerI_Doctrine,
    A25_ListenerI_OnlineCourseAccount
{
    public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
    {
        if (!$doctrineRecord instanceof A25_Record_Enroll) {
            return;
        }

        $doctrineRecord->hasColumn('prerequisites_completed', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             ));
    }
    
    public function beforeCourseButton(A25_Record_Enroll $enroll) {
        if ($enroll->prerequisites_completed == 0) {
            if ($enroll->Course->course_type_id == A25_Record_Course::typeId_Spanish) {
                return '<p>Paso 1: Lea el <a href="images/resources/0507s-cobert-safety-brochure-SPAN.pdf">folleto de seguridad del conductor</a></p>'
                . '<form method="get" action="complete-prerequisite">'
                . '<input type="hidden" name="xref_id" value="' . $enroll->xref_id . '" />'
                . '<input type="submit" style="font-size: 12px; margin-top: 10px;" value="He leido el folleto"/>';
            }
            else {
                return '<p>Step 1: Read the <a href="images/resources/0507s-cobert-safety-brochure-ENG.pdf">Driver Safety Brochure</a></p>'
                . '<form method="get" action="complete-prerequisite">'
                . '<input type="hidden" name="xref_id" value="' . $enroll->xref_id . '" />'
                . '<input type="submit" style="font-size: 12px; margin-top: 10px;" value="I\'ve read the brochure"/>';
            }
        }
        else if ($enroll->prerequisites_completed == 1) {
            if ($enroll->course_id == 1 || ($enroll->course_id == 14 && $enroll->Student->age() < 25)) {
                return '<p>Step 2: Watch the video</p>'
                . '<video width="640" height="480" controls>
    <source src="images/resources/AliveAt25VirtualClass.mp4" type="video/mp4">
    Your browser does not support the video tag.
    </video>'
                . '<form method="get" action="complete-prerequisite">'
                . '<input type="hidden" name="xref_id" value="' . $enroll->xref_id . '" />'
                . '<input type="submit" style="font-size: 12px; margin-top: 10px;" value="I\'ve watched the video"/>';
            }
//            else if ($enroll->course_id == 3) {
//                return '<p>Paso 2: ver el v&iacute;deo</p>'
//                . '<video width="640" height="480" controls>
//    <source src="images/resources/AliveAt25VirtualClass.mp4" type="video/mp4">
//    Your browser does not support the video tag.
//    </video>'
//                . '<form method="get" action="complete-prerequisite">'
//                . '<input type="hidden" name="xref_id" value="' . $enroll->xref_id . '" />'
//                . '<input type="submit" style="font-size: 12px; margin-top: 10px;" value="He visto el video"/>';
//            }
            else {
                return;
            }
        }
        else {
            return;
        }
    }
}
