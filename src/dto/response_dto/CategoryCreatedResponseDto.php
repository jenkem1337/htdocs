<?php 

class CategoryCreatedResponseDto extends ResponseViewModel {
    protected $uuid;

    protected $categoryName;

    protected $createdAt;

    protected $updatedAt;

    public function __construct($uuid, $categoryName, $createdAt, $updatedAt)
    {
        $this->uuid = $uuid;
        $this->categoryName = $categoryName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        parent::__construct('success', $this);
    }
    
    

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get the value of categoryName
     */ 
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Get the value of uuid
     */ 
    public function getUuid()
    {
        return $this->uuid;
    }
}