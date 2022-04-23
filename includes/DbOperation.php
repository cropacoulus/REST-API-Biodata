<?php

class DbOperation
{
    private $con;
    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }

	function createperson($nama, $email, $nohp, $keterangan){
		$stmt = $this->con->prepare("INSERT INTO tabel (nama, email, nohp, keterangan)
                                  VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $nama, $email, $nohp, $keterangan);
		if($stmt->execute())
			return true;
		return false;
	}

	function getperson(){
		$stmt = $this->con->prepare("SELECT *
                                FROM tabel");
		$stmt->execute();
		$stmt->bind_result($id, $nama, $email, $nohp, $keterangan);
		$manyperson = array();
		while($stmt->fetch()){
			$person  = array();
			$person['id'] = $id;
			$person['nama'] = $nama;
			$person['email'] =  $email;
			$person['nohp'] = $nohp;
			$person['keterangan'] = $keterangan;
			array_push($manyperson, $person);
		}
		return $manyperson;
	}

	

	function updateperson($id, $nama, $email, $nohp, $keterangan){
		$stmt = $this->con->prepare("UPDATE tabel
                                SET nama = ?, email = ?, nohp = ?, keterangan = ?
                                WHERE id = ?");
		$stmt->bind_param("ssssi", $nama, $email, $nohp, $keterangan, $id);
		if($stmt->execute())
			return true;
		return false;
	}

	function deleteperson($id){
		$stmt = $this->con->prepare("DELETE FROM tabel
                                 WHERE id = ? ");
		$stmt->bind_param("i", $id);
		if($stmt->execute())
			return true;
		return false;
	}
}
