<?php

namespace Tests\Unit\Currency\Converter;

use App\Domain\Currency\Converter\DynamicXmlToArray;
use PHPUnit\Framework\TestCase;

class DynamicXmlToArrayTest extends TestCase
{

    public function getXml()
    {
        return file_get_contents(__DIR__ . '/../../../data/dynamic.xml');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testArray()
    {
        $converter = new DynamicXmlToArray($this->getXml());
        $data = $converter->convert();
        $this->assertCount(8, $data);


        $this->assertContains([
            'nominal' => '1',
            'valuteID' =>'R01235',
            'date' => '02.03.2001',
            'value' => '28.6200',
        ], $data);
    }
}
