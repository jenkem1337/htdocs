<?php


require "./vendor/autoload.php";
class Product extends BaseEntity implements AggregateRoot{
    private $brand;
    private $model;
    private $header;
    private $description;
    private $price;
    private $stockQuantity;
    private $rate;
    private $comments;
    private $comment;
    private $categories;
    private $images;
    function __construct($uuid, $brand,$model,$header,$description,$price,$stockQuantity,$rate,$categories,$images,$createdAt, $updatedAt)
    {
        parent::__construct($uuid,$createdAt, $updatedAt);
        if(!$brand){
            throw new Exception("brand must be not null");
        }
        if(!$model){
            throw new Exception("model must be not null");
        }
        if(!$header){
            throw new Exception("header must be not null");
        }
        if(!$description){
            throw new Exception("description must be not null");
        }
        if(!$price){
            throw new Exception('price must be not null');
        }
        if($price <= 0){
            throw new Exception("price must be greater than zero");
        }
        if(!$stockQuantity){
            throw new Exception("stock quantity must be not null");
        }
        if(!$rate){
            throw new Exception("rate must be not null");
        }
        if(!$categories || count($categories)==0){
            throw new Exception("categories must be not null or length greater than zero");
        }
        $this->brand = $brand;
        $this->model = $model;
        $this->header = $header;
        $this->description = $description;
        $this->price = $price;
        $this->stockQuantity = $stockQuantity;
        $this->rate = $rate;
        $this->categories = $categories;
        $this->images = $images;
    }

    function incrementStockQuantity(int $quantity){
        $this->stockQuantity += $quantity;
    }
    function decrementStockQuantity(int $quantity){
        $this->stockQuantity -= $quantity;
        if($this->stockQuantity < 0){
            throw new Exception("stock quantity must not be less than zero");
        }
    }
    function setComment(Comment $comment){
        if(!$comment){
            throw new Exception("comment must be not null");
        }
        $this->comment = $comment;
    }
    function setComments(array $comments){
        $this->comments = $comments;
    }
    /**
     * Get the value of brand
     */ 
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Get the value of model
     */ 
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get the value of stockQuantity
     */ 
    public function getStockQuantity()
    {
        return $this->stockQuantity;
    }

    /**
     * Get the value of rate
     */ 
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Get the value of comments
     */ 
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get the value of categories
     */ 
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the value of header
     */ 
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Get the value of newComment
     */ 
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Get the value of images
     */ 
    public function getImages(array $images)
    {
        return $this->images;
    }
}