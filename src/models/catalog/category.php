<?php
class Category{
    public string $name;
    public string $description;
    public int $id;
    public Category | null $child;

    private function __construct($name, $description, $id, $child){
        $this->name = $name;
        $this->description = $description;
        $this->id = $id;
        $this->child = $child;
    }
    public static function findCategoryByID($id): Category | null{
        $pdo = pdoconnect::getInstance();
        $sql = "WITH RECURSIVE ParentCategories (id, name, parent_id, description) AS (
                SELECT id, name, parent_id, description
                FROM categories
                WHERE id = :id AND is_deleted = FALSE
                UNION ALL
                SELECT c.id, c.name, c.parent_id, c.description
                FROM categories c
                INNER JOIN ParentCategories pc ON c.id = pc.parent_id
                WHERE c.is_deleted = FALSE
                )
                SELECT * FROM ParentCategories;
        ";  
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetchAll();
        if(count($res) <= 0){
            return null;
        }
        $category = new Category($res[0]['name'], $res[0]['description'], $res[0]['id'], null);
        $first = $category;
        array_shift($res);
        foreach($res as $row){
            $category->child = new Category($row['name'], $row['description'], $row['id'], null);
            $category = $category->child;
        }
        return $first;
    }

}