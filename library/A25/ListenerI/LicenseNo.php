<?php

interface A25_ListenerI_LicenseNo
{
	public function getStudentLicenseNumber(A25_Record_Student $student);
	public function afterFiltersAdminListStudent($lists, $where,
			$database, $option);
	public function afterLicenseStateFilterAdminListStudentHtml(
			$filter_license_no);
	public function capitalizeLicenseNumber(A25_Record_Student $student);
}
