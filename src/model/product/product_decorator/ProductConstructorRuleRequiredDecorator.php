<?php
require './vendor/autoload.php';
class ProductConstructorRuleRequiredDecorator extends ProductDecorator {
    function __construct($uuid, string $brand,string $model, string $header, string $description, float $price, int $stockQuantity, RateInterface $rate, $createdAt, $updatedAt)
    {
        if(!$brand){
            throw new NullException("brand");
        }
        if(!$model){
            throw new NullException("model");
        }
        if(!$header){
            throw new NullException("header");
        }
        if(!$description){
            throw new NullException("description");
        }
        if(!$price){
            throw new NullException('price');
        }
        if($price < 0){
            throw new NegativeValueException();
        }
        if($stockQuantity<0){
            throw new NegativeValueException();
        }
        if(!$rate){
            throw new NullException('rate');
        }
        parent::__construct($uuid, $brand,$model, $header,  $description, $price, $stockQuantity, $rate, $createdAt, $updatedAt);
    }
}