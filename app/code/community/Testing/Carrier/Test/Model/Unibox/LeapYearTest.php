<?php
/**
 * This is a file with php code.
 *
 * @package WMDB
 * @author  Roland Golla <roland.golla@wmdb.de>
 */

class Testing_Carrier_Test_Model_Unibox_LeapYearTest extends EcomDev_PHPUnit_Test_Case {

    public $fixture;

    public function setup()
    {
        $this->fixture = Mage::getModel('glsbox/unibox_leapYear');
    }

    public function tearDown()
    {
        unset($this->fixture);
    }

    /**
     * @dataProvider provideNonLeapYears
     */
    public function testLeapYearValidateExpectingFalse($value) {
        $this->assertFalse($this->fixture->validate($value));
    }

    /**
     * @dataProvider provideLeapYears
     */
    public function testLeapYearValidateExpectingTrue($value) {
        $this->assertTrue($this->fixture->validate($value), "Msg:" . $value);
    }

    public static function provideNonLeapYears() {
        return [
            "2015" => [2015],
            "1000" => [ 1000 ]
        ];
    }

    public static function provideLeapYears() {
        return [
            [2016]
        ];
    }
}