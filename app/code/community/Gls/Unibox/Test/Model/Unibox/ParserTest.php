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

    public function testPreparePrintInvalidId() {
        $result = $this->fixture->preparePrint(666);
        $this->assertFalse($result);
    }

    public function testPreparePrint() {

        //test printing function return value
        $result = $this->fixture->preparePrint(133);
        $this->assertEquals(64,count($result->getItems()));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #integer.*#
     */
    public function testPreparePrintNoInteger() {
        $result = $this->fixture->preparePrint('testify');
        $this->assertTrue(true);
    }


    /**
     * $id = 133
     * shipment_id/14
     * $tags = array([...],400 => 507173210520, 821 => DE, 206 => EBP,[...]);
     * return value instance of Varien_Data_Collection
     */
}