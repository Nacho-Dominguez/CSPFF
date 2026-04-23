<?php

class A25_Record_ReasonType extends JosReasonType implements A25_ISelectable
{
	const reasonTypeId_CourtOrdered = 1;
	const reasonTypeId_ObtainEarlyPermit = 2;
	const reasonTypeId_ParentsRequired = 3;
	const reasonTypeId_Insurance = 4;
	const reasonTypeId_Other = 5;
	// By default, states don't have a "Pending Legal Matter" option.
	const reasonTypeId_PendingLegalMatter = PlatformConfig::reasonTypeId_PendingLegalMatter_number;

	public static function legalMatterList() {
    return array_merge(
      A25_DI::PlatformConfig()->courtOrderedReasonTypeList,
      A25_DI::PlatformConfig()->pendingLegalMatterReasonTypeList
    );
  }

	/**
	 * @param integer $id
	 * @return A25_Record_ReasonType
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_ReasonType')->find($id);
	}
	
	public function getSelectionName() {
		return $this->reason_name;
	}
}
