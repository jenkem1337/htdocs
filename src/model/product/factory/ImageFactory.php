<?php
require "./vendor/autoload.php";

abstract class ImageFactory implements Factory {
    function createInstance($isMustBeConcreteObject =false,...$params):Image
    {
        return new Image(...$params);
    }
}