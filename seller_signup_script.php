<?php

	session_start();
	
	require 'vendor/autoload.php';
	$con = new MongoDB\Client('mongodb+srv://khushi:khushi123@cluster-nc.nm1fy.mongodb.net/ncdb?retryWrites=true&w=majority');
	$ncdb = $con->ncdb;
	$sellercoll = $ncdb->seller;
	$userscoll = $ncdb->users;
	
	$fname = $_POST['fname'];
    $lname = $_POST['lname'];
	$email = $_POST['email'];
	$e = ($_POST['email']);
	$_SESSION['em'] = $e;
    $phone = $_POST['phone'];
	$uname = $_POST['uname'];
	$password = $_POST['password'];
	$encrypted_pwd = md5($password);
	
	$doccnt = $sellercoll->count();
	if($doccnt == 0)
	{
		$idcnt = 1;
	}
	else
	{
		$idcnt = $doccnt + 1;
	}
	
	$sellercoll->insertOne(
	["id" => $idcnt, "fname" => "$fname", "lname" => "$lname", "email" => "$email", "phone" => "$phone", "uname" => "$uname", "password" => "$encrypted_pwd"]
	);
	
	$doccntu = $userscoll->count();
	if($doccntu == 0)
	{
		$idcntu = 1;
	}
	else
	{
		$idcntu = $doccntu + 1;
	}
	
	$userscoll->insertOne(["id" => $idcntu, "email" => $email, "otp" => null, "isexpired" => null]);
	
	header("Location:seller.php");
                     
?>