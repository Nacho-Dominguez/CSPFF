<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_HearAboutType extends A25_Filter
{
    protected $hear_about_ids;

    public function modifyQuery(Doctrine_Query $q)
    {
        if ($this->hear_about_ids) {
            $q->andWhereIn('e.hear_about_id', $this->hear_about_ids);
        }
        return $q;
    }

    protected function title()
    {
        return 'How they heard about us';
    }

    protected function field()
    {
        return $this->generateMultiSelect(
            'hear_about_ids',
            'A25_Record_HearAboutType',
            'hear_about_name'
        );
    }
}
