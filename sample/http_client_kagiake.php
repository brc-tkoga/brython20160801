<?php
/**
 * HTTP_CLIENTを使用してログイン
 */
require_once ('HTTP/Client.php');


//ログイン処理
$client = new HTTP_Client();
$params = array(
			"s_login_id" => "tkoga@brycen.co.jp",
			"s_password" => "1111",
			"i_access_cnt" => 0,
);
$url = "http://localhost/kagiake/login/login?XDEBUG_SESSION_START=ECLIPSE_DBGP&KEY=14702066642986";
$client->post($url, $params);

//クッキーマネージャシをシリアライズ
$classCookieManager = $client->getCookieManager();
$classCookieManager->serializeSessionCookies(true);
$seriarize = serialize($classCookieManager);

//会社選択処理
$client = new HTTP_Client(null,null,unserialize($seriarize));
$params = array(
		"s_company_id" => "A00001",
		"i_access_cnt" => 0
);
$url = "http://localhost/kagiake/login/company/select?XDEBUG_SESSION_START=ECLIPSE_DBGP&KEY=14702066642986";
$client->post($url, $params);

//クッキーマネージャシをシリアライズ
$classCookieManager = $client->getCookieManager();
$classCookieManager->serializeSessionCookies(true);
$seriarize = serialize($classCookieManager);

//好きなところに遷移
$client = new HTTP_Client(null,null,unserialize($seriarize));
$url = "http://localhost/kagiake/user/list?XDEBUG_SESSION_START=ECLIPSE_DBGP&KEY=14702066642986";
$client->get($url);





//表示
$response = $client->currentResponse();
print_r($response);

?>
