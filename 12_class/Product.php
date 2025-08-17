<?php

enum ProductStatus: string {
  case AVAILABLE = 'available';
  case NOT_YET_RELEASED = 'not_yet_released';
  case DISCONTINUED = 'discontinued';
}

enum StockOperation: string {
  case INCREMENT = 'increment';
  case DECREMENT = 'decrement';
}

// PhysicalProductとDigitalProductの抽象クラス
abstract class Product {
  private int $id;
  protected string $name;
  protected ?int $price;
  protected ProductStatus $status;

  // ※ メモリ的に不利なので実務では多分アンチパターン
  /** @var Product[] */
  private static array $products = [];

  public function __construct(string $name, ?int $price, ProductStatus $status) {
    $id = random_int(1000, 9999); // 疑似的なid
    $this->id = $id;
    $this->name = $name;
    self::$products[$id] = $this;
    $this->status = $status;
    $this->setPrice($price);
  }

  // getter
  public function getId(): int {
    return $this->id;
  }
  public function getName(): string {
    return $this->name;
  }
  public function getPrice(): int | null {
    return $this->price;
  }
  public function getStatus(): ProductStatus {
    return $this->status;
  }
  // 商品全件を返すクラスメソッド
  public static function getProducts(): array {
    return self::$products;
  }

  // setter
  public function setName(string $name): void {
    $this->name = $name;
    self::$products[$this->id] = $name;
  }
  protected function setPrice(?int $price): void {
    if (is_null($price)) {
      if ($this->status === ProductStatus::AVAILABLE) {
        throw new InvalidArgumentException("販売中の（availableな）商品の場合、{$price}は必須です。");
      } else {
        $this->price = null;
        return;
      }
    }
    if ($price < 0) {
      throw new InvalidArgumentException("{$price}は0以上で設定してください。");
    }

    $this->price = $price;
  }

  abstract public function sell(int $amount = 1): void;
}

class PhysicalProduct extends Product {
  private int $stock;
  public function __construct(
    string $name,
    ?int $price,
    ProductStatus $status,
    int $stock
  ) {
    parent::__construct($name, $price, $status);
    $this->stock = $stock;
  }

  public function getStocks(): int {
    return $this->stock;
  }

  public function changeStocks(int|StockOperation $type): void {
    switch ($type) {
      case StockOperation::INCREMENT: {
        $this->stock++;
        return;
      }
      case StockOperation::DECREMENT: {
        if ($this->stock > 0) $this->stock--;
        return;
      }
      default: {
        if ($type === 0) return;
        $this->stock += $type;
      }
    }
  }

  public function sell(int $amount = 1): void {
    if ($this->stock < $amount) {
      echo "現在在庫切れです。";
      return;
    } else if ($amount <= 0) {
      throw new RuntimeException("1以上の数量を指定してください。");
      return;
    }
    $this->changeStocks(-$amount);
    $salesAmount = $this->price * $amount;
    // 管理者へ通知する（疑似）処理
    echo "{$this->name}を{$amount}個販売しました。この取引の売り上げは{$salesAmount}円で、残りの在庫は{$this->stock}個です。";
  }
}


class DigitalProduct extends Product {
  // ダウンロードリンクの生成方法は商品によって異なるかもしれないので、
  // インスタンス生成時に指定してもらう。
  // callableはプロパティの型としてサポートされていないらしいのでPHPDocで代用
  /** @var callable():string */
  private $downloadLinkIssuer;

  public function __construct(
    string $name,
    ?int $price,
    ProductStatus $status,
    callable $downloadLinkIssuer
  ) {
    parent::__construct($name, $price, $status);
    $this->downloadLinkIssuer = $downloadLinkIssuer;
  }

  private function issueDownloadLink() {
    // 疑似発行処理。
    $issuer = $this->downloadLinkIssuer;
    return $issuer();
  }

  public function sell(int $amount = 1): void {
    if ($amount !== 1) {
      echo "Warning: {$amount}が指定されましたが、デジタル商品は数量指定ができません。";
    }
    $link = $this->issueDownloadLink();
    // 管理者へ通知する（疑似）処理
    echo "{$this->name}を販売しました。";
    // ユーザーへ通知する（疑似）処理
    echo "{$link}より、{$this->name}をお受け取り下さい。";
  }
}
