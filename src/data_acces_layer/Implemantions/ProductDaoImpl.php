<?php
require './vendor/autoload.php';

class ProductDaoImpl implements ProductDao {
    protected SingletonConnection $dbConnection;
	function __construct(SingletonConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
	}
	function persist(Product $p) {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "INSERT INTO products (uuid, brand, model, header, _description, price, prev_price, rate, stockquantity, created_at, updated_at)
            VALUES (:uuid, :brand, :model, :header, :_description, :price, :prev_price, :rate, :stockquantity, :created_at, :updated_at)"
        );
        $stmt->execute([
            'uuid'=>$p->getUuid(),
            'brand'=>$p->getBrand(),
            'model'=>$p->getModel(),
            'header'=>$p->getHeader(),
            '_description'=>$p->getDescription(),
            'price'=>$p->getPrice(),
            'prev_price'=>NULL,
            'rate'=>$p->getAvarageRate(),
            'stockquantity'=>$p->getStockQuantity(),
            'created_at'=>$p->getCreatedAt(),
            'updated_at'=>$p->getUpdatedAt()
        ]);
        $conn = null;
	}
    function persistSubscriber(ProductSubscriber $ps)
    {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "INSERT INTO product_subscriber (uuid, user_uuid, product_uuid, created_at, updated_at)
            VALUES (:uuid, :user_uuid, :product_uuid, :created_at, :updated_at)"
        );
        $stmt->execute([
            'uuid'=>$ps->getUuid(),
            'user_uuid'=>$ps->getUserUuid(),
            'product_uuid'=>$ps->getProductUuid(),
            'created_at'=>$ps->getCreatedAt(),
            'updated_at'=>$ps->getUpdatedAt()
        ]);
        $conn = null;

    }
	
	function deleteSubscriberByUserAndProductUuid($userUuid, $productUuid)
    {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "DELETE FROM product_subscriber 
            WHERE (user_uuid = :user_uuid AND product_uuid = :product_uuid)"
        );
        $stmt->execute([
            'user_uuid'=>$userUuid,
            'product_uuid' => $productUuid
        ]);
        $conn = null;

    }
    function deleteSubscriberByProductUuid($pUuid)
    {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "DELETE FROM product_subscriber WHERE product_uuid = :uuid"
        );
        $stmt->execute([
            'uuid'=>$pUuid
        ]);
        $conn = null;

    }
	function deleteByUuid($uuid) {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "DELETE FROM products WHERE uuid = :uuid"
        );
        $stmt->execute([
            'uuid'=>$uuid
        ]);
        $conn = null;
	}
	function findAll(){
        $conn =  $this->dbConnection->getConnection();
        $stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);    
        $conn = null;
        if($products == null) return $this->returnManyNullStatement();

        return $products; 

    }
    function findAllProductSubscriberByProductUuid($uuid)
    {
        $conn= $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "SELECT ps.uuid, ps.user_uuid, ps.product_uuid ,ps.created_at, ps.updated_at, u.full_name as user_full_name, u.email as user_email
            FROM product_subscriber as ps, users as u
            WHERE ps.product_uuid = :uuid AND ps.user_uuid = u.uuid"
        );
        $stmt->execute([
            "uuid"=>$uuid
        ]);
        $productSubscriber = $stmt->fetchAll(PDO::FETCH_OBJ);
        $conn = null;
        if($productSubscriber == null) return $this->returnManyNullForSubscriberStatement();
        return $productSubscriber;

    }
    function findSubscriberByUserUuid($uuid)
    {
        $conn =  $this->dbConnection->getConnection();
        $stmt = $conn->prepare("SELECT * FROM product_subscriber WHERE user_uuid = :uuid LIMIT 1");
        $stmt->execute([
            'uuid'=>$uuid
        ]);
        $product = $stmt->fetch(PDO::FETCH_OBJ);
        $conn = null;
        if($product == null) return $this->returnNullStatment();
        return $product;

    }
	function findAllWithPagination($startingLimit, $perPageForUsers) {
        $conn =  $this->dbConnection->getConnection();
        $stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT $startingLimit, $perPageForUsers");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $conn = null;
        if($products == null) return $this->returnManyNullStatement();

        return $products; 

	}
	
	
	function findOneByUuid($uuid) {
        $conn =  $this->dbConnection->getConnection();
        $stmt = $conn->prepare("SELECT * FROM products WHERE uuid = :uuid LIMIT 1");
        $stmt->execute([
            'uuid'=>$uuid
        ]);
        $product = $stmt->fetch(PDO::FETCH_OBJ);
        $conn = null;
        if($product == null) return $this->returnNullStatment();
        return $product;
	}
	
	function findBySearching($value, $startingLimit, $perPageForUsers) {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "SELECT * FROM products 
            WHERE (
                model LIKE :_value OR
                brand LIKE :_value OR
                _description LIKE :_value OR
                header LIKE :_value 
            )
            ORDER BY created_at DESC 
            LIMIT $startingLimit, $perPageForUsers" 
            
        );
        $stmt->execute([
            '_value'=>"%".$value."%"
        ]);
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $conn = null;
        if($products == null) return $this->returnManyNullStatement();
        return $products;
	}
	function findByPriceRange($from, $to)
    {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "SELECT * FROM products 
            WHERE price BETWEEN :_from AND :_to" 
            
        );
        $stmt->execute([
            '_from' => (int)$from,
            '_to' => (int)$to
        ]);
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $conn = null;
        if($products == null) return $this->returnManyNullStatement();
        return $products;

    }
	function updateStockQuantityByUuid(Product $p) {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "UPDATE products SET stockquantity = :stockquantity, updated_at = NOW()
            WHERE uuid = :uuid"
        );
        $stmt->execute([
            'uuid'=>$p->getUuid(),
            'stockquantity'=>$p->getStockQuantity()
        ]);
        $conn = null;

	}
	
	function updateModelNameByUuid(Product $p) {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "UPDATE products SET model = :model, updated_at = NOW()
            WHERE uuid = :uuid"
        );
        $stmt->execute([
            'uuid'=>$p->getUuid(),
            'model'=>$p->getModel()
        ]);
        $conn = null;

	}
	
	function updateBrandNameByUuid(Product $p) {        
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "UPDATE products SET brand = :brand, updated_at = NOW()
            WHERE uuid = :uuid"
        );
        $stmt->execute([
            'uuid'=>$p->getUuid(),
            'brand'=>$p->getBrand()
        ]);
        $conn = null;

	}
	
	function updatePriceByUuid(Product $p) {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "UPDATE products SET price = :price, prev_price = :prev_price, updated_at = NOW()
            WHERE uuid = :uuid"
        );
        $stmt->execute([
            'uuid'=>$p->getUuid(),
            'price'=>$p->getPrice(),
            'prev_price'=>$p->getPreviousPrice()
        ]);
        $conn = null;

	}
	function updateDescriptionByUuid(Product $p) {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "UPDATE products SET _description = :_description, updated_at = NOW()
            WHERE uuid = :uuid"
        );
        $stmt->execute([
            'uuid'=>$p->getUuid(),
            '_description'=>$p->getDescription()
        ]);
        $conn = null;

	}
	
	function updateHeaderByUuid(Product $p) {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "UPDATE products SET header = :header, updated_at = NOW()
            WHERE uuid = :uuid"
        );
        $stmt->execute([
            'uuid'=>$p->getUuid(),
            'header'=>$p->getHeader()
        ]);
        $conn = null;


	}
	
	function updateAvarageRateByUuid(Product $p) {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "UPDATE products SET rate = :rate, updated_at = NOW()
            WHERE uuid = :uuid"
        );
        $stmt->execute([
            'uuid'=>$p->getUuid(),
            'rate'=>$p->getAvarageRate()
        ]);
        $conn = null;
	}
    private function returnNullStatment() {
        $arr = [
            'uuid'=>null,
            'brand' => null,
            'model'=>null, 
            'header'=>null,
            '_description'=>null,
            'price'=>null,
            'prev_price'=>null,
            'rate'=>null,
            'stockquantity'=>null,
            'created_at'=>null,
            'updated_at'=>null,
        ];
        return json_decode(json_encode($arr),false);
    } 

    function findOneOrEmptySubscriberByUuid($uuid, $userUuid){
        $conn= $this->dbConnection->getConnection();
        $stmt = $conn->prepare(
            "SELECT ps.uuid, ps.user_uuid, ps.product_uuid ,ps.created_at, ps.updated_at, u.full_name , u.email
            FROM product_subscriber as ps
            JOIN users as u ON ps.user_uuid = u.uuid
            WHERE ps.product_uuid = :uuid and ps.user_uuid = :user_uuid LIMIT 1");
        $stmt->execute([
            "uuid"=>$uuid,
            "user_uuid"=> $userUuid
        ]);
        $productSubscriber = $stmt->fetch(PDO::FETCH_OBJ);
        $conn = null;
        if($productSubscriber == null) return $this->returnNullForSubscriberStatement();
        return $productSubscriber;

    }
    private function returnManyNullStatement(){
        $productArr = array();
        $product = new stdClass();
        $product->uuid = null;
        $product->brand = null;
        $product->model = null;
        $product->header = null;
        $product->_description =null;
        $product->price = null;
        $product->prev_price = null;
        $product->rate = null;
        $product->stockquantity = null;
        $product->created_at= null;
        $product->updated_at = null;
        $productArr[] = $product;
        return $productArr;
    }
    private function returnManyNullForSubscriberStatement(){
        $productArr = array();
        $product_subscriber = new stdClass();
        $product_subscriber->uuid = null;
        $product_subscriber->product_uuid = null;
        $product_subscriber->user_uuid = null;
        $product_subscriber->created_at= null;
        $product_subscriber->updated_at = null;
        $product_subscriber->user_full_name = null;
        $product_subscriber->user_email = null;
        $productArr[] = $product_subscriber;
        return $productArr;
    }
    private function returnNullForSubscriberStatement(){
        $product_subscriber = new stdClass();
        $product_subscriber->uuid = null;
        $product_subscriber->product_uuid = null;
        $product_subscriber->user_uuid = null;
        $product_subscriber->created_at= null;
        $product_subscriber->updated_at = null;
        $product_subscriber->user_full_name = null;
        $product_subscriber->user_email = null;
        return $product_subscriber;
    }


}