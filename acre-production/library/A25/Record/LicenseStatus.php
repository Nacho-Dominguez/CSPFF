<?php

class A25_Record_LicenseStatus extends JosLicenseStatus
{
	const statusId_valid = 1;
	const statusId_suspended = 2;
	const statusId_conditionalProbation = 3;
	const statusId_cancelled = 4;
	const statusId_unlicensed = 5;
	const statusId_drivingPermit = 6;

	/**
	 * @param integer $id
	 * @return A25_Record_LicenseStatus
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_LicenseStatus')->find($id);
    }
}
?>
