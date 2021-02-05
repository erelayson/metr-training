<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey_model extends CI_Model {

	function get_survey() {
		return array(
			'name' => "DUO Service",
			'description' => "I am a DUO Service",
			'keyword' => "BATANGAS",
			'targetSelect' => "1",
			'sweden_offer' => "sweden_offer1",
			'ace_tariff_plan' => "ace_batangas",
			'alg_zone_service_name' => "ACE_BATANGAS",
			'vn_pool_id' => "123",
			'vn_pool_area_code' => "4",
			'ajax_src' => "source1",
			'ajax_dest' => "destopt1",
			'brands' => array(
				array(
					'keyword' => "tablenametest",
					'name' => "table name test"
				)
			),
			'unlimited' => "yes",
			'allowance_unit' => "sms"
		);
	}

	function get_skus() {
		return array(
			array(
				'keyword' => 'DUO100', 
				'name' => 'DUO100',
				'description' => 'DUO 100 SMS', 
				'for_extend' => '1', 
				'params' => 'Expiry(days): 1'
			),
			array(
				'keyword' => 'DUO200', 
				'name' => 'DUO200',
				'description' => 'DUO 200 SMS', 
				'for_extend' => '0', 
				'params' => 'Expiry(days): 3'
			)
		);
	}

	function set_skus() {
		echo "inserting to sku";
		echo "<pre>";
		print_r ($_POST);
		echo "</pre>";
		return;
	}

	function get_duo_messages() {
		return array(
			array(
				'message_type' => 'duo_vn_expiry_confirmation', 
				'brand' => 'GHP',
				'message' => 'Hey <MSISDN> Your IMSI <IMSI> expired', 
				'access_code' => '3333'
			),
			array(
				'message_type' => 'duo_vn_test_confirmation', 
				'brand' => 'HPG',
				'message' => 'Test message', 
				'access_code' => '4444'
			)
		);
	}

}