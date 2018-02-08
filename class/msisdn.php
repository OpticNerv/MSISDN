<?php
class Msisdn
{
	private static $msisdn;
	public static $countries;
	
	public function __construct() 
	{
	}
	
	public static function loadData()
	{
		if(file_exists("./data/countries.json"))
		{
			try
			{
				self::$countries = json_decode(file_get_contents("./data/countries.json"));
			}
			catch(Exception $e) 
			{ 
				echo "Invalid JSON structure!";
				header("HTTP/1.0 500 Internal Server Error"); 
			}
		}
		else
		{
			echo "Missing data file!";
			header("HTTP/1.0 500 Internal Server Error");
		}
	}
	
	public static function search($searchString)
	{
		$result = new StdClass();
		$result->success = false;
		self::loadData();

		if(strlen($searchString)>0 && isset(self::$countries))
		{
			$result->success = true;
			foreach(self::$countries as $country)
			{
			
				if(strlen($searchString)>=strlen($country->country_code) && substr($searchString,0,strlen($country->country_code)) == $country->country_code)
				{
					$result->country_code = $country->country_code;
					$result->iso31662 = $country->iso31662;
					$result->country_name = $country->country_name;
					
					foreach($country->carriers as $carrier)
					{
						if(strlen($searchString)>=strlen($carrier->call_number) && substr($searchString,0,strlen($carrier->call_number)) == $carrier->call_number)
						{
							$result->carrier_call_number = $carrier->call_number;
							$result->carrier_mno = $carrier->mno;
							$result->subscriber_number = substr($searchString,strlen($carrier->call_number));
							return json_encode($result);
						}
					}
				}
			}
		}
		else
			$result->success = false;
			
		return json_encode($result);	
	}
	
	/*function parseTxtToJSON()
	{

		$countries = json_decode(file_get_contents('data/countries.json'));
		foreach($countries as $country)
		{
			$handle2 = null;
			if(strpos($country->country_code,"-")!==false && file_exists("data/carriers/".substr($country->country_code,0,strpos($country->country_code,"-")).".txt"))
				fopen("data/carriers/".substr($country->country_code,0,strpos($country->country_code,"-")).".txt","r");
			else if(strpos($country->country_code,"-")===false && file_exists("data/carriers/".$country->country_code.".txt"))
				$handle2 = fopen("data/carriers/".$country->country_code.".txt","r");
			
			if(isset($handle2))
			{
				$carriers_list = array();
				while (($line = fgets($handle2)) !== false) 
				{
					if(strpos($line,"#")===false && strpos($line,"|")!==false)
					{
						$tmp_output = explode("|",$line);
						if(isset($tmp_output) && is_array($tmp_output) && count($tmp_output) == 2)
						{
							$obj = new StdClass();
							$obj->call_number = $tmp_output[0];
							$tmp_output[1] = rtrim($tmp_output[1], " ");
							$tmp_output[1] = rtrim($tmp_output[1], "\r\n");
							$obj->mno = $tmp_output[1];
							array_push($carriers_list,$obj);
						}
					}

				}
				fclose($handle2);
				$country->carriers = $carriers_list;
			}
			else
				$country->carriers = array();
		}
		
		$fp = fopen('carriers.json', 'w');
		fwrite($fp, json_encode($countries));
		fclose($fp);
		echo "done";
	}
	}*/
}

?>