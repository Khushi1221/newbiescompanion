<?php
	session_start();

	require 'vendor/autoload.php';
	$con = new MongoDB\Client('mongodb+srv://khushi:khushi123@cluster-nc.nm1fy.mongodb.net/ncdb?retryWrites=true&w=majority');
	$db = $con->ncdb;
	$buyercoll = $db->buyer;
	
	/* CHANGE PASSWORD */
	$oldpwd = $_POST['oldpassword'];
	$encoldpwd = md5($oldpwd);
	$newpwd = $_POST['newpassword'];
	$retypepwd = $_POST['retypepassword'];
	$email = $_SESSION['em'];
	if(strcmp($newpwd,$retypepwd)==0)
	{
		$encnewpwd = md5($newpwd);
		$cpwdqry = $buyercoll->updateOne(['email' => $email, 'password' => $encoldpwd], ['$set' => ['password' => $encnewpwd]]);
		session_unset();
		session_destroy();
		header("Location:buyer_login.php");
	}
	else
	{
		echo "Re-typed password does not match with the New password.";
	}
?>