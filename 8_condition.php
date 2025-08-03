<?php
# if-elseif-else文
echo "--- if-elseif-else -------" . PHP_EOL;
$c = 0;
if ($c > 0) {
  // echo '正の数' . "\n";
} elseif ($c < 0) {
  // echo '負の数' . "\n";
} else {
  // echo 'ゼロ' . "\n";
}

# switch文
echo "--- switch ---------------" . PHP_EOL;
# - == による緩い比較
# - switch"式"は存在しない
# - matchと異なり、いずれのcaseにも該当しない場合、エラーは吐かない。
#   => defaultは省略せず、エラーを投げるなどの対応をするように注意しなければならない。
$fruit = "orange";
switch ($fruit) {
  case "apple":
  case "cherry":
  case "strawberry":
    // echo 'RED' . "\n";
    break;
  case "banana":
  case "lemon":
    // echo 'YELLOW' . "\n";
    break;
  default:
    // echo 'Unknown fruit is passed' . "\n";
}

# match式 (PHP 8.0+)
echo "--- match -----------------" . PHP_EOL;
# - === による厳格な比較
# - "式"なので代入して使う
# - {}で複数行の記述はできない
# - switchと異なり、いずれのケースにも該当しない場合は、エラーを吐く。
$day = 10;
$label = match ($day) {
  1, 2, 3, 4, 5 => '平日',
  6, 7          => '週末',
  default       => 'error',
};
// echo $label . "\n";
# - match (true) {} の用法
$age = 25;
$generation = match (true) {
  (0 <= $age && $age < 12) => 'child',
  (12 <= $age && $age < 19) => 'student',
  (18 <= $age && $age < 60) => 'general',
  (60 <= $age) => 'senior',
  default => 'bad input',
};
// echo $generation . "\n";

# 三項演算子
echo "--- ternary --------------" . PHP_EOL;
$d = 10;
$result = $d > 5 ? '大きい' : '小さい';
// echo $result . "\n";
