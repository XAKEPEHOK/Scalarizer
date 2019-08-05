<?php
/**
 * Created for Scalarizer
 * Datetime: 05.08.2019 11:55
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace XAKEPEHOK\Scalarizer\Stubs;


class ToStringStub
{

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

}