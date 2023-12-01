<?php

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
    function findAllProducts(): IteratorAggregate
    {
        return $this->productRepository->findAllProducts();
    }
    function deleteProductByUuid(Product $p)
    {
        $this->productRepository->deleteProductByUuid($p);   
    }
    function updateProductStockQuantity(Product $p)
    {
        $this->productRepository->updateProductStockQuantity($p);
    }
    function findProductsByPriceRange($from, $to): IteratorAggregate
    {
        return $this->productRepository->findProductsByPriceRange($from, $to);
    }
    function findProductsBySearch($searchValue, $startingLimit, $perPageForProduct): IteratorAggregate
    {
        return $this->productRepository->findProductsBySearch($searchValue, $startingLimit, $perPageForProduct);
    }
    function deleteProductSubscriberByUserAndProductUuid($userUuid, $productUuid)
    {
        $this->productRepository->deleteProductSubscriberByUserAndProductUuid($userUuid, $productUuid);
    }
    function persistProductSubscriber(Product $p)
    {
        $this->productRepository->persistProductSubscriber($p);
    }
    
    function updateProductModelName(Product $p)
    {
        $this->productRepository->updateProductModelName($p);
    }
    function findAllWithPagination($startingLimit, $perPageForProduct): IteratorAggregate
    {
        return $this->productRepository->findAllWithPagination($startingLimit, $perPageForProduct);
    }
    function updateProductHeader(Product $p)
    {
        $this->productRepository->updateProductHeader($p);
    }
    function updateProductDescription(Product $p)
    {
        $this->productRepository->updateProductDescription($p);
    }
    function findOneProductByUuid($uuid):ProductInterface
    {
        return $this->productRepository->findOneProductByUuid($uuid);
    }
    function updateProductBrandName(Product $p)
    {
        $this->productRepository->updateProductBrandName($p);
    }
    function updateProductPrice(Product $p)
    {
        $this->productRepository->updateProductPrice($p);
    }
    function persistImage(Product $p)
    {
        $this->productRepository->persistImage($p);
    }
    function deleteImageByUuid($uuid)
    {
        $this->productRepository->deleteImageByUuid($uuid);
    }
    function createProductCategory(ProductForCreatingCategoryDecorator $c, $categoryUuidForFinding)
    {
        $this->productRepository->createProductCategory($c, $categoryUuidForFinding);
    }
    function findAllProductCategory(): ProductInterface
    {
        return $this->productRepository->findAllProductCategory();
    }
    function findOneProductCategoryByName($categoryName): ProductInterface
    {
        return $this->productRepository->findOneProductCategoryByName($categoryName);
    }
    function findOneProductCategoryByUuid($uuid): ProductInterface
    {
        return $this->productRepository->findOneProductCategoryByUuid($uuid);
    }
    function updateProductCategoryNameByUuid(Product $c, $categoryUuidForFinding)
    {
        $this->productRepository->updateProductCategoryNameByUuid($c, $categoryUuidForFinding);
    }
    function deleteProductCategoryByUuid($uuid)
    {
        $this->productRepository->deleteProductCategoryByUuid($uuid);
    }

    function findASetOfProductCategoryByUuids($uuids):ProductInterface {
        return $this->productRepository->findASetOfProductCategoryByUuids($uuids);
    }

    function findOneProductWithOnlySubscriberByUuid($uuid, $userUuid){
        return $this->productRepository->findOneProductWithOnlySubscriberByUuid($uuid, $userUuid);
    }
 
}