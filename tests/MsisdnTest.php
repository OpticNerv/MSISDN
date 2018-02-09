<?php
/*@author    Jure Škorc <jure|skorc|gmail|com>*/

use PHPUnit\Framework\TestCase;
require_once ('./class/Msisdn.php');

final class MsisdnTest  extends TestCase
{
	private $msisdn;
	
	/*Sets up the instance of the Msisdn class.*/
	protected function setUp()
    {
        $this->msisdn = new Msisdn();
    }
    
	/*Tests for invalid number format, return false*/
	public function testInvalidNumberFormat()
	{
    	$obj = new StdClass();
		$obj->success = false;
		
		$result = $this->msisdn->search("žaba");
        $this->assertEquals(json_encode($obj), $result);
    }
	
	/*Tests for valid response, which is an object with both country and MNO data.*/
	public function testValidResponse()
	{
		$obj = new StdClass();
		$obj->success = true;
		$obj->country_code ="66";
		$obj->iso31662 = "TH";
		$obj->country_name = "Thailand";
		$obj->carrier_call_number = "6666";
		$obj->carrier_mno = "DTAC";
		$obj->subscriber_number = "66666";
	
        $result = $this->msisdn->search("666666666");
        $this->assertEquals(json_encode($obj), $result);
	}
	
	/*Tests for a partial, country only match. MNO is not found.*/
	public function testCarrierNotFound()
    {
		$obj = new StdClass();
		$obj->success = true;
		$obj->country_code ="66";
		$obj->iso31662 = "TH";
		$obj->country_name = "Thailand";
	
        $result = $this->msisdn->search("6612321312");
        $this->assertEquals(json_encode($obj), $result);
    }
}
