<?php
use Ramsey\Uuid\Uuid;

class ProductController {
    private ProductService $productService;
    function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    function createNewProduct(){
        $jsonBody = json_decode(file_get_contents('php://input'));

        $this->productService->craeteNewProduct(
            new ProductCreationalDto(
                Uuid::uuid4(),
                $jsonBody->brand,
                $jsonBody->model,
                $jsonBody->header,
                $jsonBody->description,
                $jsonBody->price,
                $jsonBody->stock_quantity,
                $jsonBody->categories,
                date ('Y-m-d H:i:s'),
                date ('Y-m-d H:i:s')    
            )
            )->onSucsess(function (ProductCreatedResponseDto $response){
            echo json_encode([
                'uuid'=>$response->getUuid(),
                'brand'=> $response->getBrand(),
                'model' => $response->getModel(),
                'header'=>$response->getHeader(),
                'description'=>$response->getDescription(),
                'price'=>$response->getPrice(),
                'stock_quantity'=>$response->getStockQuantity(),
                'categories'=>$response->getCategories(),
                'created_at' => $response->getCreatedAt(),
                'updated_at'=>$response->getUpdatedAt()
            ]);
        })->onError(function (ErrorResponseDto $err){
            echo json_encode([
                'error_message'=>$err->getErrorMessage(),
                'status_code'=> $err->getErrorCode()
            ]);
        });
    }
    function findAllProducts() {
        $this->productService->findAllProduct(new FindAllProductsDto)
                            ->onSucsess(function (AllProductResponseDto $products){

                                foreach($products->getProducts() as $product) {
                                    $categories = [];
                                    $comments = [];
                                    $rates = [];
                                    $images = [];
                                    $subscribers = [];
                                    foreach($product->getCategories() as $category){
                                        $categoryObj = new stdClass;
                                        $categoryObj->uuid = $category->getUuid();
                                        $categoryObj->category_name = $category->getCategoryName();
                                        $categoryObj->created_at = $category->getCreatedAt();
                                        $categoryObj->updated_at = $category->getUpdatedAt();
                                        $categories[] = $categoryObj;  
                                    }
                                    foreach($product->getComments() as $comment) {
                                        $commentObj = new stdClass;
                                        $commentObj->uuid = $comment->getUuid();
                                        $commentObj->comment_text = $comment->getComment();
                                        $commentObj->writer_name = $comment->getWriterName();
                                        $commentObj->created_at = $comment->getCreatedAt();
                                        $commentObj->updated_at = $comment->getUpdatedAt();
                                        $comments[] = $commentObj;
                                    }
                                    foreach($product->getRates() as $rate) {
                                        $rateObj = new stdClass;
                                        $rateObj->uuid = $rate->getUuid();
                                        $rateObj->user_uuid = $rate->getUserUuid();
                                        $rateObj->user_name = $rate->getRateNumber();
                                        $rateObj->created_at = $rate->getCreatedAt();
                                        $rateObj->updated_at = $rate->getUpdatedAt();
                                        $rates[] = $rateObj;
                                    }
                                    foreach($product->getImages() as $image) {
                                        $imageObj = new stdClass;
                                        $imageObj->uuid = $image->getUuid();
                                        $imageObj->image_name = $image->getImageName();
                                        $imageObj->product_uuid = $image->getProductUuid();
                                        $imageObj->created_at = $image->getCreatedAt();
                                        $imageObj->updated_at = $image->getUpdatedAt();
                                        $images[] = $imageObj;
                                    }
                                    foreach($product->getSubscribers() as $subscriber) {
                                        $subObj = new stdClass;
                                        $subObj->uuid = $subscriber->getUuid();
                                        $subObj->subscriber_uuid = $subscriber->getUserUuid();
                                        $subObj->subscriber_name = $subscriber->getUserFullName();
                                        $subObj->subscriber_email = $subscriber->getUserEmail();
                                        $subObj->created_at= $subscriber->getCreatedAt();
                                        $subObj->updated_at = $subscriber->getUpdatedAt();
                                        $subscribers[] = $subObj;
                            
                                    }
                                    echo json_encode([
                                        'uuid'=>$product->getUuid(),
                                        'brand'=> $product->getBrand(),
                                        'model' => $product->getModel(),
                                        'header'=>$product->getHeader(),
                                        'description'=>$product->getDescription(),
                                        'price'=>$product->getPrice(),
                                        'stock_quantity'=>$product->getStockQuantity(),
                                        'avarage_rate'=>$product->getAvarageRate(),
                                        'comments'=>$comments,
                                        'rates'=>$rates,
                                        'subscribers'=>$subscribers,
                                        'categories'=>$categories,
                                        'images'=>$images,
                                        'created_at' => $product->getCreatedAt(),
                                        'updated_at'=>$product->getUpdatedAt()
                                    ]);
    
                                }
                            })->onError(function (ErrorResponseDto $err){
                                echo json_encode([
                                    'error_message'=>$err->getErrorMessage(),
                                    'status_code'=> $err->getErrorCode()
                                ]);
                    
                            });
    }
    function findOneProductByUuid($uuid){
        
       $this->productService->findOneProductByUuid(new FindOneProductByUuidDto($uuid))
                            ->onSucsess(function(OneProductFoundedResponseDto $response){
                                echo json_encode([
                                    'uuid'=>$response->getUuid(),
                                    'brand'=> $response->getBrand(),
                                    'model' => $response->getModel(),
                                    'header'=>$response->getHeader(),
                                    'description'=>$response->getDescription(),
                                    'price'=>$response->getPrice(),
                                    'stock_quantity'=>$response->getStockQuantity(),
                                    'avarage_rate'=>$response->getAvarageRate(),
                                    'comments'=> $response->getComments(),
                                    'rates'=>$response->getRates(),
                                    'subscribers'=>$response->getSubscribers(),
                                    'categories'=> $response->getCategories(),
                                    'images'=>$response->getImages(),
                                    'created_at' => $response->getCreatedAt(),
                                    'updated_at'=>$response->getUpdatedAt()
                                ]);
                            })->onError(function (ErrorResponseDto $err){
                                echo json_encode([
                                    'error_message'=>$err->getErrorMessage(),
                                    'status_code'=> $err->getErrorCode()
                                ]);
        
                            });
        
    }

