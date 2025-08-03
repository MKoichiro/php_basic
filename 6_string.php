<?php
# 文字列

# A. 文字列の定義
# 1. ダブルクォート
# - エスケープ、変数展開が可能
$doubleStr = "'Hello'\n\"PHP\"";
echo $doubleStr;
echo "\n";

# 2. シングルクォート
# - エスケープ、変数展開は不可
$singleStr = '"hello"\n\"php\"';
echo $singleStr;
echo "\n";

# 3. 変数展開
# - ダブルクォートでのみ可
# - 展開する変数の後に、記号以外の文字が続くと、
#   たとえば、「$variableです。」まで変数名として認識されてしまうので、{}で囲う。
# - 式展開ではなく変数展開というだけあり、
#   テンプレート内で式を評価する仕組みは標準では用意されていない
$val1 = 0b1010;
$val2 = 0b1100;
echo "\$val1: $val1";
// echo "\$val1は$val1です。"; // エラー
echo "\n";
echo "\$val1は{$val1}です。";
echo "\n";
echo "sum: ($val1 + $val2)";
echo "\n";
echo "sum: " . ($val1 + $val2);

echo "\n";
echo "\n";

# 4. ナウドキュメント/ヒアドキュメント
# ↓$hereDoc = <<< EOMと同じ
$hereDoc = <<< "EOM"
  開始識別子を""で囲うか、囲わない場合ヒアドキュメントになる。
  "Here"ドキュメントとは、外部ファイルではなくあえて「ここ」に定義するドキュメントという意味で、
  比較的長い複数行に渡る文字列を定義するのに使う。
  インデントや改行がそのまま認識される。という特徴がある。

  識別子は開始と終端でそろっていればなんでもよい。
    -> ヒアドキュメント自体は他環境にもあり、
       一般的には慣習的にEOFが識別子に使われるが、phpではEOMをよく見る。
  phpでは、終端識別子のインデント分、行頭インデントは無視される。
  また、開始識別子をそのまま書くか、""で囲う場合、
  「\$val: {$val1}」のように変数展開やエスケープが認識される。

  EOM;
echo $hereDoc;

echo "\n";

$nowDoc = <<< 'EOF'
  ''で開始識別子を囲うと、「ナウドキュメント」になる。
  変数展開や\nなど\によるエスケープに対応していない。
  それ以外はヒアドキュメントと同じ。
  なお、ナウドキュメントという単語は、php用語で他では使われないし、
  そもそも他環境ではこのように機能がわけて提供されていることもないと思う。
  語源もいまいちわからない。
EOF;
echo $nowDoc;

echo "\n";
echo "\n";

# B. 文字列演算子: .
# 1. 文字列との連結
$greeting = "Hello ";
$name = "Andy";
$message = $greeting . $name;
echo $message;

# 2. 文字列以外との連結
$age = 20;
$message .= $age;
echo $message . PHP_EOL;

# C. 組み込み関数
# 1. 「長さ」
# - strlen( string $string ): int
#   -> 「バイト長」を返す
# - mb_strlen( string $str [, string $encoding = mb_internal_encoding() ]): string
#   -> マルチバイトを加味した「文字数」返す

# バイト長は、一部の文字はエンコーディング方式によって変わる。
# コード内のリテラル文字列の場合はソースファイルの文字コードに、
# 外部から読んだ文字列の場合は読み込んだバイト列のエンコーディングに依存する。
# mb_strlen() は第二引数でエンコーディングを明示できるので、
# UTF-8 以外のファイルやバイナリデータを扱う場合はこちらを使うと安全。

# ASCII only
$str = "abc!";
echo strlen($str) . PHP_EOL;    // 4 byte
echo mb_strlen($str) . PHP_EOL; // 4 chars
# CJK統合漢字面を含む場合
$str = "𰻞𰻞麺";
echo strlen($str) . PHP_EOL;    // 11 = 4byte x 2chars + 3byte
echo mb_strlen($str) . PHP_EOL; // 3 chars
# 基本多言語面の場合
$str = "ビャンビャンメン";
echo strlen($str) . PHP_EOL;    // 24 = 3byte x 8chars
echo mb_strlen($str) . PHP_EOL; // 8 chars
# UTF-32はすべての文字を4byte固定長で表現する
# 出力される6は、24byteが、UTF-32だとするなら6文字分に当たるということ。
echo mb_strlen($str, "UTF-32") . PHP_EOL; // 6

# 2. 部分文字列
# - substr(string $string, int $offset, ?int $length): string
# - mb_substr(string $string, int $start, ?int $length, ?string $encoding): string
echo substr("abcdefg", 2, 4) . PHP_EOL;       // cdef
echo mb_substr('あいうえお', 1, 2) . PHP_EOL; // いう
echo substr('あいうえお', 1, 2) . PHP_EOL;    // ��

