<?php
abstract class PlatformConfigState extends PlatformConfigAbstract
{
    const isAState = true;

    const accountPath = '/account';

    public function contactUrl()
    {
        return A25_Link::to(self::contactPath);
    }
    /**
     * @todo-soon - re-design this so that we don't have to hard-code "aliveat25.us",
     * and so that each ADOD PlatformConfig file doesn't have to override this
     * function.  This could be done as part of Issue #154.
     */
    public function accountUrlDirect()
    {
        return 'https://aliveat25.us/' . PlatformConfig::STATE_ABBREV
          . PlatformConfigAbstract::accountPath;
    }
    public function findACourseUrl()
    {
        return A25_Link::to(self::findACoursePath);
    }
    public function programInfoUrl()
    {
        return A25_Link::to(self::programInfoPath);
    }
}
