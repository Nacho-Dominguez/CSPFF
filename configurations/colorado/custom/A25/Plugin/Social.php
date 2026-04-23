<?php

class A25_Plugin_Social implements A25_ListenerI_AppendFooterMenu
{
    public function appendFooterMenu()
    {
?>
<br />
Follow us:
<a href="https://www.facebook.com/pages/Alive-at-25-Colorado/274173152612077" target="_blank" class="noDots">
<img src="<?php echo A25_Link::to('/images/facebook_32.png')?>" border="0" alt="Facebook" width="32" /></a>
<a href="https://twitter.com/Alive_at_25" target="_blank" class="noDots">
<img src="<?php echo A25_Link::to('/images/twitter_32.png')?>" border="0" alt="Twitter" width="32" /></a>
<?php
    }
}
