<?php /**
 * 
 */
class MY_Form_validation extends CI_Form_validation
{

	public function __construct()
    {
        parent::__construct();
    }

    // --------------------------------------------------------------------

	/**
	 * If string length >= 8
	 *
	 * @param	string
	 * @return	bool
	 */
	function password_strength_check($str)
	{
		if(strlen($str) < 8) 
		{
			return FALSE;
		}
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Check if date is valid 
	 *
	 * @param	string (Y-m-d)
	 * @return	bool
	 */
	function date_valid($date) 
	{
		$day = (int) substr($date, 8, 2);
		$month = (int) substr($date, 5, 2);
		$year = (int) substr($date, 0, 4);
		return checkdate($month, $day, $year);
	}

	// --------------------------------------------------------------------

	/**
	 * Check if time is valid 
	 *
	 * @param	string (H:i)
	 * @return	bool
	 */
	function time_valid($time) 
	{
		$dateObj = DateTime::createFromFormat('H:i', $time);
		if ($dateObj == FALSE) 
		{ 
			return FALSE;
		}
		return TRUE;
	}
	// --------------------------------------------------------------------

	/**
	 * Check if datetime is valid 
	 *
	 * @param	string (Y-m-dTH:i)
	 * @return	bool
	 */
	function datetime_valid($datetime) 
	{
		$dateObj = DateTime::createFromFormat('Y-m-d\TH:i', $datetime);
		if ($dateObj == FALSE) { 
			return FALSE;
		}
		return TRUE;
	}
}