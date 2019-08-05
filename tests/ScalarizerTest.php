<?php
/**
 * Datetime: 05.08.2019 11:48
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace XAKEPEHOK\Scalarizer;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use XAKEPEHOK\Scalarizer\Stubs\AllStub;
use XAKEPEHOK\Scalarizer\Stubs\JsonStub;
use XAKEPEHOK\Scalarizer\Stubs\ReflectionManyStub;
use XAKEPEHOK\Scalarizer\Stubs\ReflectionStub;
use XAKEPEHOK\Scalarizer\Stubs\ToStringStub;

class ScalarizerTest extends TestCase
{

    private $scalarizer;

    protected function setUp()
    {
        parent::setUp();
        $this->scalarizer = new Scalarizer(
            [
                DateTimeInterface::class => function (DateTimeInterface $value) {
                    return $value->format('Y-m-d H:i:s');
                },
            ],
            true
        );
    }

    /**
     * @dataProvider scalarizeProvider
     * @param $expected
     * @param $value
     */
    public function testScalarize($expected, $value)
    {
        $this->assertEquals($expected, $this->scalarizer->scalarize($value));
    }

    /**
     * @dataProvider scalarizeExceptionProvider
     * @param $code
     * @param $value
     */
    public function testScalarizeException($code, $value)
    {
        $this->expectException(ScalarizerException::class);
        $this->expectExceptionCode($code);
        $this->scalarizer->scalarize($value);
    }

    public function scalarizeProvider()
    {
        return [
            [null, null],
            [true, true],
            [1, 1],
            ['hello', 'hello'],
            ['2019-08-05 11:53:23', new DateTime('2019-08-05 11:53:23')],
            ['2019-08-05 11:53:23', new DateTimeImmutable('2019-08-05 11:53:23')],
            [123456, new JsonStub(123456)],
            ['hello world', new ToStringStub('hello world')],
            ['hello world', new ReflectionStub('hello world')],
            ['hello world', new AllStub('hello world')],
        ];
    }

    public function scalarizeExceptionProvider()
    {
        return [
            [1, []],
            [1, fopen('http://yandex.ru', 'r')],
            [3, new ReflectionManyStub(1, 2)],
        ];
    }




}
