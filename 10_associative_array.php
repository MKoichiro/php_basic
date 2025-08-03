<?php

# 連想配列

# 0. 特徴
# - 添え字付き配列も含め、順序は保証される

# 1. 宣言
# 1-1. リテラル（5.4+）
$fruits = [
  "apple" => "りんご",
  "banana" => "ばなな",
  "cherry" => "さくらんぼ",
];
# 1-2. array()
$scores = array("Mike" => 90, "Taro" => 28, "Mary" => 0);

# 2. キー
# - 許容される型は、int/stringのみ
# - キーをint型とすると、どちらかというと「（添え字付き）配列」と呼ばれる。
# - キーの自動キャスト
#   Numericなstringをキーに使うと、自動的にintに変換される。
$arr = ["1" => "one", "002" => "two"];
var_dump(array_keys($arr)); // [1, 2]

# 3. 基本操作
# 3-1. 取得
echo $fruits["banana"] . PHP_EOL;
# 3-2. 存在確認
# - isset()
var_dump(isset($fruits["peach"])); // bool(false)
# - array_key_exist()
var_dump(array_key_exists("banana", $fruits)); // bool(true)
# 3-3. 追加・上書き
$fruits["peach"] = "桃";
var_dump($fruits);
$fruits["peach"] = "もも";
var_dump($fruits);
# 3-4. 削除
unset($fruits["peach"]);

# 4. foreach
# - キーと値のループ
foreach ($fruits as $k => $v) {
  echo "{$key}は日本語で{$value}です。" . PHP_EOL;
}
# - 値だけのループ
foreach ($fruits as $v) {
  echo "値は、{$v}です。" . PHP_EOL;
}
# - キーだけのループ
foreach (array_keys($fruits) as $k) {
  echo "キーは、{$k}です。" . PHP_EOL;
}
# - 参照渡し
#   - array_mapで処理が長くなり可読性が落ちるとき
#   - 要素を条件に応じて「削除」したいとき
$users = [
  ['id' => 1, 'name' => 'alice', 'age' => 30],
  ['id' => 2, 'name' => 'bob',   'age' => 25],
  ['id' => 3, 'name' => 'carol', 'age' => 40],
];
foreach ($users as $idx => &$user) {
    // ① 30 未満なら role を追加
    if ($user['age'] < 30) {
        $user['role'] = 'junior';
    }
    // ② 35 以上なら削除
    if ($user['age'] >= 35) {
        unset($users[$idx]);
    }
}
unset($user);  // 最後の要素への参照をクリア
print_r($users);

# 5. イミュータビリティとコピー
# 5-1. ネストされた（連想）配列
$original = [
  'alice' => [
    'age'   => 30,
    'email' => 'alice@example.com',
  ],
  'bob' => [
    'age'   => 25,
    'email' => 'bob@example.com',
  ],
];
# 5-1-1. CoWによる疑似ディープコピー
# 普通に変数に代入すると疑似的にディープコピーしてくれる。
#   内部的なしくみはCoW(: copy-on-write)最適化と呼ばれ、
#   PHPではstring型とarray型に限りこれがはたらく。
#   代入文でデータは共有されるが、丸々コピーしてメモリを2倍消費するのではなく、
#   更新がかけられたとき、その要素だけ新たにメモリ領域を確保する仕組み。
$copy = $original;
$copy["alice"]["age"] = 44;
echo $original["alice"]["age"] . PHP_EOL; // 30
# 5-1-2. 参照渡しでシャローコピー
# 技術的に可能だが、単にエイリアスを作るだけであまり用途もなさそうなので省略。

# 5-2. オブジェクトを含む（連想）配列
$obj      = new stdClass();
$obj->id  = 1;
$original = ['user' => $obj];
# 5-2-1. シャローコピー
# 普通に変数に代入すると、配列自体は複製されるが、要素のオブジェクトは共有される。
$copy = $original;
$copy['user']->id = 2;
echo $original['user']->id . PHP_EOL;  // 2   ← 同じインスタンス
# 5-2-2. ディープコピー
# 簡易的にはシリアライズ、デシリアライズで対応可能
# 大きな連想配列ではメモリコストが高めであるのが弱点
#   => 「外部ライブラリを使う」か、
#      「ディープコピーの関数を自作する」か、
#      「そもそも連想配列として持つのではなく、Generatorやクラスで代替できるか検討する」
$copy = unserialize(serialize($original));
$copy['user']->id = 3;
echo $original['user']->id . PHP_EOL;  // 2   ← 元は変わらない

# 分割代入
$scores = [
  "english" => ["Mike" => 90, "Taro" => 20, "Mary" => 12],
  "math" => ["Mike" => 1, "Taro" => 100, "Mary" => 98],
];
["english" => $englishScores, "math" => $mathScores] = $scores;
var_dump($englishScores);

# ArrayObject
# https://chatgpt.com/s/t_688ef709d814819185af66385abe53ee
