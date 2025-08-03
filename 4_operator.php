<?php
// 基本演算子 ------------------------------------------
$a = 10;
$b = 3;

// 算術演算子: +, -, *, /, %, **
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

# 比較演算子
echo "--- comparison operator ---" . PHP_EOL;
# <, >, <=, >= ... 説明省略
# ==, !=       ... 型は問わず、値の一致でtrue／不一致でtrue
# ===, !==     ... 型と値、両方とも一致でtrue／型と値のどちらか一方でも不一致でtrue。
# <>           ... !=のエイリアス。
# <=>          ... 右辺<左辺で-1, 右辺=左辺で0, 右辺>左辺で1
# instanceof   ... 右辺オブジェクトが左辺クラスのインスタンス、またはサブクラスのインスタンスならtrue

echo "--- int vs string ---" . PHP_EOL;
var_dump(1.1 == '1.1');       // true
var_dump(1 === '1');          // false
var_dump(1 === 1.0);          // false  int vs doubleの比較は厳密には非等価
var_dump((double)1 === 1.0);  // true   キャストして揃えれば厳密に等価
var_dump(65 == "A");          // false  文字コード比較がされるわけでもない
var_dump(chr(65) == 'A');     // true   chr(コードポイント)でstring型の文字列が返る
// 非半角文字列との比較では、数値は先頭一桁の文字コードを示す数値に変換される
var_dump("!" > 100);          // false 1はU+0x49、!はU+0x21 -> 21 > 49 は false
var_dump("A" > 100);          // true  1はU+0x49、AはU+0x65 -> 65 > 49 は true

echo "--- null vs ? ---" . PHP_EOL;
var_dump(null == ""); // true   nullと空文字は等価
# 以下参考程度
# nullは数値より大きいが、0とは等しい
// var_dump(null == 0); // true
// var_dump(null > -1); // false
# 空文字は0と等価だが0より小さく-1より大きい
// var_dump("" == 0);   // false
// var_dump("" < 0);    // true
// var_dump("" > -1);   // false
# 非空文字は0より大きい
// var_dump(0 > "bar"); // false
// var_dump("bar" > 0); // true

echo "--- array vs ? ---" . PHP_EOL;
var_dump([1, 2] == [1, 2]);   // true
var_dump([1, 2] == [4, 5]);   // false
var_dump([1, 2, 3] > [4, 5]); // true 要素数で大小比較
# 配列はオブジェクトに次いで他より「大きい」
var_dump([] > 0);       // true
var_dump([] > "foo");   // true

echo "--- object vs ? ---" . PHP_EOL;
class A { public int $x = 1; }
class B { public int $x = 1; }
$a1 = new A();
$a1_1 = new A();
$a2 = new A(); $a2->x = 2;
$b1 = new B();
# == はプロパティが同一の別インスタンスまで等価とみなす
var_dump($a1 == $a1);     // true
var_dump($a1 == $a1_1);   // true
var_dump($a1 == $a2);     // false 同じクラスでもプロパティが異なれば false
# === は完全に同一のインスタンまで等価とみなす
var_dump($a1 === $a1);    // true
var_dump($a1 === $a1_1);  // false 同じクラス、同じプロパティでもインスタンスが異なれば false
# スカラーや別クラスのインスタンス間の比較はwarningが出るので直接は避ける
// @var_dump($a1 <=> $b1);
// @var_dump($a1 > 0);
// @var_dump(0 <=> $a1);
# その代わりinstanceofでハンドリング込みで比較
$inspector = $b1;
if ($inspector instanceof A) {
  var_dump($inspector <=> $a1);
} else {
  var_dump($inspector <=> $b1);
}
# オブジェクトは非オブジェクトより常に「大きい」
var_dump($a1 > []); // bool(true)


echo "--- bool vs ? ---" . PHP_EOL;
# 一般的な実装パターンに真偽値 vs 他の型で比較をするシーンは多分ないので省略

# 論理演算子
# - &&, ||, !, and, xor, orの全6種のみ。
# - andとorの論理的機能は&&と||と同じ。
# - || vs xor
#   - ||           ... 左右の 「どちらか一方が真」、 「またはどちらも真」 の場合、    全体で真
#   - xor          ... 左右の 「どちらか一方が真」                        の場合のみ、全体で真
# - and, xor, or の優先度は論理演算子だけでなくすべての演算子の中で最下位。
$a = true && false;
$b = true and false;
var_dump($a); // false: $a = (true && false)  → $a は       false
var_dump($b); // true:  ($b = true) and false → $b としては true
$b = true and false;

# PHPでfalsyな値
echo "--- falsy -----------------" . PHP_EOL;
# 真偽値にキャストするとfalseになる値
# jsとほぼ同じ
var_dump(!!0);            // false （0 は false）
var_dump(!!"");           // false （空文字は false）
var_dump(!!"0");          // false （文字列 "0" も false 扱い）
var_dump(!![]);           // false （空配列は false）
var_dump(!!null);         // false
var_dump(!!false);        // false
$undefined;
// @はエラー制御演算子、Notice表示を抑制する
@var_dump(!!$undefined);  // false （初期化前の変数）
@var_dump(!!$unknown);    // false （未定義の変数）
# PHPでtruthyな値
echo "--- truthy ----------------" . PHP_EOL;
# 真偽値にキャストするとtrueになる値
var_dump(!!-1);           // true  （0 以外の数値は true）
var_dump(!!"hello");      // true  （非空文字列は true）
var_dump(!![1,2,3]);      // true  （非空配列は true）
var_dump(!!new stdClass); // true  （オブジェクトは常に true）
# PHPでnull判定される値
echo "--- null equivalent -------" . PHP_EOL;
# null合体演算でnull判定となる値(
# = isset()でfalseが返る値
var_dump(isset($undefined));  // 定義されているが値が代入されていない変数
var_dump(isset($unknown));    // 未定義の変数

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

echo "\n";

# null合体代入演算子
echo "--- null coalescing assign" . PHP_EOL;
$data = [];
$data['key'] ??= 'value';
// print_r($data);

// スプレッド演算子 ----------------------------------
// 配列展開 (PHP 7.4+)
$arrA = [1, 2];
$arrB = [0, ...$arrA, 3];
var_dump($arrB);  // array(4) { [0]=> int(0) [1]=> int(1) [2]=> int(2) [3]=> int(3) }

// 引数アンパック (PHP 5.6+)
function sum($x, $y, $z) { return $x + $y + $z; }
$args = [1, 2, 3];
echo sum(...$args);  // 6

# 単項算術演算子（+/-）
var_dump(+"123");      // int(123)
var_dump(+"12.3abc");  // float(12.3)
var_dump(+true);       // int(1)
var_dump(+"abc");      // int(0) 非数値は 0 に変換

var_dump(-123);        // int(-123)
var_dump(-"123");      // int(-123)
var_dump(-"12.3abc");  // float(-12.3)
var_dump(-true);       // int(-1)
