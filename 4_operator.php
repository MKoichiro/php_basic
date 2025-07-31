<?php
// 基本演算子 ------------------------------------------
$a = 10;
$b = 3;

// 代数演算子: +, -, *, /, %, **
echo $a + $b;    // 足し算: 13
echo "\n";
echo $a - $b;    // 引き算: 7
echo "\n";
echo $a * $b;    // 掛け算: 30
echo "\n";
echo $a / $b;    // 割り算: 3.3333...
echo "\n";
echo $a % $b;    // 剰余: 1
echo "\n";
echo $a ** $b;   // 累乗: 1000

echo "\n\n";

// インクリメント/デクリメント: ++, --
echo ++$a;       // 前置インクリメント: 11
echo "\n";
echo $b--;       // 後置デクリメント（式の評価後に減少）: 3
echo " (now b={$b})\n";

echo "\n";

// 文字列演算子: .
$str1 = "Hello";
$str2 = "World";
echo $str1 . " " . $str2;  // Hello World

echo "\n\n";

// 代入演算子: +=, -=, *=, /=, %=, .=
$c = 5;
$c += 2;  // $c = 7
$c *= 3;  // $c = 21
echo "$c\n";

// 比較演算子: >, <, <=, >=, ==, !=, ===, !==
var_dump($a > $b);   // bool(true)
var_dump($a == $b);  // bool(false)
var_dump($a === "11"); // 型も比較: bool(false)

echo "\n";

// 論理演算子: &&, ||, !, xor
var_dump(($a > 0) && ($b > 0)); // bool(true)
var_dump(($a < 0) || ($b > 0)); // bool(true)
var_dump(!($a < 0));            // bool(true)

echo "\n\n";

// 発展演算子 ------------------------------------------

// ビット演算子: &, |, ^, ~, <<, >>
// ~以外は複合代入演算子あり: &=, |=, ^=, <<=, >>=
$m = 6; // 110
$n = 3; // 011
var_dump($m & $n);  // AND: 2 (010)
var_dump($m | $n);  // OR: 7 (111)
var_dump($m ^ $n);  // XOR: 5 (101)
var_dump(~$m);      // NOT: -7 (two's complement)
var_dump($m << 1); // 左シフト: 12 (1100)
var_dump($m >> 2); // 右シフト: 1 (001)

echo "\n";

// エラー制御演算子: @
// 存在しないファイル読み込みの抑制
$content = @file_get_contents('no_such_file.txt');
var_dump($content); // bool(false), Notice 抑制

echo "\n";

// 実行演算子 (バッククオート)
// 現在のディレクトリ一覧を取得
$ls = `ls -1`;
echo "Files:\n" . $ls;

echo "\n";

// 配列演算子: +
// 配列の結合
$arr1 = ['a' => 1, 'b' => 2];
$arr2 = ['b' => 3, 'c' => 4];
$union = $arr1 + $arr2; // キーの重複は無視
var_dump($union);

echo "\n";

// 型演算子
class Foo {}
$obj = new Foo();
var_dump($obj instanceof Foo); // bool(true)

echo "\n";

$result;
$truthy = true;
$falsy = false;
$nullable = null;
// Elvis演算子: ?:
// jsで言うところの||の短絡評価
$result = $falsy || "default"; // これは単に($falsy || true)の結果の真偽値が代入される
echo $result; // 1 (trueをechoすると1)
echo "\n";
$result = $falsy ?: "default";
echo $result; // default

echo "\n";

// 三項演算子
// jsの&&の短絡評価は無いので常に三項演算子で表現する
$result = $truthy ? "true" : "false";
echo $result; // true

echo "\n";

// NULL合体演算子: ??
// nullableの値の場合は後ろを採用
$result = $nullable ?? "nullable";
echo $result; // nullable
echo "\n";
$result = $falsy ?? "falsy";
echo $result; // $falsy = falseが入った$resultをechoするので空文字

// スプレッド演算子 ----------------------------------
// 配列展開 (PHP 7.4+)
$arrA = [1, 2];
$arrB = [0, ...$arrA, 3];
var_dump($arrB);  // array(4) { [0]=> int(0) [1]=> int(1) [2]=> int(2) [3]=> int(3) }

// 引数アンパック (PHP 5.6+)
function sum($x, $y, $z) { return $x + $y + $z; }
$args = [1, 2, 3];
echo sum(...$args);  // 6
