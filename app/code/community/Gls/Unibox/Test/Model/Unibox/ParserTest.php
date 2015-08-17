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

    public function testPreparePrint() {
        //$result = $this->fixture->preparePrint(131);
        //$this->assertTrue(is_array($result->getItems()));

        $result = $this->fixture->preparePrint(132);
        $this->assertTrue(is_array($result->getItems()));

        $result = $this->fixture->preparePrint(133);
        $this->assertEquals(64,count($result->getItems()));
    }

    /**
     * @expectedException              \Exception
     * @expectedExceptionMessageRegExp #integer.*#
     */
    public function testPreparePrintException()
    {
        $result = $this->fixture->preparePrint('testify');
        $this->assertFalse($result);
    }

    /**
     * @expectedException              \Exception
     * @expectedExceptionMessageRegExp #666.*#
     */
    public function testPreparePrintIdException()
    {
        $result = $this->fixture->preparePrint(666);
        $this->assertFalse($result);
    }

    /**
     * $id = 133
     * shipment_id/14
     * $tags = array([...],400 => 507173210520, 821 => DE, 206 => EBP,[...]);
     * return value instance of Varien_Data_Collection
     */
}