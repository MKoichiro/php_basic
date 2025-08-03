<?php
# 関数

# 関数の定義
# 1. 基本形
# 引数と返り値の型は任意だが、指定する方が良い
function greet(string $name): void {
  echo "こんにちは、{$name}さん";
}

# 3. デフォルト引数
# デフォルト引数（オプショナルな引数）は複数指定可能、また最後で無くても良い。
// function connectTo(string $protocol = "http", string $host = "localhost", int $port): string {
//   return "{$protocol}://{$host}:{$port}";
// }
// # 第一、第三引数のみ指定: このような「名前付き引数」は8.0+で利用可能
// echo connectTo(port: 9000);
# ただ普通、必須の引数を先に持ってくる
function connectTo2(int $port, string $protocol = "http", string $host = "localhost"): string {
  return "{$protocol}://{$host}:{$port}";
}
# そうするとシンプルに呼び出せる
echo connectTo2(3000);

# 4. 可変長引数
function sum(int ...$nums): int {
  return array_sum($nums);
}
echo sum(1, 2, 3, 4);
$nums = [10, 11, 12, 13, 12, 99];
echo sum(...$nums);

# 5. 参照返し
# $countの値ではなく、参照を返し外部から操作可能にする。
function &getCounter(): int {
  static $count = 0; // static 修飾子で初回呼び出し時だけ実行
  return $count;
}

echo getCounter(); // 0
$counter =& getCounter(); // $countの参照をもらう
$counter++;               // $countを外部から更新
echo getCounter(); // 1


# 6. 無名関数（= Closureオブジェクト）
# - 無名関数を定義するとClosureクラスのインスタンスとなるため、クロージャとも呼ばれる
# - 無名関数とクロージャとアロー関数
#   言葉として、「無名関数」＝「クロージャ」⊃「アロー関数」の体感。


# 6-1. 通常の無名関数
$double = function(int $x): int {
  return $x * 2;
};
echo $double(2);


# 6-2. アロー関数（7.4+）
# ※ 単一の式しか記述できないという制約があり、jsとは異なる。
$double = fn(int $x): int => $x * 2;
echo $double(5);
# また、引数に外部スコープの変数を渡すことができる
$value = 5;
$mul = fn(int $x, $value): int => $x * $value;
echo $mul;


# 6-3. IIFE(: Immediately Invoke Function Expression; 即時実行関数)
# 6-3-1. 基本形
$doubleResult = (function(int $x): int {
  return $x * 2;
})(3);
echo $doubleResult;

$doubleResult = (fn(int $x): int => $x * 2)(4);
echo $doubleResult;

# 6-3-2. 疑似ブロックとしての利用
# おそらく、Closure導入時に言語仕様上、必然的に、副産物的に許されるようになる書き方
# 仰々しい名前が付いているものの、単に()で評価順を先にしているだけ
# ただし、
# - PHPではブロックでスコープが作られない
# - 一方、関数の仮引数は{}ブロック内でのみ利用可能
# という背景から、IIFEは疑似ブロックの形成として利用価値は見いだせる。

# A. IIFEを使わない場合
$users = ['Alice', 'Bob', 'Carol'];
$greetings = [];
foreach ($users as $user) {
    $greetings[] = "Hello, {$user}!";
}
print_r($greetings);
# foreach(){}はスコープを作らないため、$userにアクセスできる
echo "最後に処理したユーザー: {$user}\n";

# B. IIFEを使う場合
$greetings = (function(): array {
    $users = ['Alice', 'Bob', 'Carol'];
    $out    = [];
    foreach ($users as $user) {
        $out[] = "Hello, {$user}!";
    }
    return $out;
})();
print_r($greetings);
# IIFE 内部のローカル変数は外に漏れない
var_dump(isset($user));  // bool(false)
var_dump(isset($users)); // bool(false) 


# 7. callable型
# 高階関数とは、引数や返り値が関数の関数。
# callableという型ヒントが関数を表すものとして提供されている。
function triple(int $x): int {
  return $x * 3;
}
function highOrderFunction(callable $fn): int {
  return $fn(7);
}
echo highOrderFunction($double);
echo highOrderFunction("triple"); // 関数宣言で定義した関数は文字列で渡す。

# おまけ
# PHPでクラス名はそのまま型名として機能する。
# 試しに、Closureオブジェクトだけ許可すると関数宣言で定義した関数は渡せない。
function highOrderFunction2(Closure $fn): int {
  return $fn(8);
}
echo highOrderFunction2($double);
// echo highOrderFunction2("triple"); // Expected type 'closure'. Found ''triple''.

# 8. 外部スコープへのアクセス
# ※ 近年重要視される関数の純粋性や疎結合性の観点から、
#    アンチパターンになりつつあるとは想像している。
# 8-1. global修飾子
# - 関数宣言、無名関数どちらでも使える
$counter = 0;
function increment(): void {
  global $counter; // 以降の$counterを外部の$counterであることを明示
  $counter++;
}
increment();
echo $counter; // 1

# 8-2. useキーワード
# - 無名関数のみでサポート
# A. 値渡し
$value = 5;
$mul = function(int $x) use ($value): int {
  return $x * $value;
};
echo $mul(3); // 15
# アロー関数では、そもそもuseを使わずに直接使用可能
$value = 5;
$mul = fn(int $x): int => $x * $value;
echo $mul(3);

# B. 参照渡し
# - 参照渡しの場合には、アロー関数は不可。
#   通常の無名関数 + useキーワード で対応するしかない。
$count = 0;
$inc = function() use (&$count): void {
  $count++;
};
$inc();
echo $count; // 1

# 9. 型と関数
# - void型(7.1+):
#   説明省略。
# - mixed型(8.0+):
#   jsのany。あらゆる型を許可。
#   基本忌避すべきで、処理内容が汎用的な関数でのみ使用するのが良い。
function debug(mixed $value): void {
  var_dump($value);
}
echo debug(1); echo debug("test");
# - ユニオン型(8.0+)と型ガード
function format(int|string $value): string {
  return match (true) {
    is_int($value)   => sprintf('%d 件', $value),
    is_string($value)=> $value,
  };
}
echo format(10); echo format("10件");
