<?php

class A25_EmailContent_Survey extends A25_EmailContent
{
  private $enroll_id;
  
  public function __construct($enroll_id)
  {
    $this->enroll_id = $enroll_id;
  }
  public function innerHtml()
  {
    ?><p><b>Alive at 25 Applicants,</b></p>
    <p>
    <b>Greetings from Alive At 25!</b> Our records show that you have completed
    our course either by yourself or enrolled the course for a young driver. We
    are attempting to gather some information from both parties. Your help
    through the link below of the Alive at 25 program will help determine and
    assess the experience enrollees have had.
    </p><p>
    Your responses are <u>completely confidential</u> and will only be used for
    our assessment analysis. Your name will not be identified to your answers in
    any way. Also, this study is <u>completely voluntary</u>. However, you can
    help us very much by taking a few minutes to share your experiences and
    opinions about the program. Please note <u>you must be at least 18 years of
    age</u> to take the assessment.
    </p><p>
    As a small token of appreciation, we will randomly select a participant each
    month to receive a <b>$25 Amazon online gift card</b> as a way of saying
    thank you.
    </p><p>
    If you have any questions or comments about this study, we would be happy to
    talk with you. Feel free to contact us through the information below.
    </p><p>
    Thank you very much for helping us save lives through this important study!
    </p><p>
    <b>Please click the link below to complete this short assessment:</b>
    </p><p>
    <a href="<?php echo ServerConfig::staticHttpsUrl() . 'survey?id=' . $this->enroll_id ?>">
      Take the survey</a>
    </p><p>
    Sincerely,
    </p><p>
    <i>The Alive at 25 Staff and CU Denver Student Research Team<br/>
    Colorado State Patrol Family Foundation<br/>
    55 Wadsworth Boulevard<br/>
    Lakewood, CO 80226<br/>
    <?php echo PlatformConfig::phoneNumber ?> / info@cspff.net<br/>
    www.coloradosafedriver.org</i>
    </p><?php;
  }
  
  public function subject()
  {
    return 'Alive at 25 Feedback';
  }
}
