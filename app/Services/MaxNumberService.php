<?php

namespace App\Services;

use App\Models\MaxNumber;

class MaxNumberService
{
    protected $prefix;

    protected $initial;

    /**
     * MaxNumberService constructor.
     *
     * @param string $prefix
     * @param int $initial
     */
    public function __construct(string $prefix, int $initial=1000)
    {
        $this->prefix = $prefix;
        $this->initial = $initial;
    }

    /**
     *
     * @param string $prefix
     * @param int $initial
     * @return \App\Services\MaxNumberService
     */
    static public function new(string $prefix, int $initial=1000)
    {
        return new MaxNumberService($prefix, $initial);
    }

    public function saveNewNumber(): MaxNumber
    {
        if(MaxNumber::where('name', $this->prefix)->exists()) {
            $maxNumber = MaxNumber::where('name', $this->prefix)
                ->lockForUpdate()->first();
            $maxNumber->value = ++$maxNumber->value;
            $maxNumber->save();
        } else {
            $maxNumber = MaxNumber::create([
                'name' => $this->prefix,
                'value' => $this->initial,
            ]);
        }

        return $maxNumber;
    }
}
