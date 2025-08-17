<?php

# ファイル I/O

# 使うディレクトリの指定
$dir = './io_test/';

# 共通する事項

# A. include_path について
# どちらかというと、include/require文などのインポート時に、サーチ先のディレクトリ（＝ライブラリ置き場）を指定するための
# include_pathという内部設定値がある（php.iniやget_include_path()で確認できる。）
echo get_include_path() . PHP_EOL;

# file I/Oにおいても、file_get_contents()やfile()など、多くの読み込み系関数で引数に指定することができるのだが、
# 通常はライブラリ置き場から文字情報としてファイルを読み込むことは無いため、基本的に使わない。

# また、include_pathにディレクトリを追加すれば、技術的には各関数のstring $filename引数に指定するパスを短縮できるが、
# やはり、これは本来「コードの読み込み（インポート）」を想定した内部設定値なので、
# file I/O目的でinclude_pathを編集するのは設計として筋が悪い。

# B. 警告のハンドリング
# 読み込み系関数の多くはファイルやディレクトリが見つからないとfalseを返し、警告を出力する。
# ユーザー定義の関数内でI/O処理をする場合には、if文などでファイル、ディレクトリの存在性を確認し、
# 存在しない場合には、例外を投げるようにしておくと良い。

# C. メモリ効率
# 一般に、単に出力するだけであれば、全体を一挙に取得する関数を用いるより、ループ処理で、行単位で出力する方がメモリ効率は上がる。
# ループ処理では、基本的に毎回同じ変数に値を代入するので、過去のイテレーションでの参照はクリアされるからである。
# なお、ループでも、一行ずつ配列に格納してしまうなら、結局のところメモリはファイルサイズ分きっちり占有される。
# 一行を要素とした配列を作りたいなら、配列ライクに扱え、ストリーム処理を備えた、Generatorの使用を検討すると良い。


# 1. file_put_contents(string $filename , mixed $data [, int $flags = 0 [, ?resource $context = null ]]): int|false
# パスで指定する中間ディレクトリが存在しなければ、副作用的に作成する。
# 2. file_get_contents(
#     string $filename
#     [, bool $use_include_path = false [, ?resource $context = null [, int $offset = 0 [, ?int $maxlen = null ]]]]): string|false

# e.g.) 1. 
$path = "{$dir}file_put_contents_string.txt";
$bytes = file_put_contents(
  $path,                  // string $filename: 相対パスで指定
  "こんにちは。\n",       // mixed $data: ここではstring
  FILE_APPEND | LOCK_EX,  // int $flag: 「① （デフォルトの上書きではなく）追記」、「② 並列処理対策の排他ロック」
);
echo $bytes . PHP_EOL;

$read = file_get_contents($path);
echo $read . PHP_EOL;

# e.g.) 2. 
$path = "{$dir}file_put_contents_array.txt";
$data = ['name' => 'Alice', 'age' => 30];
file_put_contents(
  $path,
  json_encode($data, JSON_PRETTY_PRINT),  // 連想配列をjson化したstring
);

$read = file_get_contents($path);
echo $read . PHP_EOL;


# 3. file(string $filename [, int $flags = 0 [, ?resource $context = null ]]): array|false
$read = file($path);
var_dump($read);
$read = file($path, FILE_IGNORE_NEW_LINES);
var_dump($read);

# 4. bool mkdir(
#     string $pathname,             // 作成したいディレクトリのパス
#     int    $mode = 0777,          // パーミッション（8進数指定）
#     bool   $recursive = false,    // true にすると再帰的に親ディレクトリも作成
#     resource|null $context = null // ストリームコンテキスト（ほとんど使わない）
#    );
$path = "{$dir}sub/subsub/";
if (!is_dir($path)) {
  mkdir($path, 0777, true); // 0777の先頭の0は8進数リテラルの0である点に注意
} else {
  echo "{$path}は既に存在します。削除してから実行してください。" . PHP_EOL;
}

# - fopen(string $filename , string $mode [, bool $use_include_path = false [, ?resource $context = null ]]): resource|false
# - fclose(resource $stream ): bool
# - fwrite(resource $stream , string $data [, ?int $length = null ]): int|false

# 5. fopen, fwrite, fgets, fread, fclose

// 書き込み用ファイルパスを設定
$path = "{$dir}fopen_test.txt";

