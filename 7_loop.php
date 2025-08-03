<?php
# for文
echo "--- for -----------------" . PHP_EOL;
# 固定回数のループ
for ($i = 0; $i < 5; $i++) {
  // echo $i, "\n";
}
# ループ関連の構文ではすべて、
# $iのスコープはループブロック外部にあることに注意
# これは「参照渡し」で重要になる。
// echo "end of for loop\n";
// echo $i .PHP_EOL;

# while文
echo "--- while ---------------" . PHP_EOL;
# 条件駆動のループ
$i = 0;
while ($i < 5) {
  // echo $i, "\n";
  $i++;
}

# do-while文
echo "--- do-while ------------" . PHP_EOL;
# 必ず一度は実行
$i = 0;
do {
  // echo $i, "\n";
  $i++;
} while ($i < 5);

# continueとbreak
echo "--- continue/break ------" . PHP_EOL;
$fruits = ['apple', 'banana', 'cherry'];

# continueでスキップ
foreach ($fruits as $f) {
  if ($f === 'banana') {
    continue;
  }
  // echo $fruit, "\n";
}

# breakで中断して次へ
for ($i = 0; $i < 10; $i++) {
  if ($i === 3) {
    break;
  }
  // echo $i, "\n";
}

# （連想）配列のループ
# 値だけ（配列的なループ）
foreach ($fruits as $v) {
  // echo $v, "\n";
}
# インデックス（キー）と値（連想配列的なループ）
foreach ($fruits as $k => $v) {
  // echo "{$k} => {$v}\n";
}

# 配列駆動のループ
echo "--- array_* function ----" . PHP_EOL;
$nums = [1, 2, 3, 4];

# array_map
$squares = array_map(fn($n) => $n * $n, $nums);
// print_r($squares);  // [1, 4, 9, 16]

# array_filter
$even = array_filter($nums, fn($n) => $n % 2 === 0);
// print_r($even);  // [2, 4]

# array_reduce
$sum = array_reduce($nums, fn($carry, $n) => $carry + $n, 0);
// echo $sum . PHP_EOL;  // 10


# 参照渡し
echo "--- pass by reference ---" . PHP_EOL;
$data = [1, 2, 3];

# 非参照渡しの場合
# $numには1, 2, 3のスカラーが入る
foreach ($data as $num) {
  $num *= 2;
}
// var_dump($data);

# 参照渡しの場合
# $numには値ではなく$data[0], $data[1], $data[2]が保持する参照が入る
foreach ($data as &$num) {
  $num *= 2;
}
// echo $num .PHP_EOL;
# $numには$data[2]の参照が入っているため、
# 意図せず$numを更新すると$dataを破壊することになる
// $num = 0; var_dump($data); // NG

# 以下のように参照をクリアしておけば、$numは別用途で再利用可能。
unset($num);
$num = 0;
// var_dump($data);


# ジェネレーター
echo "--- generator -----------" . PHP_EOL;
# ジェネレーターを使わない場合
foreach (range(1, 3) as $num) {
  echo "$num ";
}
# 結果: 1 2 3

# ジェネレーターを使う場合
# ジェネレーター（を返す関数）を定義
function gen_int(int $max): Generator {
  for ($i = 1; $i <= $max; $i++) {
    yield $i; // ここで処理が一時停止する
  }
}
foreach (gen_int(3) as $num) {
  echo "$num ";
}
# 結果: 1 2 3
/*
  補足
  結果は同じだが、ジェネレーターを使わない場合には1, 2, 3及びこれを格納する配列のフレームがメモリ上確保される。
  これは、range(0, 1_000_000)の場合、100MB以上ものメモリ消費になる。
  一方ジェネレーターを使う場合には、都度生成するストリーム処理なるのでメモリ効率が良い。
 */

# おまけ
# Generatorを渡すfor文では、内部的にcurrent()やnext()を呼んでいるが、構文の内に隠蔽されている。
$generator = gen_int(3);
echo $generator->current() . " "; // 1
$generator->next();
echo $generator->current() . " "; // 2
$generator->next();
echo $generator->current() . " "; // 3
