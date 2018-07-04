<?php

namespace App\Models;

use Scottlaurent\Accounting\Models\Ledger;

class Journal extends \Scottlaurent\Accounting\Models\Journal
{
    /**
     * @param Ledger $ledger
     * @return Journal
     */
    public function assignToLedger(Ledger $ledger)
    {
        $ledger->journals()->save($this);

        return $this;
    }
}
