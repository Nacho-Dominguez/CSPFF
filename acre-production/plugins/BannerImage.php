<?php

class A25_Plugin_BannerImage implements
    A25_ListenerI_BannerAd
{
    public function afterState()
    {
?>
<div style="clear: both"></div>
<img style="padding: 15px 8px 0px 8px; max-width: 100%;" src="<?php echo A25_Link::to('/images/KSDImage.png')?>" alt="Kentucky Safe Driver"/>
<?php
    }
}

