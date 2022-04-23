<?php
	require_once './includes/DbOperation.php';
	function isTheseParametersAvailable($params){
		$available = true;
		$missingparams = "";

		foreach($params as $param){
			if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
				$available = false;
				$missingparams = $missingparams . ", " . $param;
			}
		}

		if(!$available){
			$response = array();
			$response['error'] = true;
			$response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';
			echo json_encode($response);
			die();
		}
	}

	$response = array();
	if(isset($_GET['aksi'])){
		switch($_GET['aksi']){
			case 'createperson':
				isTheseParametersAvailable(array('nama','email','nohp','keterangan'));
				$db = new DbOperation();
				$result = $db->createperson(
					$_POST['nama'],
					$_POST['email'],
					$_POST['nohp'],
					$_POST['keterangan']
				);
				if($result){
					$response['error'] = false;
					$response['message'] = 'cihuy sukses add data';
					// $response['manyperson'] = $db->getperson();
				}else{
					$response['error'] = true;
					$response['message'] = 'Some error occurred please try again';
				}

			break;
			case 'getperson':
				$db = new DbOperation();
				$response['error'] = false;
				$response['message'] = 'Request successfully completed';
				$response['manyperson'] = $db->getperson();
			break;
			case 'updateperson':
				isTheseParametersAvailable(array('id','nama','email','nohp','keterangan'));
				$db = new DbOperation();
				$result = $db->updateperson(
					$_POST['id'],
					$_POST['nama'],
					$_POST['email'],
					$_POST['nohp'],
					$_POST['keterangan']
				);
				
				if($result){
					$response['error'] = false;
					$response['message'] = 'Update data sukses';
					//$response['person'] = $db->getperson();
				}else{
					$response['error'] = true;
					$response['message'] = 'Some error occurred please try again';
				}
			break;
			case 'deleteperson':
				if(isset($_GET['id'])){
					$db= new DbOperation();
					if($db->deleteperson($_GET['id'])){
						$response['error'] = false;
						$response['message'] = 'Delete succesfully completed';
					}else{
						$response['error'] = true;
						$response['message'] = 'Nothing to Delete, provide an id please';
					}
				}
			break;
		}

	}else{
		$response['error'] = true;
		$response['message'] = 'Invalid API Call';
	}
	echo json_encode($response);
