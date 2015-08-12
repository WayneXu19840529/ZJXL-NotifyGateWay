<?php

function GET_POST_FIELD($receivers,$content)
{
	if( empty($receivers) or empty($content) ){
		die("Error: receivers or content is empty.\n");
	}

	$account = "ityw";
	$passwd = "zjxl_ityw!@#";
	$auth_key = "ityw_12#$";

	$md5_sign = MD5_SIGN($receivers,$passwd,$account,$auth_key);
	
	$sms_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ctfo="http://www.ctfo.com">
<soapenv:Header>
        <ctfo:Authentication><password>'.$passwd.'</password><userName>'.$account.'</userName></ctfo:Authentication>
</soapenv:Header>
<soapenv:Body>
<ctfo:SendMessage>
        <SendMessageRequest>
                <signValue>'.$md5_sign.'</signValue>
                <content><![CDATA['.$content.']]></content>
                <mobiles>'.$receivers.'</mobiles>
        </SendMessageRequest>
</ctfo:SendMessage>
</soapenv:Body>
</soapenv:Envelope>';

	return $sms_string;

}

function MD5_SIGN($a_receivers,$a_passwd,$a_account,$a_auth_key)
{

        $string = $a_receivers."_".$a_passwd.$a_account.$a_auth_key;

	if( $md5_string = md5($string) )
	{
		return $md5_string;
	}else{
		die("Error: MD5 failed.\n");
	}
}

function CUT_STR($input_strings,$len_strings)
{
        $str_len = strlen($input_strings);
        print $str_len."\n";

        if ( $str_len >= $len_strings )
        {
                $cut_str = substr($input_strings,0,$len_strings);
                $cut_str = $cut_str."..detail in mail.";
        }else{
		$cut_str = $input_strings;
	}
        return $cut_str;
}

function SEND_SMS($receivers,$content)
{
	//set header 
	$header[] = "Content-Type: text/xml; charset=utf-8";

	//curl op
        $ch = curl_init();
        //$url = "http://192.168.100.166:8081/NotificationService/services/notification";
        //$url = "http://192.168.111.189:5180/NotificationService/services/notification";
	$url = "http://sms.4000966666.com:9000/NotificationService/services/notification";


        #strins "..detail in mail. 【中交兴路】" : 36 byte.
        $content = CUT_STR($content,103);

        //$content = $content." [ZJXL-IT]";
        //$content = $content." [HYGGPT]";
        //$content = $content." 【中交兴路】";
        //$content = "【中交兴路】 ".$content;
        //var_dump(strlen($content));

	$post_field = GET_POST_FIELD($receivers,$content);

	//var_dump($post_field);

        curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"$post_field");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $rsp = curl_exec($ch);
	//var_dump($rsp);
        
	$datetime = date('Y-m-d H:i:s');
        if( preg_match("/success/",$rsp) ){
                #file_put_contents("log/ops_system.log",$datetime." send sms success.\n",FILE_APPEND);
                file_put_contents("log/ops_system.log",$datetime."receiver: ".$receivers."; content:".$content."send sms success.\n",FILE_APPEND);
                return ("true");
        }else{
                #file_put_contents("log/ops_system.log",$datetime." send sms failed.\n",FILE_APPEND);
                file_put_contents("log/ops_system.log",$datetime."receiver: ".$receivers."; content:".$content." send sms failed.\n",FILE_APPEND);
                return("false");
	}
        curl_close($ch);
}

//$OP_RESULT = SEND_SMS("13911484765","【中交兴路监控平台】:中交兴路监控平台各位领导客车平台出现异常情况!");
//$OP_RESULT = SEND_SMS("13911484765","<From 21ViaNet> 192.168.111.181(chpt_m_01) : Swap SWAP OK - 100% free (4094 MB out of 4094 MB)");
//echo $OP_RESULT;

?>
