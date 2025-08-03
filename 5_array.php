<?php

/*
  配列
  - 他言語と特に異なり特徴的なこと、
  - 他言語と共通した操作方法、
  を中心に解説
*/

/*
  PHPにおいて、他言語で言うところの「配列」は実は存在しない。
  PHPの配列は連想配列の一種であり、どちらもarray型。
  配列は連想配列の内の、キーが正の整数値のものであり、「添え字付き配列」などとも呼ばれる。
*/

# 定義１: 連想配列のリテラルを省略せず記述
$ages = [0 => 20, 1 => 30, 2 => 99, 3 => 59];
# 定義２: 配列のリテラル（１のショートハンドを利用）
$names = ["Betty", "Andy", "Mike", "Carol"];
$ages = [20, 30, 99, 59];
# 定義３: range()を用いて整数列を生成
$numbers = range(2, 100, 2);
// var_dump($numbers); // {[0] => int(2), ... [49] => int(100)}

# 要素の取得
// echo $names[0] . PHP_EOL;
// echo $names[3] . PHP_EOL; // Warning:  Undefined array
// echo $names    . PHP_EOL; // Warning:  Array to string conversion
// var_dump($names);

// 要素の更新
$names[2] = "Dave";
// var_dump($names);

# 要素を末尾に追加
$names[] = "Joe";
$ages[] = 3;
// var_dump($names);
// var_dump($ages);
# 配列も結局は連想配列なので、連番ではないキーでも代入できてしまう
$names[100] = "Mike";
$ages[100] = 110;
// var_dump($names);
// var_dump($ages);

# 要素の削除１: unset()
unset($names[100]);
unset($ages[100]);
$namesBackup = $names[1]; $agesBackup = $ages[1]; // バックアップ
unset($names[1]);
unset($ages[1]);
// var_dump($names);
// var_dump($ages);
# => - インデックス0, 2, 3が残り1が不在になる
#    - unsetはvoidを返す（削除した要素は返さない）
#    => あまり使われない、次のarray_sliceが推奨される。
$names[1] = $namesBackup; $ages[1] = $agesBackup; // 復元して次へ

# 要素の削除２: array_splice($array, index, 1)
array_splice($names, 1, 1);
array_splice($ages, 1, 1);
// var_dump($names);
// var_dump($ages);

# 要素の入れ替え
$tmp = $names[1];
$names[1] = $names[2];
$names[2] = $tmp;
// var_dump($names);


# 組み込み関数
# 要素数: count($array)
$length = count($names);
// echo $length . PHP_EOL;

# key配列
$keys = array_keys($names);
// var_dump($keys);
# value配列（特に一般の連想配列で便利）
$values = array_keys($names);
// var_dump($values);

# 結合: array_merge($array1, $array2)
$combined = array_merge($names, $ages);
// var_dump($combined);

# スライス: array_slice($array, start_index, num_of_elems)
# 「非破壊的に取り出す」
# 返り値は第二、第三引数で指定した配列
# 第一引数の配列はそのまま
$extracted = array_slice($names, 1, 2);
// var_dump($extracted);
// var_dump($names);

# スプライス: array_splice($array, start_index, num_of_elems)
# 「破壊的に分ける」
# 返り値は第二、第三引数で指定した配列
# 第一引数の配列にはそれ以外が残る
$extracted = array_splice($names, 1, 2);
// var_dump($extracted);
// var_dump($names);

# マップ: array_map()
$agesAYearLater = array_map(fn($age) => $age + 1, $ages);
// var_dump($agesAYearLater);

# フィルター: array_filter()
$seniors = array_filter($ages, fn($age) => $age >= 65);
// var_dump($seniors);

# リデュース: array_reduce()
$sum = array_reduce($numbers, fn($acc, $n) => $acc + $n, 0);
// echo "sum of \$numbers: $sum\n";

# 合計: array_sum
$sum = array_sum($numbers);
// echo "sum of \$numbers: $sum\n";

# 最大/最小: max()
$max = max($numbers);
$min = min($numbers);
// echo "max among \$numbers: $max\nmin among \$numbers: $min\n";

# ソート１: sort()/rsort()
# ※ 破壊的
# - 値によるソート（キーは保持）
# 昇順: sort()
// var_dump(sort($ages));
// var_dump(sort($names));
# 降順: rsort()
// var_dump(rsort($ages));
// var_dump(rsort($names));

# ソート２: ksort()
# ※ 破壊的
# - キーによるソート
// var_dump(ksort($ages));
// var_dump(ksort($names));

# 先頭追加: array_unshift(array, value1, value2, ...)
$currentCounts = array_unshift($names, "Mike", "Takeshi");
// echo "[unshifted] current number of elements: $currentCounts\n";
// var_dump($names);

# 先頭削除: array_shift(array): length of array after shifted
$deleted1 = array_shift($names);
$deleted2 = array_shift($names);
// echo "deleted: $deleted1 & $deleted2 " . PHP_EOL;
// var_dump($names);

# 末尾追加: array_push(array, value1, value2, ...)
$currentCounts = array_push($names, "Mike", "Takeshi");
// echo "[pushed] current number of elements: $currentCounts\n";
// var_dump($names);

# 末尾削除: array_pop(array): length of array after popped
$deleted1 = array_pop($names);
$deleted2 = array_pop($names);
// echo "deleted: $deleted1 & $deleted2 " . PHP_EOL;
// var_dump($names);

# キーが狂った配列をリインデックス
$weird = [3 => 'apple', 'x' => 'cherry', 42 => 'banana'];
$reindexed = array_values($weird);
// var_dump($reindexed);


# 分割代入
[$first, $second] = $names;
// echo $first . PHP_EOL; echo $second . PHP_EOL;

# スプレッド（引数展開も可）
$merged = [...$names, ...$ages];
// var_dump($merged);


# ChatGPTによる「その他のトピック」
// 値からキーを検索
// $idx = array_search($needle, $array, true); // 厳密一致

// 配列の逆順
// $rev = array_reverse($array);

// 配列同士を組み合わせ
// $combined = array_combine($keys, $values);

// 要素ごとにコールバック実行（戻り値は無視）
// array_walk($array, fn(&$v, $k) => $v = trim($v));

// 深い階層も含めた再帰処理
// array_walk_recursive($multiDim, fn(&$v) => $v = strtoupper($v));
