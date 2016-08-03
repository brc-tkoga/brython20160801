<?php

// 標準入力から指定された上端の数値配列
$verticals = explode(",", trim(fgets(STDIN)));
// 標準入力から指定された左端の数値配列
$horizons = explode(",", trim(fgets(STDIN)));

$cells = array ();

// 表作成
foreach($horizons as $h_index=>$horizon)
{
	echo "|";
	foreach($verticals as $v_index=>$vertical)
	{
		echo ($horizon + $vertical) . "|";
	}
	echo "\n";
}

// 初期化
$route = array ();
$cur_v_index = 0;
$cur_h_index = 0;

// 右下端までループ
while ( $cur_v_index < count($verticals) || $cur_h_index < count($horizons) )
{
	// 今いるマスのインデックスと、合計を配列として確保
	array_push($route, array (
			intval($cur_v_index),
			intval($cur_h_index),
			intval($verticals [$cur_v_index] + $horizons [$cur_h_index])
	));

	$next_v_sum = 99;
	$next_h_sum = 99;

	// 次の移動が右端に達しない場合
	if($cur_v_index + 1 < count($verticals))
	{
		// 右に進んだ場合の合計
		$next_v_sum = $verticals [$cur_v_index + 1] + $horizons [$cur_h_index];
	}

	// 次の移動が右端まで移動した場合
	if($cur_h_index + 1 < count($horizons))
	{
		// 下に進んだ場合の合計
		$next_h_sum = $verticals [$cur_v_index] + $horizons [$cur_h_index + 1];
	}

	//終端に達した場合
	if($next_v_sum == 99 && $next_h_sum == 99)
	{
		$cur_v_index ++;
		$cur_v_index ++;
		break;
	}
	// 右に進んだほうが最小になる場合
	elseif($next_v_sum <= $next_h_sum)
	{
		$cur_v_index ++;
	}
	// 下に進んだほうが最小になる場合
	elseif($next_v_sum > $next_h_sum)
	{
		$cur_h_index ++;
	}
}

var_dump($route);
?>

