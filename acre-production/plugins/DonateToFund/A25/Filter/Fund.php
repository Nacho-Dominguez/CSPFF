<?php

class A25_Filter_Fund extends A25_Filter
{
    /**
     * @var array
     */
    protected $fund_ids;

    public function modifyQuery(Doctrine_Query $q)
    {
        if ($this->fund_ids) {
            $q->whereIn('d.fund_id', $this->fund_ids);
        }
        return $q;
    }

    protected function title()
    {
        return 'Fund';
    }

    protected function field()
    {
        return $this->generateMultiSelect('fund_ids', 'A25_Record_Fund', 'name');
    }
}
