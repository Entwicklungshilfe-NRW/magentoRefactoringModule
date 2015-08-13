<?php
/**
 * This is a file with php code.
 *
 * @package WMDB
 * @author  Roland Golla <roland.golla@wmdb.de>
 */

class Gls_Unibox_Model_Unibox_LeapYear {
    public function validate($year) {
        $result = ($year % 4) == 0;
        if ($year % 100 == 0) {
            $result = ($year % 400 == 0);
        }

        return $result;
    }
}