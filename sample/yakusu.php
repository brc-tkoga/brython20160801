<?php
/**
 * これは、数字の約数を探すプログラムです。
 * 画面から数字が入力されますので、その数字の約数を画面に列挙して下さい。
 * 例えば、画面から「4」と入力された場合、「1,2,4」と表示されます。
 *
 * 正の整数が入力されることを前提条件としてください。
 */

$input_num = 0;
$output_num = array();

for($i = 1; $i <= $input_num; $i++){
	if($input_num % $i == 0){
		array_push($output_num, $i);
	}
}

echo implode(",",$output_num);

//var_dump($output_num);

?>

