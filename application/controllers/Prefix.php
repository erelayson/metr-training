<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prefix extends CI_Controller {

	public static function get_prefixes() {
		return array(
			"mr" => "Mr.",
			"ms" => "Ms.",
			"dr" => "Doctor."
		);
	}

}