<?php
class Product{
    public string $name;
    public float $price;
    public string $description;
    public int $id;

    private function __construct($name, $price, $description, $id){
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->id = $id;
    }

    public function getProductsFromCategory($id, $limit = 50){
        $pdo = pdoconnect::getInstance();
        $sql = "SELECT * FROM products WHERE category_id = :id LIMIT :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'limit' => $limit]);
        $res = $stmt->fetchAll();
        $products = [];
        foreach($res as $row){
            $products[] = new Product($row['name'], $row['price'], $row['description'], $row['id']);
        }
        return $products;
    }
}