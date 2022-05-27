<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
require 'vendor/PHPMailer/phpmailer/src/PHPMailer.php'; 
require 'vendor/PHPMailer/phpmailer/src/SMTP.php';  
require 'vendor/PHPMailer/phpmailer/src/Exception.php'; 

$con = new MongoDB\Client('mongodb+srv://khushi:khushi123@cluster-nc.nm1fy.mongodb.net/ncdb?retryWrites=true&w=majority');
$db = $con->ncdb;
$userscoll = $db->users;
$buyercoll = $db->buyer;

if(isset($_POST['email']))
{
$email = $_POST['email'];
$e = ($_POST['email']);
$_SESSION['em'] = $e;
$res = $userscoll->findOne(["email" => $_POST['email']]);
$resb = $buyercoll->findOne(["email" => $_POST['email']]);
$count = $userscoll->count();
if($count>0)
{
	if($res and $resb)
	{
		
		if(strcmp($_POST['password2'],$_POST['password3'])==0)
		{
			$otp = rand(100000,999999);
			$userscoll->updateOne(["email" => $_POST['email']], ['$set' => ['otp' => $otp, "isexpired" => 0]]);
			$_SESSION['EMAIL']=$email;
			$_SESSION['password2']=$_POST['password2'];
			$_SESSION['password3']=$_POST['password3'];
			sendOTP($email, $otp);
			echo "Proceeding...";
		}
		else
		{
			echo "New and Retyped passwords does not match!";
		}
	}
	else
	{
		echo "Email does not exists!";
	}
}
else
{
	echo "Email does not exists!";
}
}

function sendOTP($email,$otp)
	{	
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->Host = 'smtp.mailtrap.io';
		$mail->SMTPAuth = true;
		$mail->Port = 2525;
		$mail->Username = '190fc72057e88b';
		$mail->Password = '0f436579b26fc8';

		$mail->setFrom('newbiescompanion@mailtrap.io', 'Mailtrap');
		$mail->addReplyTo('newbiescompanion@mailtrap.io', 'Mailtrap');
		
		$message_body = "Your One Time Password is<br><br>".$otp."<br><br>Please do not share it with anyone.<br>For any queries please write to us at newbiescompanion@gmail.com";
		$mail->AddAddress($email);
		$mail->Subject = "OTP to Reset Password";
		$mail->MsgHTML($message_body);
		$result = $mail->Send();
		if(!$result)
		{
			echo "Mailer Error: ".$mail->ErrorInfo;
		}
		else
		{
			return $result;
		}
	}
?>