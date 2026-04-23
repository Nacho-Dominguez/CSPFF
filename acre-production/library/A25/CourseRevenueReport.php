<?php
require_once(dirname(__FILE__) . '/../../administrator/components/com_stats/admin.stats.html.php');
require_once(dirname(__FILE__) . '/../../administrator/components/com_stats/stats.class.php');

class A25_CourseRevenueReport {
	/**
	 * I think $option can simply be 'com_stats', instead of being a parameter.
	 *
	 * @param <type> $filter
	 * @param <type> $option
	 * @param <type> $mainframe
	 * @param <type> $mosConfig_list_limit
	 * @param <type> $locs
	 */
    public static function run( $filter, $option, $mainframe,
			$mosConfig_list_limit, $locs) {
		$limit = intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 ) );

		$stats = new courseStats( $filter, $locs );
		$stats->count(A25_DI::DB());

		if ($stats->total < $limitstart) { $limitstart = 0; }
		require_once( dirname(__FILE__).'/../../administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav( $stats->total, $limitstart, $limit );

		$stats->summary(A25_DI::DB());
		$stats->load(A25_DI::DB(), 'data', $pageNav);

		$lists = array();

		HTML_stats::courseStats( $stats, $lists, $pageNav, $limit, $offset);
	}
}
?>
