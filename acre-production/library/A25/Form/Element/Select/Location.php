<?php

class A25_Form_Element_Select_Location extends A25_Form_Element_Select
{
    public function __construct($name, A25_Record_User $user)
    {
        parent::__construct($name);
        $this->addMultiOptions($this->getLocations($user));
    }
    /**
     * @param A25_Record_User $user
     * @return array
     */
    private function getLocations(A25_Record_User $user)
    {
        $locations = $this->getLocationRecords($user);

        $options["0"] = '-- None --';

        $options += A25_Form_Record::createSelectionList($locations);

        return $options;
    }

    private function getLocationRecords(A25_Record_User $user)
    {
        $locations = array();
        if ($user->isInstructor()) {
            $locations = $user->getLocations();
        } else {
            $locations = Doctrine_Query::create()->select()
                    ->from('A25_Record_Location')
                    ->where('parent <> 0')
                    ->andWhere('published = 1')
                    ->execute();
        }
        $locations = $this->simpleSort($locations);
        return $locations;
    }

    /**
     * Protected for testing
     */
    protected function simpleSort($locations)
    {
        $count = count($locations);
        for ($i=0; $i<$count; $i++) {
            for ($j=$i+1; $j<$count; $j++) {
                if (strcasecmp(
                    $locations[$j]->location_name,
                    $locations[$i]->location_name
                ) < 0) {
                    $temp = $locations[$i];
                    $locations[$i] = $locations[$j];
                    $locations[$j] = $temp;
                }
            }
            $location[$i] = $lowest;
        }
        return $locations;
    }
}
