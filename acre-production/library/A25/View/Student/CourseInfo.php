<?php

// Duplication with A25_View_Student_Account
class A25_View_Student_CourseInfo extends A25_View_Student
{
  private $course;

  public function __construct($course)
  {
    $this->course = $course;
  }

  public function render()
  {
    $head = A25_DI::HtmlHead();
    $head->append('
      <style type="text/css">
        .account-block {
          padding: 15px;
          background-color: white;
        }
        a.action_link {
          padding: 6px;
          border: 1px solid black;
          font-weight: bold;
          display: block;
          background: none;
          background-color: #efefff;
          border-radius: 5px;
          text-decoration: none;
          text-align: center;
        }
        a.action_link:hover {
          background-color: #dfdfff;
        }
        p {
          margin: 12px 0px 12px 0px;
        }
        br {
          clear: none
        }
        h2, h1 {
          color: #232;
        }
        .payment_due_info {
        float: right;
        max-width: 320px;
        padding: 24px;
        color: #333;
        }
        @media (max-width: 700px) {
          .payment_due_info {
          float: left;
          padding: 0px;
          }
        }
      </style>');
    $tooltip = new A25_Include_Tooltip();
    $tooltip->load();
    ?>
    <h1 style="margin: 15px; font-size: 28px;">
        <?php if ($this->course->course_type_id == A25_Record_Course::typeId_Spanish) {
            echo 'Informaci&oacute;n del curso';
        }
        else {
            echo 'Course Info';
        } ?>
        </h1>
		<div style="margin:15px;">
    <div style="clear: both; margin-top: 18px;">
    <div class="account-block" style="border: 1px solid #769E3B;">
    <?php
    if ($this->course) {
        if ($this->course->status_id != 1) {
            echo 'Sorry, this course is not open for enrollment. Would you like to <a href="' . PlatformConfig::findACoursePath . '">view all upcoming courses</a>?';
        }
        else {
            echo $this->paymentDueInfo();
            echo A25_Html::courseMessage($this->course, true);
            echo $this->certificateMessage($this->course);
        }
    }
    else {
      echo 'Sorry, that course does not exist. Would you like to <a href="' . PlatformConfig::findACoursePath . '">view all upcoming courses</a>?';
    }
    ?>
    </div>
    </div>
    </div>
    <?php
  }
  
    protected function certificateMessage($course)
    {
        if ($course->Location->virtual == true) {
            if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
                return A25_DI::PlatformConfig()->courseInfoCertificateMessageVirtualSpanish;
            }
            return A25_DI::PlatformConfig()->courseInfoCertificateMessageVirtual;
        }
        if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            return A25_DI::PlatformConfig()->courseInfoCertificateMessageSpanish;
        }
        return A25_DI::PlatformConfig()->courseInfoCertificateMessage;
    }

  private function paymentDueInfo()
  {
    ?>
    <div class="payment_due_info">
    <?php
    echo '<a class="action_link" style="float: right; font-size: 20px;
        margin-bottom: 12px;" href="' . $this->enrollLink() . '">';
    if ($this->course->course_type_id == A25_Record_Course::typeId_Spanish) {
        echo 'Inscribirse';
    }
    else {
        echo 'Enroll';
    }
    echo '</a>';
        $display = A25_DI::PlatformConfig()->displayTuitionOnCourseInfo();
        if ($display) {
            echo '<div style="font-size: 20px; padding-top: 6px;">';
        if ($this->course->course_type_id == A25_Record_Course::typeId_Spanish) {
            echo 'Matr&iacute;cula: ';
        }
        else {
            echo 'Tuition: ';
        }
            echo $this->tuition();
            echo $this->tooltip() . '</div>';
        }
    echo $this->paymentOptions() . '</div>';
  }

  private function tooltip()
  {
    $text = htmlspecialchars(A25_DI::PlatformConfig()->tuitionDetails('$' . intval($this->course->getSetting('fee'), 0)));
    if ($text) {
      return '*<br /><a href="javascript:void()" rel="tooltip" title="' . $text
          . '"style="font-size: 10px;">* Additional fees or discounts may apply</a>';
    }
  }

  private function tuition()
  {
    $tuition = A25_DI::PlatformConfig()->displayedTuitionOnCourseInfo($this->course);
    if ($tuition === 0)
      return 'Free';
    return '$' . $tuition;
  }

  // Duplication with A25_Listing_BrowseCourses->enrollLink()
  private function enrollLink()
  {
		$link = $this->fireEnrollLink($this->course);
		if (!$link)
			$link = A25_Link::to(
					'/component/option,com_course/task,confirm/course_id,' . $this->course->course_id . '/Itemid,19/');
    return $link;
  }

  protected function paymentOptions()
  {
      if (!A25_DI::PlatformConfig()->acceptCreditCards) {
          return '<div style="clear: both;"><p>Pay with money order</p></div>';
      }
    $return = '<div style="clear: both;"><p>';
    if ($this->course->course_type_id == A25_Record_Course::typeId_Spanish) {
        echo 'Pagar con ';
    }
    else {
        echo 'Pay with ';
    }
    $return .= A25_DI::PlatformConfig()->acceptedCards;
    if (!$this->course->isPastPaymentOptionDeadline()) {
      if (A25_DI::PlatformConfig()->acceptChecks) {
        $return .= ', check,';
      }
      $return .= ' or money order';
    }
    $return .= '</p></div>';
    return $return;
  }

  // Duplication with A25_Listing_BrowseCourses->fireEnrollLink()
	protected static function fireEnrollLink(A25_Record_Course $course)
	{
		$link = false;
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_BrowseCourses) {
				$link = $listener->enrollLink($course);
			}
		}
		return $link;
	}
}
