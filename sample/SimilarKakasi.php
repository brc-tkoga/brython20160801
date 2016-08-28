<?php
/** 2�̕��̗͂ގ��x���v�Z����(kakasi��)
 *
 * @copyright	(c)studio pahoo
 * @author		�p�p�ςӂ�
 * @version		1.0 2009/04/29
*/
$InternalEncoding = 'SJIS';
mb_internal_encoding($InternalEncoding);
mb_regex_encoding($InternalEncoding);
$MySelf = basename($_SERVER['SCRIPT_NAME']);

/**
 * kakasi�̎��s�p�X
 * @global	string $Kakasi
*/
//$Kakasi = '/usr/bin/kakasi';					//Linux�̏ꍇ�i��j
$Kakasi = 'C:\\tool\\kakasi\\bin\\kakasi.exe';		//Windows�̏ꍇ�i��j

/**
 * kakasi���g���ĒP��ɕ�������
 * @param	string $kakasi kakasi�̎��s�p�X
 * @param	string $str    ��������R���e���c
 * @return	�Ȃ�
*/
function parsing($kakasi, $str) {
	$items = array();

	//�`�ԑf��͂����������͂�n���Akakasi�ւ̃n���h���I�[�v��
	$handle = popen("echo '$str' | $kakasi -w ", "r");

	//���ʂ�1�s���擾
	while ($get_kakasi = fgets($handle)) {
		//kakasi�̌��ʂ𕪉�
		$result = preg_split("/[\s,]+/", $get_kakasi);
		//���ʂ�z��Ɋi�[����
		foreach ($result as $key=>$val) {
			if ($val != "")		array_push($items, $val);
		}
	}
	pclose($handle);

	return $items;
}

/**
 * �z��e�v�f�̕����񒷂̓��̍��v���v�Z����
 * @param	array $items �P��̓����Ă���z��
 * @return	int �d�ݕt���J�E���g
*/
function count_weight($items) {
	$ret = 9;
	foreach ($items as $word)	$ret += mb_strlen($word) * mb_strlen($word);
	return $ret;
}

/**
 * 2�̃e�L�X�g�̗ގ��x���v�Z����
 * @param	string $sour   ���̃e�L�X�g
 * @param	string $dest   ��r����e�L�X�g
 * @return	double �ގ��x�i0�`1�j�^FALSE�F�v�Z�Ɏ��s
*/
function similar_kakasi($sour, $dest) {
	global $Kakasi;

	$items_sour = array();
	$items_dest = array();

	//�P�s�����o���ĒP��ɕ�������
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

// ���C���E�v���O���� =======================================================
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
//	$sour = htmlspecialchars($sour);				//XSS�΍�
	$result = sprintf('%02.1f', similar_kakasi($sour, $dest) * 100);
}

// �\������ =================================================================
echo <<< EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$InternalEncoding" />
<title>2�̕��̗͂ގ��x���v�Z����(KAKASI��)</title>
<meta name="author" content="studio pahoo" />
<meta name="copyright" content="studio pahoo" />
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW" />
</head>
<body>
<h1>��2�̕��̗͂ގ��x���v�Z����(KAKASI��)</h1>
<form name="myForm" method="post" enctype="multipart/form-data" action="$MySelf">
<table border="0" cellspacing="10">
<tr>
<td>���̃e�L�X�g</td>
<td>&nbsp;</td>
<td>��r����e�L�X�g</td>
</tr>
<tr>
<td><textarea name="sour" rows="10" cols="40">$sour</textarea></td>
<td>��</td>
<td><textarea name="dest" rows="10" cols="40">$dest</textarea></td>
</tr>
<tr>
<td>
<input type="submit" name="exec" value="��r" />�@
<input type="submit" name="reset" value="���Z�b�g" />
</td>
<td>&nbsp;</td>
<td>�ގ��x�F <b>$result ��</b></td>

</tr>
<tr>
<td colspan="3">
<hr />
<b>�y�g�����z</b>
<ol>
<li>�m���̃e�L�X�g�n�ɔ�r���̃e�L�X�g����͂��Ă��������B</li>
<li>�m��r����e�L�X�g�n�ɔ�r�������e�L�X�g����͂��Ă��������B</li>
<li>�m��r�n�{�^���������Ă��������B</li>
<li>�ގ��x���p�[�Z���g�\������܂��B</li>
<li>�m���Z�b�g�n�{�^���������ƁA�\�����N���A����܂��B</li>
</ol>
</td>
</tr>
</table>
</form>
</body>
</html>

EOF;
/*
** �o�[�W�����A�b�v���� ===================================================
 *
 * @version		1.0 2009/04/30
*/
?>
