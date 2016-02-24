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

    public function testPreparePrintByValidId() {
        //test printing function return value
        $result = $this->fixture->preparePrint(133);
        $this->assertEquals(64,count($result->getItems()));
    }

    public function testPreparePrintByInvalidId(){
        $result = $this->fixture->preparePrint(666);
        $this->assertFalse($result);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage this fucking value is not a fucking number
     */
    public function testPreparePrintByNotInt() {
        $result = $this->fixture->preparePrint('Roland');
    }

    public function testPreparePrintByValidService() {
        $result = $this->fixture->preparePrint('cash');
        $this->assertFalse($result);

    }

    /**
     * $id = 133
     * shipment_id/14
     * $tags = array([...],400 => 507173210520, 821 => DE, 206 => EBP,[...]);
     * return value instance of Varien_Data_Collection
     */
}