<?php

class A25_Record_OrderItem_MoneyOrderDiscount extends A25_Record_OrderItem
{
  /**
   * Money order discounts are accrued as income based on:
   * - creation date
   * - enrollment status does NOT matter
   * This is not 100% accurate.  In reality, a money order discount should
   * usually be counted as income once the student actually attends a class,
   * even if it is a later enrollment.  However, that would just be too hard
   * to calculate for thousands of records at once.  This is probably okay
   * since nobody uses money order discounts any more.  If they were to
   * become common again, we might need to figure out a way to line it up as
   * income more accurately.
   */
  protected function accrualDateForActiveItem()
  {
    return A25_Functions::stringToDate($this->created);
  }
}
