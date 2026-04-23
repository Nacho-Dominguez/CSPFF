<?php

class A25_FirefoxPrintWarning
{
  public static function run()
  {
    A25_DI::HtmlHead()->includeJqueryCookie();
    A25_DI::HtmlHead()->append('
      <script type="text/javascript">
        navigator.sayswho= (function(){
          var N= navigator.appName, ua= navigator.userAgent, tem;
          var M= ua.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);
          if(M && (tem= ua.match(/version\/([\.\d]+)/i))!= null) M[2]= tem[1];
          M= M? [M[1], M[2]]: [N, navigator.appVersion, "-?"];

          return M;
        })();

        jQuery.noConflict();

        jQuery(function($) {
          $(".cert_button").click(function() {
            if (navigator.sayswho[0] == "Firefox" && navigator.sayswho[1] >= 19 && !$.cookie("firefox_print_warning"))
            {
              alert("It looks like you\'re using Firefox 19 or higher.  Some people have had issues printing certificates with Firefox 19 or higher.  If you have trouble, you may want to use a different browser such as Internet Explorer or Google Chrome.");
              $.cookie("firefox_print_warning", "shown", { expires: 31 });
            }
          });
        });
      </script>
    ');
  }
}
