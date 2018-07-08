<?php

namespace App\Traits;

trait Monetary
{
    private $locale = 'en-SA';

    private $currencySymbol = '';

    /**
     * Returns the formatter.
     *
     * @return \NumberFormatter
     */
    public function getFormatter()
    {
        $formatter = new \NumberFormatter($this->locale, \NumberFormatter::CURRENCY);
        $formatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL , $this->currencySymbol);

        return $formatter;
    }

    public function setLocale(string $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function setCurrencySymbol(string $symbol)
    {
        return $this->currencySymbol = $symbol;
    }
}
