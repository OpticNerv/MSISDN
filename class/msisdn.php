<?php
/**
* Class used for parsing input strings and matching them agains Mobile Network Operator data 
* loaded from provided JSON files.
* @class	 Msisdn
* @author    Jure Škorc <jure.skorc@gmail.com>
*/
class Msisdn
{
	private static $msisdn;
	public static $countries;
	
	public function __construct() 
	{
	}
	
	/**
	* PHP Function loadData, load required json file with country carriers data.
	* @name: loadData
	**/
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
	
	/**
	* PHP Function search, searches in list of all available country call codes, if it finds the match
	* it also searches for Mobile Network Operator data for that country.
	* @name: search
	* @params: searchString, string to be usedi in search
	* @returns: JSON object with status true on false, based on whether it found a matching country call code.
	* It also return MNO data if match is made with its number.
	* It can also return a partial match, when it matches the country call code but no Mobile Network Operator is found.
	**/
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
}
?>