# 3. 配列 <=> 文字列 の変換
# - implode(array|string $separator = "", ?array $array): string
#   -> 連結
# - explode(string $separator, string $string, int $limit = PHP_INT_MAX): string[]
#   -> 分割
$colors = ["red", "green", "blue"];
$csv = implode(",", $colors);
echo $csv . PHP_EOL;
$colors = explode(',', $csv);
print_r($colors);

# 4. トリミング
# - trim(string $string, string $characters = " \t\n\r\0\x0B"): string
#   -> 前後の空白・改行を除去
# - ltrim(string $string, string $characters = " \n\r\t\v\0"): string
#   -> 左側のみ
# - rtrim(string $string, string $characters = " \n\r\t\v\0"): string
#   -> 右側のみ
$text = "\t  　Hello World　  \t\n";
echo '"' . trim($text)  . '"' . PHP_EOL; // "　Hello World　"
echo '"' . ltrim($text) . '"' . PHP_EOL; // "　Hello World　    ↲"
echo '"' . rtrim($text) . '"' . PHP_EOL; // "     　Hello World　"
# ※ デフォルトで、半角スペース、タブ文字、改行が対象。
# 第二引数で全角スペースも加えることで削除可↲
echo '"' . trim($text, " \t\n\r\0\x0B　")  . '"' . PHP_EOL; // "Hello World"

# 5. 大文字／小文字変換
# - strtoupper(string $string): string
#   -> すべてのASCII文字を大文字に変換
# - strtolower() ... 省略
# - ucwords(string $string, string $separators = " \t\r\n\f\v"): string
#   -> タイトルケースに変換。第二引数で区切り文字も指定可
$message = "php Is fun!";
echo strtoupper($message) . PHP_EOL;          // "PHP IS FUN!"
echo strtolower($message) . PHP_EOL;          // "php is fun!"
echo ucwords($message) . PHP_EOL;             // "Php Is Fun!"
echo ucwords("red|green|blue", "|") .PHP_EOL; // "Red|Green|Blue"
# - mb_convert_case()
#   -> マルチバイト文字列（ギリシャ文字やウムラウト）の大文字、小文字変換
$text = "straße und tür";
echo mb_convert_case($text, MB_CASE_UPPER, 'UTF-8')  . PHP_EOL; // "STRASSE UND TÜR"
echo mb_convert_case($text, MB_CASE_LOWER, 'UTF-8')  . PHP_EOL; // "straße und tür"
echo mb_convert_case($text, MB_CASE_TITLE, 'UTF-8')  . PHP_EOL; // "Straße Und Tür"

# 6. 単純検索
# - strpos(string $haystack, string $needle, int $offset = 0): int|false
#   -> 大文字小文字を区別して検索し、開始インデックスを返す。
# - stripos(string $haystack, string $needle, int $offset = 0): int|false
#   -> 大文字小文字を無視して検索
# - strstr(string $haystack, string $needle, bool $before_needle = false): string|false
#   -> マッチした文字列以降を返す。検索は大文字小文字を区別する。
$haystack = 'Hello World!!!';
var_dump(strpos($haystack, 'World'));  // int(6)
var_dump(stripos($haystack, 'world')); // int(6)
echo strstr($haystack, 'World') . PHP_EOL;       // "World!!!"
echo strstr($haystack, 'World', true) . PHP_EOL; // "Hello "
# マルチバイト対応
# - mb_strpos(string $haystack, string $needle, int $offset = 0, ?string $encoding): int|false
#   -> マルチバイト文字列を文字単位で検索
# - mb_strstr(string $haystack, string $needle, bool $before_needle = false, ?string $encoding): string|false
#   -> マッチ以降を取得。（検索は大文字小文字を区別する。）
$mb = 'こんにちは世界';
echo mb_strpos($mb, '世界') . PHP_EOL;       // 5
echo mb_strstr($mb, '世界') . PHP_EOL;       // "世界"
echo mb_strstr($mb, '世界', true) . PHP_EOL; // "こんにちは"

# 7. 置換
# - str_replace(array|string $search, array|string $replace, array|string $subject, &$count): array|string
#   -> 単純置換（大文字小文字区別）
# - str_ireplace(array|string $search, array|string $replace, array|string $subject, &$count): array|string
#   -> 大文字小文字を無視して置換
$text = 'apple banana Apple';
echo str_replace('apple', 'orange', $text) . PHP_EOL;  // "orange banana Apple"
echo str_ireplace('apple', 'orange', $text) . PHP_EOL; // "orange banana orange"

