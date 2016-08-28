<?php
/** 2つの文章の類似度を計算する(kakasi版)
 *
 * @copyright	(c)studio pahoo
 * @author		パパぱふぅ
 * @version		1.0 2009/04/29
*/
$InternalEncoding = 'SJIS';
mb_internal_encoding($InternalEncoding);
mb_regex_encoding($InternalEncoding);
$MySelf = basename($_SERVER['SCRIPT_NAME']);

/**
 * kakasiの実行パス
 * @global	string $Kakasi
*/
//$Kakasi = '/usr/bin/kakasi';					//Linuxの場合（例）
$Kakasi = 'C:\\tool\\kakasi\\bin\\kakasi.exe';		//Windowsの場合（例）

/**
 * kakasiを使って単語に分解する
 * @param	string $kakasi kakasiの実行パス
 * @param	string $str    分解するコンテンツ
 * @return	なし
*/
function parsing($kakasi, $str) {
	$items = array();

	//形態素解析をしたい文章を渡しつつ、kakasiへのハンドルオープン
	$handle = popen("echo '$str' | $kakasi -w ", "r");

	//結果を1行ずつ取得
	while ($get_kakasi = fgets($handle)) {
		//kakasiの結果を分解
		$result = preg_split("/[\s,]+/", $get_kakasi);
		//結果を配列に格納する
		foreach ($result as $key=>$val) {
			if ($val != "")		array_push($items, $val);
		}
	}
	pclose($handle);

	return $items;
}

/**
 * 配列各要素の文字列長の二乗の合計を計算する
 * @param	array $items 単語の入っている配列
 * @return	int 重み付けカウント
*/
function count_weight($items) {
	$ret = 9;
	foreach ($items as $word)	$ret += mb_strlen($word) * mb_strlen($word);
	return $ret;
}

/**
 * 2つのテキストの類似度を計算する
 * @param	string $sour   元のテキスト
 * @param	string $dest   比較するテキスト
 * @return	double 類似度（0～1）／FALSE：計算に失敗
*/
function similar_kakasi($sour, $dest) {
	global $Kakasi;

	$items_sour = array();
	$items_dest = array();

	//１行ずつ取り出して単語に分解する
	$str = strtok($sour, "\n");
	while ($str != FALSE) {
		$items_sour = parsing($Kakasi, $str);
		$str = strtok("\n");
	}
	$str = strtok($dest, "\n");
	while ($str != FALSE) {
		$items_dest = parsing($Kakasi, $str);
		$str = strtok("\n");
	}

	$result = count_weight(array_intersect($items_sour, $items_dest));

	return (double)$result / count_weight($items_dest);
}

// メイン・プログラム =======================================================
$sour = '';
$dest = '';
$result = '';

if (isset($_POST['reset'])) {
	$sour = '';
	$dest = '';
	$result = '';
} else if (isset($_POST['exec'])) {
	$sour = isset($_POST['sour']) ? $_POST['sour'] : '';
	$dest = isset($_POST['dest']) ? $_POST['dest'] : '';
// 	$sour = mb_convert_encoding($sour, $InternalEncoding, 'auto');
// 	$dest = mb_convert_encoding($dest, $InternalEncoding, 'auto');
//	$sour = htmlspecialchars($sour);				//XSS対策
	$result = sprintf('%02.1f', similar_kakasi($sour, $dest) * 100);
}

// 表示処理 =================================================================
echo <<< EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$InternalEncoding" />
<title>2つの文章の類似度を計算する(KAKASI版)</title>
<meta name="author" content="studio pahoo" />
<meta name="copyright" content="studio pahoo" />
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW" />
</head>
<body>
<h1>■2つの文章の類似度を計算する(KAKASI版)</h1>
<form name="myForm" method="post" enctype="multipart/form-data" action="$MySelf">
<table border="0" cellspacing="10">
<tr>
<td>元のテキスト</td>
<td>&nbsp;</td>
<td>比較するテキスト</td>
</tr>
<tr>
<td><textarea name="sour" rows="10" cols="40">$sour</textarea></td>
<td>⇔</td>
<td><textarea name="dest" rows="10" cols="40">$dest</textarea></td>
</tr>
<tr>
<td>
<input type="submit" name="exec" value="比較" />　
<input type="submit" name="reset" value="リセット" />
</td>
<td>&nbsp;</td>
<td>類似度： <b>$result ％</b></td>

</tr>
<tr>
<td colspan="3">
<hr />
<b>【使い方】</b>
<ol>
<li>［元のテキスト］に比較元のテキストを入力してください。</li>
<li>［比較するテキスト］に比較したいテキストを入力してください。</li>
<li>［比較］ボタンを押してください。</li>
<li>類似度がパーセント表示されます。</li>
<li>［リセット］ボタンを押すと、表示がクリアされます。</li>
</ol>
</td>
</tr>
</table>
</form>
</body>
</html>

EOF;
/*
** バージョンアップ履歴 ===================================================
 *
 * @version		1.0 2009/04/30
*/
?>
