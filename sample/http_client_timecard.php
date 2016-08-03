<?php
/**
 * HTTP_CLIENTを使用してログイン
 */
require_once ('HTTP/Client.php');


//ログイン処理
$url = "http://54.64.68.9/timecard/staff/login.php";
$client = new HTTP_Client();
$params = array(
			"staff_id" => "0370",
			"password" => "tk2016brc",
			// ボタン名までチェックしているっぽい。これが肝
			"submit" => "ログイン"
);
$response_code = $client->post($url, $params);
if($response_code != 200){
	echo "this site is not found!!";
	exit(99);
}


//クッキーマネージャシをシリアライズ
$classCookieManager = $client->getCookieManager();
$classCookieManager->serializeSessionCookies(true);
$seriarize = serialize($classCookieManager);

//勤務表に遷移
$url = "http://54.64.68.9/timecard/staff/list/index.php";
$client = new HTTP_Client(null,null,unserialize($seriarize));
$params = array(
		"list" => "2016-02",
		//サーバー側で強制的にログイン者の社員番号になっている。
		"staff" => "0382"
);
$response_code = $client->get($url, $params);
if($response_code != 200){
	echo "this site is not found!!";
	exit(99);
}

//


//表示
$response = $client->currentResponse();
print_r($response);

?>
