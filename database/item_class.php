<?php

class Item {
    private $id;
    private $sellerId;
    private $title;
    private $description;
    private $departmentId;
    private $categoryId;
    private $subcategoryId;
    private $brand;
    private $size;
    private $color;
    private $condition;
    private $price;
    private $imageUrl;

    // Constructor
    public function __construct($id, $sellerId, $title, $description, $departmentId, $categoryId, $subcategoryId, $brand, $size, $color, $condition, $price, $imageUrl) {
        $this->id = $id;
        $this->sellerId = $sellerId;
        $this->title = $title;
        $this->description = $description;
        $this->departmentId = $departmentId;
        $this->categoryId = $categoryId;
        $this->subcategoryId = $subcategoryId;
        $this->brand = $brand;
        $this->size = $size;
        $this->color = $color;
        $this->condition = $condition;
        $this->price = $price;
        $this->imageUrl = $imageUrl;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getSellerId() {
        return $this->sellerId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDepartmentId() {
        return $this->departmentId;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function getSubcategoryId() {
        return $this->subcategoryId;
    }

    public function getBrand() {
        return $this->brand;
    }

    public function getSize() {
        return $this->size;
    }

    public function getColor() {
        return $this->color;
    }

    public function getCondition() {
        return $this->condition;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }

    // Setters
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDepartmentId($departmentId) {
        $this->departmentId = $departmentId;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function setSubcategoryId($subcategoryId) {
        $this->subcategoryId = $subcategoryId;
    }

    public function setBrand($brand) {
        $this->brand = $brand;
    }

    public function setSize($size) {
        $this->size = $size;
    }

    public function setColor($color) {
        $this->color = $color;
    }

    public function setCondition($condition) {
        $this->condition = $condition;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
    }

    // Save method
    public function save($db) {
        if ($this->id) {
            $stmt = $db->prepare('
                UPDATE Item SET seller_id = ?, title = ?, item_description = ?, department_id = ?, category_id = ?, subcategory_id = ?, brand = ?, item_size = ?, color = ?, condition = ?, price = ?, image_url = ?
                WHERE id = ?
            ');
            $stmt->execute(array(
                $this->sellerId, 
                $this->title, 
                $this->description, 
                $this->departmentId, 
                $this->categoryId, 
                $this->subcategoryId, 
                $this->brand, 
                $this->size, 
                $this->color, 
                $this->condition, 
                $this->price, 
                $this->imageUrl, 
                $this->id
            ));
        } else {
            $stmt = $db->prepare('
                INSERT INTO Item (seller_id, title, item_description, department_id, category_id, subcategory_id, brand, item_size, color, condition, price, image_url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute(array(
                $this->sellerId, 
                $this->title, 
                $this->description, 
                $this->departmentId, 
                $this->categoryId, 
                $this->subcategoryId, 
                $this->brand, 
                $this->size, 
                $this->color, 
                $this->condition, 
                $this->price, 
                $this->imageUrl
            ));
            $this->id = $db->lastInsertId();
        }
    }
}

?>
