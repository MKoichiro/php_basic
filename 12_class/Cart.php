<?php

// 商品IDと数量を紐づける、カートの一行を表現するクラス
class CartItem {
  private Product $product;
  private int $count = 0;

  public function __construct(Product $product, int $count = 1) {
    $this->product = $product;
    $this->count = $count;
  }

  // 基本getter
  public function getProduct(): Product {
    return $this->product;
  }
  public function getCount(): int {
    return $this->count;
  }

  // 基本setter
  public function setCount(int $count): void {
    $this->count = $count;
  }

  // 特殊setter
  public function addCount(int $amount): void {
    $new = $this->count + $amount;
    if ($new <= 0) {
      $this->count = 0;
      return;
    }
    $this->count = $new;
  }
  public function incrementCount(): void {
    $this->addCount(1);
  }
  public function decrementCount(): void {
    $this->addCount(-1);
  }

  // 特殊getter
  // ★ 委譲メソッド
  // $cartItem->getProduct()->getPrice()を、$cartItem->getUnitPrice()に短縮するための定義
  public function getUnitPrice(): ?int {
    return $this->product->getPrice();
  }
  // ★ 小計計算
  public function getSubtotal(): int {
    return (int)$this->getUnitPrice() * $this->count;
  }

}

class Cart {
  /** @var CartItem[] */
  private array $items;
  private int $total;

  // ユーザー作成と同時に、空のカートを作る仕様が普通なので
  // コンストラクタは引数無しで初期化処理のみ。
  public function __construct() {
    $this->items = [];
    $this->total = 0;
  }

  // 基本getter
  public function getItems(): array {
    $this->calculateTotal(); // 更新しておくとより堅牢（？）
    return $this->items;
  }
  public function getTotal(): int {
    return $this->total;
  }

  // Cartは金額を扱うので、直接書き込めるsetterはセキュリティの事由から無くても良い（？）
  // public function setItems(array $items): void {
  //   $this->items = $items;
  // }
  // public function setTotal(int $total): void {
  //   $this->total = $total;
  // }

  public function addItem(Product $product, int $amount): void {
    // ガード処理
    if ($product->getStatus() !== ProductStatus::AVAILABLE) {
      throw new RuntimeException("この商品は販売中ではないため、カートに追加できません。");
    }

    // $itemsの更新
    $productId = $product->getId();
    if (array_key_exists($productId, $this->items)) {
      $this->items[$productId]->addCount($amount);
    } else {
      $items[$product->getId()] = new CartItem($product, $amount);
    }

    // $total を更新
    $this->calculateTotal();
  }

  public function incrementItem(Product $product): void {
    $this->addItem($product, 1);
  }
  public function decrementItem(Product $product): void {
    $this->addItem($product, -1);
  }

  private function calculateTotal(): void {
    $this->total = array_reduce(
      $this->items,
      fn($carry, $item) => $carry + $item->getSubtotal(),
      0
    );
  }

  // public function checkOut() {

  // }
}