// fopen でファイルを開く
// モードの主な例:
// 'r'   : 読み込み専用
// 'w'   : 書き込み専用（既存ファイルの場合内容を完全に削除して開く）
// 'x'   : 書き込み専用（新規作成のみ。存在すると失敗）
// 'r+'  : 読み込み／書き込み
// 'w+'  : 読み込み／書き込み
// 'x+'  : 読み込み／書き込み
// 'a'   : 追記モード（ファイル末尾に追加）
// 'a+'  : 読み込み／追記
$handle = fopen($path, 'w+');
if ($handle === false) {
  throw new RuntimeException("ファイルを開けませんでした: {$path}");
}

// fwrite でデータを書き込む
$data = "あいうえお\nかきくけこ\nさしすせそ\n";
$bytes = fwrite($handle, $data);
if ($bytes === false) {
  throw new RuntimeException("データを書き込めませんでした: {$path}");
}

rewind($handle); // ポインタ（カーソルの位置、バイトの位置的な意味）がfwrite直後、末尾になっているので先頭に。
$i = 1;
while (!feof($handle)) {
  $line = fgets($handle);      // 行単位で読み込み
  if ($line === false) {
    // EOF 到達直後やエラー時は false が返るのでブレーク
    break;
  }
  echo "{$i}行目: {$line}";
  $i++;
}

rewind($handle);
echo fread($handle, 9) . PHP_EOL; // （先頭から）9バイト読み込み

// 最後に fclose でストリームを閉じる
fclose($handle);

// 確認のために読み出して画面表示
echo file_get_contents($path) . PHP_EOL;


# 6. SplFileObject
$handle = new SplFileObject("{$dir}SplFileObject_test.csv", "w");
$lines = [
  ["PHP", 1995, "backend"],
  ["JavaScript", 1995, "frontend"],
  ["Python", 1991, "deep learning"],
];

foreach ($lines as $line) {
  $handle->fputcsv($line);
}

# e.g.) 1. SplFileObjectでCSVを読み込み
$handle = new SplFileObject("{$dir}SplFileObject_test.csv"); // デフォルトで"r"
while (!$handle->eof()) { // end of lineでない限り
  $line = $handle->fgetcsv(); // $line[0] => "PHP", $line[1] => "backend", ...
  echo $line[0] . PHP_EOL;
}

# e.g.) 2. READ_CSVフラグを使って読み込む
$handle = new SplFileObject("{$dir}SplFileObject_test.csv"); // デフォルトで"r"
# 定数で$handleのインスタンスメソッドに情報を共有できる。なお、定数は以下４つで全部。
$handle->setFlags(
  SplFileObject::READ_CSV         // csv読み込みモード
  // | SplFileObject::DROP_NEW_LINE  // 行末改行を無視。
  | SplFileObject::READ_AHEAD     // 先読み（"論理的な"ポインタの次行まで読み込む）、巻き戻し（次行まで読み込むためにずれた"物理的な"ポインタを適宜調整）を行う
  | SplFileObject::SKIP_EMPTY     // 空行をスキップ、READ_AHEADも同時に指定しないと正常に動作しない。
);
# 読み込み処理部分はよりすっきり書ける
foreach ($handle as $line) {
  echo $line[1] . PHP_EOL;
}

# e.g.) 3. SplFileObject × Generator で少し大きめ(1万行)のログファイルを解析してみる
$path = "{$dir}access.log";
$bigSizedLogFile = new SplFileObject($path); // デフォルトで"r"
$bytes = filesize(realpath($path)); // 8162481
echo round($bytes / 1024 / 1024, 2) . ' MB' . PHP_EOL;

# 一行ごとストリーム
function genAccessLogs(SplFileObject $handle) {
  while (!$handle->eof()) {
    yield $handle->fgets();
  }
}

# 全アクセスからメソッドでフィルターしてストリーム
function logByMethod(SplFileObject $handle, string $method, int $limit) {
  $i = 0;
  foreach (genAccessLogs($handle) as $log) {
    if (str_contains($log, $method) && $i < $limit) {
      $i++;
      yield $log;
    }
  }
}

foreach (logByMethod($bigSizedLogFile, 'POST', 5) as $i => $line) {
  echo $i + 1 . "件目: " . $line;
}