    function updateProductBrandName($uuid){
        $jsonBody = json_decode(file_get_contents('php://input'));
        
        $this->productService->updateProductBrandName(
            new ChangeProductBrandNameDto($uuid, $jsonBody->new_brand_name)
        
        )->onSucsess(function (ProductBrandNameChangedSuccessfullyResponseDto $response){
            echo json_encode(['success_message' => $response->getSuccessMessage()]);
        
        })->onError(function (ErrorResponseDto $err){
            echo json_encode([
                'error_message'=>$err->getErrorMessage(),
                'status_code'=> $err->getErrorCode()
            ]);
        });
    }

    function updateProductModelName($uuid){
        $jsonBody = json_decode(file_get_contents('php://input'));
        
        $this->productService->updateProductModelName(
            new ChangeProductModelNameDto($uuid, $jsonBody->new_model_name)
        
        )->onSucsess(function (ProductModelNameChangedResponseDto $response){
            echo json_encode(['success_message' => $response->getSuccessMessage()]);
        
        })->onError(function (ErrorResponseDto $err){
            echo json_encode([
                'error_message'=>$err->getErrorMessage(),
                'status_code'=> $err->getErrorCode()
            ]);
        });
    }
    function updateProductHeader($uuid){
        $jsonBody = json_decode(file_get_contents('php://input'));
        
        $this->productService->updateProductHeader(
            new ChangeProductHeaderDto($uuid, $jsonBody->new_header)
        
        )->onSucsess(function (ProductHeaderChangedResponseDto $response){
            echo json_encode(['success_message' => $response->getSuccessMessage()]);
        
        })->onError(function (ErrorResponseDto $err){
            echo json_encode([
                'error_message'=>$err->getErrorMessage(),
                'status_code'=> $err->getErrorCode()
            ]);
        });

    }
    function updateProductDescription($uuid){
        $jsonBody = json_decode(file_get_contents('php://input'));
        
        $this->productService->updateProductDescription(
            new ChangeProductDescriptionDto($uuid, $jsonBody->new_description)
        
        )->onSucsess(function (ProductDescriptionChangedResponseDto $response){
            echo json_encode(['success_message' => $response->getSuccessMessage()]);
        
        })->onError(function (ErrorResponseDto $err){
            echo json_encode([
                'error_message'=>$err->getErrorMessage(),
                'status_code'=> $err->getErrorCode()
            ]);
        });

    }

    function updateProductPrice($uuid) {
        $jsonBody = json_decode(file_get_contents('php://input'));
        
        $this->productService->updateProductPrice(
            new ChangeProductPriceDto($uuid, $jsonBody->new_price)
        
        )->onSucsess(function (ProductPriceChangedResponseDto $response){
            echo json_encode(['success_message' => $response->getSuccessMessage()]);
        
        })->onError(function (ErrorResponseDto $err){
            echo json_encode([
                'error_message'=>$err->getErrorMessage(),
                'status_code'=> $err->getErrorCode()
            ]);
        });

    }

}
