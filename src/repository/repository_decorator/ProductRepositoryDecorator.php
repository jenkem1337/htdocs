<?php
require './vendor/autoload.php';

abstract class ProductRepositoryDecorator implements ProductRepository {
    private ProductRepository $productRepository;
    function __construct(ProductRepository $r)
    {
        $this->productRepository = $r;
    }
    function createProduct(Product $p)
    {
        $this->productRepository->createProduct($p);
    }
    function updateProductModelName(Product $p)
    {
        $this->productRepository->updateProductModelName($p);
    }
    function findOneProductByUuid($uuid):ProductInterface
    {
        return $this->productRepository->findOneProductByUuid($uuid);
    }
    function updateProductBrandName(Product $p)
    {
        $this->productRepository->updateProductBrandName($p);
    }
    function persistImage(Product $p)
    {
        $this->productRepository->persistImage($p);
    }
    function deleteImageByUuid($uuid)
    {
        $this->productRepository->deleteImageByUuid($uuid);
    }
    function createCategory(ProductForCreatingCategoryDecorator $c, $categoryUuidForFinding)
    {
        $this->productRepository->createCategory($c, $categoryUuidForFinding);
    }
    function findAllCategory(): ProductInterface
    {
        return $this->productRepository->findAllCategory();
    }
    function findOneCategoryByName($categoryName): ProductInterface
    {
        return $this->productRepository->findOneCategoryByName($categoryName);
    }
    function findOneCategoryByUuid($uuid): ProductInterface
    {
        return $this->productRepository->findOneCategoryByUuid($uuid);
    }
    function updateCategoryNameByUuid(Product $c, $categoryUuidForFinding)
    {
        $this->productRepository->updateCategoryNameByUuid($c, $categoryUuidForFinding);
    }
    function deleteCategoryByUuid($uuid)
    {
        $this->productRepository->deleteCategoryByUuid($uuid);
    }
}