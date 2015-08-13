<?php

class Gls_Unibox_Test_Model_Unibox_ParserTest extends EcomDev_PHPUnit_Test_Case {

    public function testAddCheckDigit()
    {
        $number = 1234;
        $_digitArray = str_split($number);
        $_digitArray = array_reverse($_digitArray);
        $sum = 0;
        foreach($_digitArray as $key => $value){
            if($key%2 == 0){
                $sum += 3*$value;
            }else{
                $sum += $value;
            }
        }
        $diff = (int)(ceil($sum/10)*10)-($sum+1);
        if($diff == 10){
            $returnVal = 0;
        }else{
            $returnVal = $number.$diff;
        }

        $this->assertEquals(
            $diff,
            7
        );
    }

}