<?php

class Gls_Unibox_Test_Model_Unibox_ParserTest extends EcomDev_PHPUnit_Test_Case {
    public $fixture;

    public function setUp() {
        $this->fixture = Mage::getModel('glsbox/unibox_parser');
    }

    public function tearDown(){
        unset($this->fixture);
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

    public function testPreparePrintForVadidId() {
        //test printing function return value
        $result = $this->fixture->preparePrint(133);
        $this->assertEquals(64,count($result->getItems()));
    }

    public function testPreparePrintForInvadidId() {
        //test printing function return value
        $result = $this->fixture->preparePrint(666);
        $this->assertEquals(64,count($result->getItems()));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #integer.*#
     */
    public function testPreparePrintForString() {
        $result = $this->fixture->preparePrint('testify');
        $this->assertEquals(64,count($result->getItems()));
    }

    /**
     * $id = 133
     * shipment_id/14
     * $tags = array([...],400 => 507173210520, 821 => DE, 206 => EBP,[...]);
     * return value instance of Varien_Data_Collection
     */
}