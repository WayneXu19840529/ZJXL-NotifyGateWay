#!/bin/bash
send_url="http://192.168.111.111/mail_sms/sms.php"
receiver="xuyingwei@ctfo.com,caishuyang@ctfo.com"
message_title="邮箱头测试，请勿理会！"
message_body="邮箱内容"
username="nagios"
password="nagios"

curl -d "notify_receiver=$receiver&notify_title=$message_title&notify_body=Infor: $message_title<\br>url:$download_page<\br>date: $datetime<\br>&user_name=${username}&user_passwd=${password}"  $send_url
#curl -d "post_phone=13911484765,13911484765&post_content=\"just a test la\"" http://114.242.194.229/sendsms_api.php
