<?php
session_start();

require 'vendor/autoload.php';

$con = new MongoDB\Client('mongodb+srv://khushi:khushi123@cluster-nc.nm1fy.mongodb.net/ncdb?retryWrites=true&w=majority');
$db = $con->ncdb;
$userscoll = $db->users;

if(isset($_POST['otp']))
{
	$otp = (int)$_POST['otp'];
	$email = $_SESSION['EMAIL'];
	$res = $userscoll->findOne(["email" => $email, "otp" => $otp, "isexpired" => 0]);
	$count = $userscoll->count();
	if($count>0)
	{
		if($res)
		{
			$userscoll->updateOne(["email" => $email], ['$set' => ['otp' => $otp]]);
			$_SESSION['IS_LOGIN'] = $email;
			echo "Logged in successfully!";
			$userscoll->updateOne(["email" => $email, "otp" => $otp], ['$set' => ['isexpired' => 1]]);
		}
		else
		{
			echo "Invalid OTP!";
		}
	}
	else
	{
		echo "Invalid OTP!";
	}
}
?>