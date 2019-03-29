<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicles extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	// Get Method with required parameter
	public function get($modelyear='',$manufacturer='',$model='')
	{
		$url = "https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/".$modelyear."/make/".$manufacturer."/model/".$model."?format=json";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch); 
		$result = json_decode($output);
		$data['Count'] = isset($result->Count)?$result->Count:0;
		if(isset($result->Results))
		{
			foreach($result->Results as $Rows)
			{
				$data['Results'][] = array('Description'=>$Rows->VehicleDescription,'VehicleId'=>$Rows->VehicleId);
			}
		}else{ $data['Results'] = []; }
		echo json_encode($data);
	}
	
	public function getwithrate($modelyear='',$manufacturer='',$model='')
	{
		$data = array();
		$url = "https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/".$modelyear."/make/".$manufacturer."/model/".$model."?format=json";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch); 
		$result = json_decode($output);
		
		$data['Count'] = isset($result->Count)?$result->Count:0;
		if(isset($result->Results))
		{
			foreach($result->Results as $Rows)
			{
				$url = "https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/".$Rows->VehicleId."?format=json";
				$ch1 = curl_init($url);
				curl_setopt($ch1, CURLOPT_HEADER, 0);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
				$output1 = curl_exec($ch1);
				$result1 = json_decode($output1);
				$result1->Results[0]->OverallRating;
				$data['Results'][] = array('CrashRating'=>$result1->Results[0]->OverallRating,'Description'=>$Rows->VehicleDescription,'VehicleId'=>$Rows->VehicleId);
			}
		}else{ $data['Results'] = []; }
		echo json_encode($data);
		
	}
	
	public function post()
	{
		$modelyear = '2015';
		$manufacturer = 'Acura';
		$url = "https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/".$modelyear."/make/".$manufacturer."/?format=json";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch); 
		$result = json_decode($output);
		$data['Count'] = isset($result->Count)?$result->Count:0;
		if(isset($result->Results))
		{
			foreach($result->Results as $Rows)
			{
				$data['Results'][] = array('modelYear'=>$Rows->ModelYear,'manufacturer'=>$Rows->Make,'model'=>$Rows->Model);
			}
		}else{ $data['Results'] = []; }
		echo json_encode($data);
	}
	
}
