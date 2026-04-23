<?php

class A25_Report_Fee extends A25_Report
{
  protected $isLegacy = false;

  public function __construct($limit, $offset)
  {
    parent::__construct(null, $limit, $offset);

    $this->filters = array(
      new A25_Filter_CourseDate(),
      new A25_Filter_FeeDate(),
      new A25_Filter_PayStatus(),
      new A25_Filter_FeeType()
    );
  }

  protected function name()
  {
    return "Fee Revenue";
  }

  protected function formatRow(A25_DoctrineRecord $orderItem)
  {
    $formatter = new RowFormatter4FeeReport($orderItem);
    return $formatter->formatRow();
  }

  protected function query()
  {
    return Doctrine_Query::create()
      ->from('A25_Record_OrderItem i')
      ->innerJoin('i.Order o')
      ->innerJoin('o.Enrollment e')
      ->innerJoin('e.Course c')
      ->andWhere('i.calc_is_active = ?', true);
  }
}

class RowFormatter4FeeReport extends A25_RowFormatter4FeeReports
{
  public function formatRow()
  {
    return array(
      'Order Item ID' => $this->orderItem->item_id,
      'Type' => $this->orderItem->getTypeName(),
      'Student ID' => $this->studentLink(),
      'Enrollment ID' => $this->enrollLink(),
      'Course ID' => $this->courseLink(),
      'Amount' => $this->orderItem->chargeAmount(),
      'Created' => A25_Functions::stringToDate($this->orderItem->created),
      'Course Date' => $this->courseDate(),
      'Payment Status' => $this->payStatus(),
      'Payment Date' => $this->orderItem->date_paid,
    );
  }
}
