<?php
// 変数宣言
// - $開始
// - _と英数字が使用可能（ただし$直後に数字はNG）
// - 大文字小文字は別の変数扱いになる
// - 慣習はcamelCaseまたはsnake_case。（定数はUPPER_SNAKE_CASE）

// 通常の宣言
$number;

// 代入
// - 再宣言はエラーにならない
$number = 10;

// 初期化
// $numberとは別の変数という扱い
$numBER = -11.1;

// 変数名に数字を使う例
$isOver100 = false;
// $1tmp; // これは構文エラー

// キャメルケース
$userName = "鈴木";
// スネークケース
$user_name = "田中";

// 未使用の仮引数などのシーンでは$_から始める慣習
$_unused;

// 定数
const MY_CONSTANT = "constant";

// 代入
// - phpは動的型付け言語
$integer = 200;     // int型
$integer = "string";  // ランタイムでstringに動的に変化

echo '$number: ' . $number . "\n";        // 10
echo '$numBER: ' . $numBER . "\n";        // -11.1
echo '$userName: ' . $userName . "\n";    // 鈴木
echo '$integer: ' . $integer . "\n";      // string
echo '$undefined: ' . $undefined . "\n";  // Warning: Undefined variable $undefined ...のエラーがそのまま出力される

?>

<?php
// データ型ごとの宣言
// phpのスカラー型（プリミティブ型）は整数、浮動小数点、文字列、論理値のみ。

// var_dump($variable)
// - 変数のデータ型を出力する組み込み関数
$variable;
var_dump($variable); // 値を代入しない場合はNULL判定

// 数値の宣言

// 整数
$int = 100;

// 浮動小数点型
// - var_dump()で調べるとfloatでも内部的にはIEEE754規定の倍精度小数、いわゆるdouble型（64bit）
$double = 3.14;
var_dump($double);

// n進数
$binary = 0b100;
$octal = 017;
$hexagonal = 0x1F;
echo "0b100: " . $binary . " ";
echo "017: " . $octal . " ";
echo "0x1F: " . $hexagonal;

// 文字列

// 基本
// ダブルクォート
// - エスケープ、変数展開が可能
$doubleStr = "'Hello'\n\"PHP\"";
echo $doubleStr;
// シングルクォート
// - エスケープ、変数展開は不可
$singleStr = '"hello"\n\"php\"';
echo $singleStr;

// 変数展開
// - ダブルクォートでのみ可
// - 展開する変数の後に、記号以外の文字が続くと、
//   たとえば、「$variableです。」まで変数名として認識されてしまうので、{}で囲う。
// - 式展開ではなく変数展開というだけあり、
//   テンプレート内で式を評価する仕組みは標準では用意されていない
$val1 = 0b1010;
$val2 = 0b1100;
echo "\$val1: $val1";
// echo "\$val1は$val1です。"; // エラー
echo "\$val1は{$val1}です。";
echo "sum: ($val1 + $val2)";
echo "sum: " . ($val1 + $val2);

// ヒアドキュメント
$multipleLines1 = <<< EOF
  ヒアドキュメントで
  複数行の文字列を
  定義できる
EOF;
echo $multipleLines1;
$multipleLines2 = <<< "EOM"
  識別子は開始と終端でそろっていればなんでもよい。
  phpではEOMをよく見る。
  また、終端識別子のインデント分、行頭インデントは無視される
  さらに、開始識別子を""で囲うことで変数展開が利用できる。
  ただし、{}で囲わないと変数名の終わりが認識されない。{$val1}は10。

  EOM;
echo $multipleLines2;

// 論理値
// - echoで出力すると、
//   - trueは1
//   - falseは""

$true = true;
$false = false;
var_dump($true);
echo "... " . $true . "\n";
var_dump($false);
echo "... " . $false;
