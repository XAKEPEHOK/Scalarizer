<?php
/**
 * Created for Scalarizer
 * Datetime: 05.08.2019 11:54
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace XAKEPEHOK\Scalarizer\Stubs;


use JsonSerializable;

class JsonStub implements JsonSerializable
{

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->value;
    }
}