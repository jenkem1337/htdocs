<?php
interface ProductRepository {
    //product
    function createProduct(Product $p);
    function findOneProductByUuid($uuid):ProductInterface;
    function updateProductBrandName(Product $p);
    function updateProductModelName(Product $p);
    function updateProductHeader(Product $p);
    function updateProductDescription(Product $p);
    function updateProductPrice(Product $p);
    function findAllProducts():IteratorAggregate;
    function findAllWithPagination($startingLimit, $perPageForProduct):IteratorAggregate;

    //image
    function persistImage(Product $p);
    function deleteImageByUuid($uuid);

    //category
    function createCategory(ProductForCreatingCategoryDecorator $c, $categoryUuidForFinding);
    function findAllCategory():ProductInterface;
    function findOneCategoryByUuid($uuid):ProductInterface;
    function updateCategoryNameByUuid(Product $c, $categoryUuidForFinding);
    function findOneCategoryByName($categoryName):ProductInterface;
    function deleteCategoryByUuid($uuid);
}