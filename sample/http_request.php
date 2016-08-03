<?php
require_once("HTTP/Request.php");

$cookies = array();

$http = new HTTP_Request();

$login_url = "https://login.yahoo.co.jp/config/login";

$http->setURL($login_url);
$http->setMethod(HTTP_REQUEST_METHOD_POST);

//POSTするデータ
$http->addPostData("login", "toshinono1009");
$http->addPostData("passwd", "Toshi1009");

$http->sendRequest();

if(count($http->getResponseCookies())){
	$cookies = $http->getResponseCookies();
}

//↓コメントアウトを外せばヘッダーとページソースが表示されます。
print_r($http->getResponseHeader());
echo $http->getResponseBody();


var_dump($cookies);//クッキーを表示します。
?>
