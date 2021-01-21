<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Country_model extends CI_Model {

	public function get_countries() {
		return array(
			"brunei" => "Brunei",
			"burma" => "Burma",
			"cambodia" => "Cambodia",
			"timor_leste" => "Timor-Leste",
			"indonesia" => "Indonesia",
			"laos" => "Laos",
			"malaysia" => "Malaysia",
			"philippines" => "Philippines",
			"singapore" => "Singapore",
			"thailand" => "Thailand",
			"vietnam" => "Vietnam"
		);
	}

}