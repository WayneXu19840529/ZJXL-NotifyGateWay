<?php
$name='ZhongJiaoXingLu_Notification';
$subject='ZhongJiaoXingLu_Notification';
$frommail='zjxl_ops@sinoiov.com';
$tomail='xuyingwei@sinoiov.com';
$bcc='noc@ctfo.com';
$smtp='mail.sinoiov.com';
//$smtp='smtp.ctfo.com';
$user='notify_sinoiov';
$pass='zjxl.com#20141119';
$Logfile='/tmp/receiver_ops.log';

$Flog = fopen($Logfile,'a+');

require_once('../../mail_sms_inc/mail_db_conn.php');

$conn = db_connect();

if(!$conn){
	echo "Error:DB connect faild";
	exit(3);
}

$_POST['user_name']="ops_zabbix";
$_POST['user_passwd']="opszabbix234#";

if(!empty($_POST['user_name']) && !empty($_POST['user_passwd'])){
	$user_name = $_POST['user_name'];
	$user_passwd = $_POST['user_passwd'];
	//fwrite($Flog,"user_name :".$user_name."; user_passwd :".$user_passwd."\n");

	$sql = "select id from user_info where user_name='".$user_name."'and user_passwd='".MD5($user_passwd)."'";
	$result = mysql_query($sql,$conn);
	$result_id = mysql_fetch_array($result);
	//fwrite($Flog,"ID:".$result_id['id']."##\n");
	//fwrite($Flog,"result: ".count($result)."\n");
		if(empty($result_id))
		{
			echo "Error:Username or Userpasswd is wrong.";
			error_log("Error:Username or Userpasswd is wrong.");
			exit(1);
		}
	}else{
		echo "Error:Username or Userpasswd is empty.";
		error_log("Error:Username or Userpasswd is empty.");
		exit(1);
		}

/* use db

if(!empty($_POST['user_name']) && !empty($_POST['user_passwd'])){
	$user_name = $_POST['user_name'];
        $user_passwd = $_POST['user_passwd'];
        fwrite($Flog,"user_name :".$user_name."; user_passwd :".$user_passwd."\n");

	if( ! $user_name == "nagios" && $user_passwd == "nagios" ){
		echo "Error:Username or Userpasswd is wrong.";
		error_log("Error:Username or Userpasswd is wrong.");
		exit(1);
	}
}else{
                echo "Error:Username or Userpasswd is empty.";
                error_log("Error:Username or Userpasswd is empty.");
                exit(1);
}

*/
//echo $user_name.":".$user_passwd;


/*
if(!empty($_POST['notify_receiver'])){
		$tomail=$_POST['notify_receiver'];
		}else{
		echo "Error:Notify_receiver is null";
		error_log("Error:Notify_receiver is null");
		exit(2);
		}

if(!empty($_POST['notify_title'])){
		$subject=str_replace("'","\"",$_POST['notify_title']);
		}else{
		echo "Error:Notify_title is null";
		error_log("Error:Notify_title is null");
		exit(2);
		}

if(isset($_POST['notify_body'])){
		$mail_body=$_POST['notify_body'];
		}


error_reporting(E_STRICT);
date_default_timezone_set('Asia/Shanghai');
require_once('/var/phpmailer/class.phpmailer.php');

$mail             = new PHPMailer();
//$body             = file_get_contents('contents.html');
$body             = $mail_body;
$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "mail.sinoiov.com"; // SMTP server
$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
//$mail->SMTPAuth   = false;                  // disenable SMTP authentication

$mail->SMTPSecure   = "ssl";                  // tls / ssl

$mail->CharSet    = "UTF-8";               // set the CharSet
$mail->Host       = "mail.sinoiov.com"; 	// sets the SMTP server
$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
$mail->Username   = "zjxl_ops@sinoiov.com"; 	// SMTP account username
$mail->Password   = "zjxl.com#20141119";        // SMTP account password
$mail->SetFrom($frommail,$user);//$mail->SetFrom('wayne.xu@ctfo.com', 'First Last');
//$mail->AddReplyTo("name@yourdomain.com","First Last");
$mail->Subject    = $subject;
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->MsgHTML($body);
$address_list = split('[,/-]',$tomail);
for ($i=0;$i<count($address_list);$i++){
	$mail->AddAddress($address_list[$i], "");
}
//$mail->AddAttachment("/var/phpmailer/examples/images/phpmailer.gif");      // attachment
//$mail->AddAttachment("/var/phpmailer/examples/images/phpmailer_mini.gif"); // attachment
// CC to somebody
//$mail->AddCC($bcc);
//log
$daytime = date('Y-m-d H:i:s',time());

if(!$mail->Send()) {
	  echo "Mailer Error: " . $mail->ErrorInfo;
	  fwrite($Flog,$daytime." ".$user_name." Mailer Error: ".$mail->ErrorInfo." Title: ".$subject.".\n");
} else {
	  echo "success";
	  fwrite($Flog,$daytime." ".$user_name." Send mail OK. Title: ".$subject.".\n");
}

// insert into sms_queue
for ( $num=0; $num<count($address_list); $num++){

	$insert_sql = "INSERT INTO sms_queue (date_time,contact,content,send_result) VALUES ('".$daytime."','".$address_list[$num]."','".$subject."','pending');";

	mysql_query($insert_sql,$conn);

	fwrite($Flog,$daytime." ".$user_name." Send mail OK. SQL: ".$insert_sql.".\n");

}

*/

$select_sql = 'select * from receiver_info;';

$mysql_result = mysql_query($select_sql,$conn);

while ( $mysql_fetch_result = mysql_fetch_array($mysql_result))
{
	var_dump($mysql_fetch_result);
	fwrite($Flog,"INSERT INTO receiver_info (user_name,phone_number) VALUES ('".$mysql_fetch_result['user_name']."','".$mysql_fetch_result["phone_number"]."');\n");
}

fclose($Flog);

?>