<?php
/**
 * @todo-scopeAndMakeIssue - Move the constants and getTypeName() into the
 * resepctive OrderItem subclasses, such as OrderItem_Tuition.  If possible, get
 * rid of this class altogether, but that will depend on if getSelectionName()
 * can also be moved (or if it is unused). I'm not sure at this point.
 */
class A25_Record_OrderItemType extends JosOrderItemType implements A25_ISelectable
{
	const typeId_CourseFee = 1;
	const typeId_LateFee = 2;
	const typeId_ReplaceCertFee = 3;
	const typeId_ReturnCheckFee = 4;
	const typeId_NonrefundableBecauseOfNoShows = 5;
	const typeId_CreditCardFee = 6;
	const typeId_CourtSurcharge = 7;
	const typeId_MoneyOrderDiscount = 8;
    const typeId_ExpiredPayment = 9;
    const typeId_Donation = 10;
    const typeId_VirtualCourseFee = 11;

	public static function getTypeName($type_id)
	{
		switch($type_id)
		{
			case A25_Record_OrderItemType::typeId_CourseFee:
				return 'Tuition';

			case A25_Record_OrderItemType::typeId_CourtSurcharge:
				return 'Court Surcharge';

			case A25_Record_OrderItemType::typeId_CreditCardFee:
				return 'Credit Card Processing Fee';

			case A25_Record_OrderItemType::typeId_LateFee:
				return 'Late Fee';

			case A25_Record_OrderItemType::typeId_MoneyOrderDiscount:
				return 'Money Order Discount';

			case A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows:
				return 'Supplemental Course Fee';

			case A25_Record_OrderItemType::typeId_ReplaceCertFee:
				return 'Replacement Certificate';

			case A25_Record_OrderItemType::typeId_ReturnCheckFee:
				return 'Returned Check Fee';
        
            case A25_Record_OrderItemType::typeId_ExpiredPayment:
                return 'Expired Payment';

            case A25_Record_OrderItemType::typeId_Donation:
                return 'Donation';
                
            case A25_Record_OrderItemType::typeId_VirtualCourseFee:
                return 'Virtual Course Fee';

			default:
				return '';
		}
	}

	public function getName()
	{
		return $this->type_name;
    }
	
	public function getSelectionName()
	{
		return $this->getName();
	}
}
