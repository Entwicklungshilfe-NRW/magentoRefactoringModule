<?php

class Gls_Unibox_Test_Model_Unibox_ParserTest extends EcomDev_PHPUnit_Test_Case {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown(){
        parent::tearDown();
    }

    public function testAddCheckDigit()
    {
        $parser = Mage::getModel('glsbox/unibox_parser');
        $result = $parser->addCheckDigit(50717321053);

        $this->assertEquals(
            $result,
            507173210537
        );
    }

    public function testPreparePrint() {
        //Mocked Model
        //test Model instance bevor printing
        $glsShipment = Mage::getModel('glsbox/shipment')->load(133);

        $returnTag = $glsShipment->getGlsMessage();
        $this->assertContains('T090:NOPRINT|T050:GlsUniboxMagentoModule|T051:V 1.1.5.2.1.9.1.0|T8700:DE 500|T545:29.04.2015|T400:507173210520|T800:Absender|T805:52962|T810:Test|T811:Test 2|T820:Vitalisstrasse 96|T821:DE|T822:50827|T823:Koeln|T853:Bestellnummer.:|T854:200000011|T863:Sonnberg 22|T864:Leogang|T330:5771|T8914:276a15rHyC|T8915:2760204107|T920:DE Box DE - Europa Cash Service Retour|T859:Herr / Frau|T860:DE Box DE - Europa Cash Service Retour|T851:KD-Nr.:|T852:2|T203:C|T220:64,99|T221:EUR|T750:CASH-SERVICE|T752:Verwendungszweck:|T753:Bestellnummer: 200000011|T8904:1|T8905:1|T080:V87_10_0007|T520:28042015|T510:kl|T500:DE 500|T103:DE 500|T560:DE03|T8797:IBOXCUS|T540:29.04.2015|T541:20:28|T100:AT|CTRA2:AT|T210:|ARTNO:Standard|T530:1.00|T206:EBP|T207:COD|ALTZIP:5771|FLOCCODE:AT 391|TOURNO:1345|T320:1345|TOURTYPE:21102|SORT1:0|T310:0|T331:5771|T890:3901|ROUTENO:38506|ROUTE1:ANS|T110:ANS|FLOCNO:716|T101: 391|T105:AT|T300:04039104|NDI:|T8970:A|T8971:A|T8975:52962|T8980:CC|T8974:D|T8916:507173210520|T8950:Tour|T8951:ZipCode|T8952:Your GLS Track ID|T8953:Product|T8954:Service Code|T8955:Delivery address|T8956:Contact|T8958:Contact|T8957:Customer ID|T8959:Phone|T8960:Note|T8961:Parcel|T8962:Weight|T8965:Contact ID|T8913:ZDCYD5JW|T8972:ZDCYD5JW|T8902:ADE 500AT 3912760204107276a15rHyCZDCYD5JWCCD         0ANS13455771   00100001001000052962                                   |T8903:A¬DE Box DE - Europa Cash Service Retour¬Sonnberg 22¬Leogang¬¬200000011¬¬                                        |T102:AT 391|PRINTINFO:|PRINT1:|RESULT:E000:507173210520|T565:202802|PRINT0:xxGLSintermecpf4i.int01',$returnTag);

        $service = $glsShipment->getService();
        $this->assertEquals('cash',$service);

        //test printing function return value
        $result = Mage::getModel('glsbox/unibox_parser')->preparePrint($glsShipment->getId());
        $this->assertEquals(64,count($result->getItems()));
    }

    /**
     * $id = 133
     * shipment_id/14
     * $tags = array([...],400 => 507173210520, 821 => DE, 206 => EBP,[...]);
     * return value instance of Varien_Data_Collection
     */
}