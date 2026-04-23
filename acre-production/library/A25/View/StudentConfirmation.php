<?php
class A25_View_StudentConfirmation
{
	public static function javascript()
	{
		?>
		// Makes the court_id select visible if this is a court referral
		function checkReason(elem) {
    if (<?php echo self::generateJavascriptOperatorsForReasonList(); ?>) {
				if ($('referringCourtForm').style.display == 'none') {
					new Effect.BlindDown('referringCourtForm', {duration: 0.2});
				}
				$('court_id').options[0].value = 'required';
		    } else {
				$('referringCourtForm').style.display = 'none';
				$('court_id').options[0].value = '';
				$('court_id').options[0].selected = true;
			}
		}

        // This will be triggered by the tmt:dependonreason attribute
		tmt_globalRules.dependonreason = function(fieldNode) {
            var isRequired = (document.register.reason_id.selectedIndex == 5);
            if(isRequired && $F(fieldNode) == '') {
                return false;
            }
            return true;
		}

		tmt_globalRules.dependoncourt = function(fieldNode) {
            var isRequired = (document.register.court_id.selectedIndex == 1);
            if(isRequired && $F(fieldNode) == '') {
                return false;
            }
            return true;
		}
		<?php
    self::fireDuringJavascript();
	}

	public static function courtSpecificFeatures($courtList, $course)
	{
		if (PlatformConfig::allowCourtReferrals) {
			?>
			<div class="row" id="referringCourtForm" style="display: none; margin-top: 8px;">
        <div class="col-sm-4"><label for="court_id">
        <?php if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            echo 'Tribunal de referencia';
        }
        else {
            echo 'Referring Court';
        } ?>:
          <img src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/images/required.gif"
          border="0" width="10" height="8" align="absmiddle" /></label>
        </div>
        <div class="col-sm-8">
          <span id="courtStateList"><?php echo $courtList; ?></span>
        </div>
			</div>
			<?php
      self::fireAfterCourtList();
		}
	}
  
  private static function generateJavascriptOperatorsForReasonList()
  {
    $reasonList = array();
    foreach(A25_Record_ReasonType::legalMatterList() as $reason) {
      $reasonList[] = '$F(elem) == ' . $reason;
    }
    $return .= implode(' || ', $reasonList);
    return $return;
  }
  
	private static function fireAfterCourtList()
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_StudentConfirmation)
        $body = $listener->afterCourtList();
    }
    return $body;
	}
  
	private static function fireDuringJavascript()
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_StudentConfirmation)
        $body = $listener->duringJavascript();
    }
    return $body;
	}
}
