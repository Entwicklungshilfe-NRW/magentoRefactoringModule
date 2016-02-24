<?php

class Gls_Unibox_Test_Model_Unibox_ParserTest extends EcomDev_PHPUnit_Test_Case {
    public $fixture;

    public function setUp() {
        $this->fixture = Mage::getModel('glsbox/unibox_parser');
    }

    public function tearDown(){
    }

    public function testAddCheckDigit()
    {
        $result = $this->fixture->addCheckDigit(10);

        $this->assertEquals(
            $result,
            108
        );

        $result = $this->fixture->addCheckDigit(11);

        $this->assertEquals(
            $result,
            115
        );

        $result = $this->fixture->addCheckDigit(50717321053);

        $this->assertEquals(
            $result,
            507173210537
        );

    }

    public function testPreparePrintWithValidId() {
        //test printing function return value
        $result = $this->fixture->preparePrint(133);
        $this->assertTrue(is_object($result));
    }

    public function testPreparePrintWithInvalidId() {
        $result = $this->fixture->preparePrint(666);
        $this->assertFalse($result);
    }

public function testPreparePrintWithString() {
        $result = $this->fixture->preparePrint("testify");
        $this->assertFalse($result);
    }

}