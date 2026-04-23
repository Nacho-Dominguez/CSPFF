<?php

namespace Acre\A25\Template;

$index = new StandardIndex(
    \A25_DI::PlatformConfig()->siteTemplateHeader(),
    new StandardFooter(new GoogleTracker())
);
echo $index->run($Itemid, $option, $task);