# 8. 正規表現
# - preg_replace(array|string $pattern, array|string $replacement, array|string $subject, int $limit = -1, &$count): array|string|null
# - preg_match(string $pattern, string $subject, &$matches, int $flags = 0, int $offset = 0): int|false
#   -> 最初にマッチした部分だけ取得。第三引数にマッチ文字列を格納するarrayを参照渡しする。返り値は○○。
# - preg_match_all(string $pattern, string $subject, &$matches, int $flags = 0, int $offset = 0): int|false
#   -> 全てのマッチを取得。第三引数にマッチ文字列を格納するarrayを参照渡しする。返り値はヒット件数。
# - preg_split(string $pattern, string $subject, int $limit = -1, int $flags = 0): array|false
#   -> パターンで分割して配列を返す
$text = 'abc123def456ghi';
$regex = '/\d+/';
echo preg_replace($regex, '#', $text) . PHP_EOL;    // "abc#def#ghi"
if (preg_match($regex, $text, $m)) {
  echo $m[0] . PHP_EOL;   // "123"
}
echo preg_match_all($regex, $text, $all) .PHP_EOL; // 2
print_r($all[0]);                                  // ["123", "456"]
print_r(preg_split($regex, $text));                // ["abc", "def", "ghi"]

# 9. 書式付き文字列
# - sprintf(string $format [, mixed $... ]): string
#   -> フォーマットに合わせて文字列を返す
# - printf(string $format [, mixed $... ]): int
#   -> フォーマットに合わせて出力し、出力バイト数を返す
# ※ %*は他にも多く用意されているので別途解説する。
# ※ 123,456,789のような表示はnumber_formatを使うのが良い。これもどこか別で解説する。
$id = 7;
$price = 1234.5;
$userName = "Taro";
# a. 数値をゼロ埋めしてフォーマット
echo sprintf("ID: %04d", $id) . PHP_EOL;      // "ID: 0007"
# b. 浮動小数点数を小数点以下2桁で表示
echo sprintf("価格: %.2f 円", $price) . PHP_EOL;  // "価格: 01234.50 円"
# c. 応用
echo sprintf("ID %04d, %6sさんの購入金額: %05d.2f円です。", $id, $userName, $price) . PHP_EOL;

# printf で直接出力し、戻り値（出力バイト数）を取得
$count = 5;
$bytes = printf("Count: %d items", $count);
echo PHP_EOL . "printf が出力したバイト数: " . $bytes . PHP_EOL;

# 10. シリアライズ
# シリアライズとは、プログラミング言語が扱うオブジェクトやデータ構造を
# 主に文字列に変換する処理。

# 以下はPHPでの操作が前提で、セッションストレージ、キャッシュなどに保存する場合やログ用途での利用が多い。
# - serialize(mixed $value ): string
#   -> データ構造をPHPがunserializeでデコードできるように文字列化
# - unserialize(string $str [, array $options ]): mixed
#   -> serialize() した文字列を元のデータに戻す
$data = ['id' => 1, 'name' => 'Taro', 'scores' => [90, 80, 70]];
$serialized = serialize($data);
$restored = unserialize($serialized);
echo $serialized . PHP_EOL;  // a:3:{s:2:"id";i:1;s:4:"name";s:4:"Taro";s:6:"scores";a:3:{i:0;i:90;i:1;i:80;i:2;i:70;}}
var_dump($restored);         // array(3) { ... }

# 以下は、フロントエンドとの通信や公開APIなど外部配布を考慮する必要があるときに使用する。
# - json_encode(mixed $value [, int $options = 0 [, int $depth = 512 ]]): string
#   -> JSON形式の文字列に変換
# - json_decode(string $json [, bool $assoc [, int $depth = 512 [, int $options = 0 ]]]): mixed
#   -> 逆処理
$json = json_encode($data);
echo $json . PHP_EOL;        // {"id":1,"name":"Taro","scores":[90,80,70]}
# 連想配列としてデコード
$assoc = json_decode($json, true);
var_dump($assoc);            // array(3) { ... }
# オブジェクトとしてデコード
$obj = json_decode($json);
var_dump($obj);              // class stdClass { ... }

# 以下は今後勉強する。
# 11. HTML エスケープ
# htmlspecialchars(), htmlentities()
# 12. エンコーディング変換
# mb_convert_encoding()
# 13. 内部エンコーディング設定
# mb_internal_encoding()
# 14. グラフェムクラスタ処理（Unicode 正規化）
# grapheme_strlen(), grapheme_substr()（intl 拡張）
# 15. バイナリ／バイナリセーフ関数
# バイナリセーフ版
