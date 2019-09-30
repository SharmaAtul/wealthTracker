<?

/*
IMPORTANT NOTE: always use the ds_midnight_timestamp() function when getting unix timestamps that return midnight. Ti]imestamps may have an hour added or subtracted 
relative to the current daylight/non-daylight savings unix time, hence returning an incorrect timestamp.
DO NOT use time() or mktime() functions directly when obtaining a midnight time value, us ds_midnight_timestamp() instead.
*/

$IS_DAYLIGHTSAVINGS = 0;

function ds_midnight_timestamp($time){
	// return the date timestamp adjusted for daylight savings
	
	//$is_in_daylight_savings = (int)date('I',$time);
	
	$timestamp = $time;
	$midnight_hour = (int)date('G',$timestamp);
	//echo "Midnight Hour=".$midnight_hour;

	if($midnight_hour == 1){
		// subtract the hour for daylight savings which has been added by the server
		$timestamp -= (60 * 60);
		//echo "DS_MIDNIGHT=".$midnight_hour."(".date('d/m/Y',$timestamp).")";
	} else if ($midnight_hour == 23){
		// add the hour for daylight savings which has been removed by the server
		$timestamp += (60 * 60);
		//echo "DS_MIDNIGHT=".$midnight_hour."(".date('d/m/Y',$timestamp).")";
	}

	return $timestamp;
}

function date_ds_format($date_format, $time){
	// return the date timestamp adjusted for daylight savings
	// This function negates the hour added or subtracted by the date() function for daylight savings
	
	//$is_in_daylight_savings = (int)date('I',$time);
	
	$timestamp = $time;
	if((int)date("I",$timestamp) == 1){
		$timestamp -= 60 * 60;  //subtract 1 hour that the date() formatting is about to add
	}

	return date($date_format, $timestamp);
}

function convert_datetime_to_timestamp($datetime, &$timestamp)
{
	if(preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", trim($datetime), $matches) == true)
	{
		$IS_DAYLIGHTSAVINGS = 0;
		$timestamp = mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1],$IS_DAYLIGHTSAVINGS);
		if($timestamp == -1)
		{
			return false;
		}
		else 
		{
			return true;
		}
	}
	else 
	{
		$timestamp = 0;
		return false; //$datetime;
	}
}

function convert_timestamp_to_datetime($timestamp)
{
	return date_ds_format('Y-m-d H:i:s', $timestamp);
}

function convert_timestamp_to_realdatetime($timestamp)
{
	return date_ds_format('d/m/Y H:i:s', $timestamp);
}


function convert_real_datetime_to_datetime($datetime)
{
	if(preg_match("/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})$/", trim($datetime), $matches) == true)
	{
		return "{$matches[3]}-{$matches[2]}-{$matches[1]} {$matches[4]}:{$matches[5]}:00";
	}
	else 
	{
		return '0000-00-00 00:00:00';
	}
	
}

function convert_datetime_to_real_datetime($datetime)
{
	
	$result = convert_datetime_to_timestamp($datetime, $timestamp);
	if( $result == true )
	{
		return date_ds_format('d/m/Y H:i', $timestamp);
	}
	else 
	{
		return '';
	}
	
	if(preg_match("/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})$/", trim($datetime), $matches) == true)
	{
		return "{$matches[3]}-{$matches[2]}-{$matches[1]} {$matches[4]}:{$matches[5]}:00";
	}
	else 
	{
		return '0000-00-00 00:00:00';
	}
	
}

