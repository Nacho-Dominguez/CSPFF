<?php

class A25_PaymentTimer
{
  private $kick_out_time;
  private $enroll;
  
  public function __construct(A25_Record_Enroll $enroll)
  {
    $this->enroll = $enroll;
    $this->kick_out_time = strtotime($enroll->kick_out_date);
  }
  
  public function insert()
  {
    if ($this->kick_out_time != null
        && $this->kick_out_time < strtotime(A25_DI::PlatformConfig()->kickOutAfterDeadline . '+ 30 minutes')
        && $this->enroll->status_id == A25_Record_Enroll::statusId_registered)
      return $this->timerHtml();
  }
  
  protected function timerHtml()
  {
    ob_start();
    $head = A25_DI::HtmlHead();
    $head->includeJquery();
    ob_start();
    ?>

    <script type="text/javascript">
    var kick_out_time = <?php echo $this->kick_out_time ?>;
    var current_time = <?php echo time()?>;
    
    var seconds_left = (kick_out_time - current_time) + 2; // Add 2 seconds to avoid overlap of redirecting when not quite being time to kick out yet.

    setInterval(timer, 1000); //1000 will  run it every 1 second

    function timer()
    {
      seconds_left=seconds_left-1;
      
      var hours = Math.floor(seconds_left / 3600);
      var minutes = Math.floor((seconds_left % (3600)) / 60);
      var seconds = seconds_left - (hours * 3600) - (minutes * 60);
      minutes += '';  // Convert minutes to string
      seconds += '';  // Convert seconds to string
      while (seconds.length < 2) {
        seconds = '0' + seconds;
      }
      
      formatted = minutes + ':' + seconds;
      
      $("#time_left").text(formatted);
      
      if (seconds_left <= 0)
      {
         clearInterval(seconds_left);
         window.location = '<?php echo A25_Link::to('/account');?>';
         return;
      }

      //Do code for showing the number of seconds here
    }
    </script>
    
    <?php
    $head->append(ob_get_clean());
    
    ?>
    <div style="margin-bottom: 12px; padding: 14px; background-color: #FFEFAF; color: #333;">
      <span id="time_left" style="font-weight: bold;"><?php echo date('i:s', $this->kick_out_time - time() + 2); ?></span> left to pay before seat reservation expires.
    </div>
    <?php
    return ob_get_clean();
  }
}
