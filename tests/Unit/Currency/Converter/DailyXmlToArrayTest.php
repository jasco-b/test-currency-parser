<?php

namespace Tests\Unit\Currency\Converter;

use App\Domain\Currency\Converter\DailyXmlToArray;
use PHPUnit\Framework\TestCase;

class DailyXmlToArrayTest extends TestCase
{
    public function getXml()
    {
        return file_get_contents(__DIR__ . '/../../../data/daily.xml');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testArray()
    {
        $converter = new DailyXmlToArray($this->getXml());
        $data = $converter->convert();

        $this->assertCount(3, $data);


        $this->assertContains([
            'valuteID' => 'R01010',
            'numCode' => '036',
            'charCode' => 'AUD',
            'value' => '42.3584',
            'nominal' => 1,
        ], $data);
    }
}