function convert_sqldate_to_date($date)
{
	if(preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", trim($date), $matches) == true)
	{
		if( (int)$matches[1] > 0 && (int)$matches[2] > 0 && (int)$matches[3] > 0 )
		{
			// date("d/m/Y", mktime(0,0,0, $matches[2], $matches[3], $matches[1]));
			
			// changed to this method for dates BEFORE 1970!
			return str_pad($matches[3], 2, 0, STR_PAD_LEFT) . "/" . str_pad($matches[2], 2, 0, STR_PAD_LEFT) . "/" . str_pad($matches[1], 4, 0, STR_PAD_LEFT); 
		}
		else 
		{
			return "";
		}
	}
	else 
	{
		return "";
	}
	
}

function convert_sqldatetime_to_datetime($date, $no_seconds=false)
{
	if(preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", trim($date), $matches) == true)
	{
		if( (int)$matches[1] > 0 && (int)$matches[2] > 0 && (int)$matches[3] > 0 )
		{
			// date("d/m/Y", mktime(0,0,0, $matches[2], $matches[3], $matches[1]));
			
			// changed to this method for dates BEFORE 1970!
			return str_pad($matches[3], 2, 0, STR_PAD_LEFT) . "/" . str_pad($matches[2], 2, 0, STR_PAD_LEFT) . "/" . str_pad($matches[1], 4, 0, STR_PAD_LEFT)." ".str_pad($matches[4], 2, 0, STR_PAD_LEFT).":".str_pad($matches[5], 2, 0, STR_PAD_LEFT).(!$no_seconds?":".str_pad($matches[6], 2, 0, STR_PAD_LEFT):""); 
		}
		else 
		{
			return "";
		}
	}
	else 
	{
		return "";
	}
	
}

function convert_sqldatetime_to_date($date, $no_seconds=false)
{
	if(preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", trim($date), $matches) == true)
	{
		if( (int)$matches[1] > 0 && (int)$matches[2] > 0 && (int)$matches[3] > 0 )
		{
			// date("d/m/Y", mktime(0,0,0, $matches[2], $matches[3], $matches[1]));
			
			// changed to this method for dates BEFORE 1970!
			return str_pad($matches[3], 2, 0, STR_PAD_LEFT) . "/" . str_pad($matches[2], 2, 0, STR_PAD_LEFT) . "/" . str_pad($matches[1], 4, 0, STR_PAD_LEFT); 
		}
		else 
		{
			return "";
		}
	}
	else 
	{
		return "";
	}
	
}

function convert_date_to_sql($date)
{
	if(ereg("^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$", $date, $regs))
	{
		list($date, $day, $month, $year)=$regs;
		return "{$year}-{$month}-{$day}";
	}
	else
	{
		return null;
	}
}

function convert_12h_time_to_sqltime($time)
{
	if($time){
		$first_pieces = explode(" ", $time);
		$time_pieces = explode(":", $first_pieces[0]);
		$ampm = strtoupper($first_pieces[1]);
		$hour = $time_pieces[0];
		$minutes = $time_pieces[1];
		if(!($ampm == "AM" ||  $ampm == "PM")){
			echo("1");
			exit;
			return false;
		}
		if(strlen($hour) == 0 || strlen($minutes) < 2){
			echo("2");
			exit;			
			return false;
		}
		if($ampm == "AM"){
			$hour_sql = ($hour==12?0:$hour);
		}
		if($ampm == "PM"){
			$hour_sql = ($hour==12?$hour:$hour+12);
		}
		$timestamp = $hour_sql.":".$minutes.":00";
		return $timestamp;
	} else {
		return null;
	}
}

function convert_sqltime_to_12h_time($time)
{
	if($time){
		$time_pieces = explode(":", $time);
		$hour = $time_pieces[0];
		$minutes = $time_pieces[1];
		$ampm = "";
		if($hour < 12){
			$ampm = "AM";
			$hour_text = ($hour == 0?12:$hour);
		} else {
			$ampm = "PM";
			$hour_text = ($hour > 12?$hour-12:$hour);
		}
		$timestamp = $hour_text.":".$minutes." ".$ampm;
		return $timestamp;
	} else {
		return null;
	}
}

function convert_date_to_timestamp($date, &$timestamp)
{
	if(ereg("^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$", $date, $regs))
	{
		list($date, $day, $month, $year)=$regs;
		if($year > 0 && $month > 0 && $day > 0)
		{
			$timestamp = mktime(0,0,0, $month, $day, $year, $IS_DAYLIGHTSAVINGS);
			return true;
		}
		else 
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}


// same as above but adds 1 month on
function convert_jscriptdate_to_timestamp($date, &$timestamp)
{
	if(ereg("^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$", $date, $regs))
	{
		list($date, $day, $month, $year)=$regs;
		if($year > 0 && $month >= 0 && $day > 0)
		{
			$timestamp = mktime(0,0,0, $month+1, $day, $year, $IS_DAYLIGHTSAVINGS);
			return true;
		}
		else 
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}


/*
	$default must be in the sql date format yyyy-mm-dd
*/
function date_pulldown($name, $default, $start_year=null, $end_year=null)
{
	// if the start year isn't set, set it.
	if( is_null($start_year) == true )
	{
		$start_year = date('Y');
		$end_year = date('Y') + 10;
	}
	
	if( preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $default, $matches) )
	{
		$default_day = (int)$matches[3];
		$default_month = (int)$matches[2];
		$default_year = (int)$matches[1];
	}
	else 
	{
		$default_day = (int)date('d');
		$default_month = (int)date('m');
		$default_year = (int)date('Y');
	}
	
	$days = range(1,31);
	$months = range(1,12);
	$years = range($start_year, $end_year);
	
	html_pulldown($name . "_dd", $days, $default_day, false);
	?> / <?
	html_pulldown($name . "_mm", $months, $default_month, false);
	?> / <?
	html_pulldown($name . "_yyyy", $years, $default_year, false);
	
}

function convert_postdate_to_sql($name)
{
	$year = form_param($name . "_yyyy");
	$month = form_param($name . "_mm");
	$day = form_param($name . "_dd");
	
	return (int)$year . "-" . (int)$month . "-" . (int)$day;
}

function day_of_week($au_date){
	//$au_date expects date format dd/mm/yyyy
	list($day,$month,$year) = split("/",$au_date);
	//$day_of_week = date_ds_format("l",mktime(0,0,0,(int)$month,(int)$day,(int)$year), $IS_DAYLIGHTSAVINGS);
	$day_of_week = date("l",mktime(0,0,0,(int)$month,(int)$day,(int)$year));
	return $day_of_week;
}

?>