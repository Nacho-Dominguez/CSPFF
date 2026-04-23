<?php

class Controller_Administrator_ViewFunds extends Controller
{
    public function executeTask()
    {
        $user_id = A25_DI::UserId();
        if ($user_id < 1) {
            exit("You need to be logged in to view this.");
        }
        if (!A25_DI::User()->isAdminOrHigher()) {
            exit('Sorry, your account is not allowed to access this page.');
        }
        $this->displayFunds();
    }

    private function displayFunds()
    {
        $head = A25_DI::HtmlHead();
        $head->append('
        <style type="text/css" media="all">
            .main {
            max-width: 500px;
        }
        </style>');
        $q = Doctrine_Query::create()->from('A25_Record_Fund')->orderBy('name');
        $funds = $q->execute();

        echo '<div style="float: left;"><h2>Funds</h2></div>';
        echo '<div style="float: right;">';
        echo A25_Buttons::toolbarWithUnassumingUrl('New', 'edit-fund', 'new_f2.png');

        echo '</div><table class="adminform" style="max-width: 500px;">';
        echo '<tr><th>Fund Name</th><th>Active?</th><th></th></tr>';
        foreach ($funds as $fund) {
            $edit = A25_Link::to('/administrator/EditFund?id=' . $fund->fund_id);
            if ($fund->is_active) {
                $active = 'Yes';
            } else {
                $active = 'No';
            }
            echo '<tr><td>' . $fund->name . '</td><td>'
                . $active . '</td><td><a href="' . $edit
                . '">Edit</a></td></tr>';
        }
        echo '</table>';
    }
}
