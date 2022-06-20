<?php

use Ramsey\Uuid\Nonstandard\Uuid;

require './vendor/autoload.php';
class UserDaoImpl implements UserDao{
    private DatabaseConnection $dbConnection;

    public function __construct(DatabaseConnection $dbConnection)
    {
        
        $this->dbConnection = $dbConnection;
    }
    function findAllWithPagination($startingLimit, $perPageForUsers)
    {
        $conn =  $this->dbConnection->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT $startingLimit, $perPageForUsers");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $conn = null;
        return $users; 
    }

    function findUserByEmail($email){
        $conn =  $this->dbConnection->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email LIMIT 1");
        $stmt->execute(['email'=>$email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        $conn = null;
        if(!$user) {
            $arr = [
                'uuid'=>null,
                'full_name'=>null,
                'email'=>null,
                'user_password'=>null,
                'is_user_activated'=>null,
                'created_at'=>null,
                'updated_at'=>null,
                'user_role'=>null
            ];
            return json_decode(json_encode($arr),false);
        }

        return $user;
        
    }
    function persist(User $user){
        $conn =  $this->dbConnection->getConnection();
        
        $stmt = $conn->prepare("INSERT INTO users (uuid, full_name, email, user_password, email_activation_code, is_user_activated, user_role, created_at, updated_at) VALUES (:uuid, :full_name, :email, :user_password, :email_activation_code, :is_user_activated, :user_role, :created_at, :updated_at)");
        $stmt->execute([
            'uuid'=>$user->getUuid(),
            'full_name'=>$user->getFullname(),
            'email'=>$user->getEmail(),
            'user_password'=>$user->getPassword(),
            'email_activation_code'=>$user->getActivationCode(),
            'is_user_activated'=> $user->getIsUserActiveted(),
            'user_role'=>$user->getUserRole(),
            'created_at'=>$user->getCreatedAt(),
            'updated_at'=>$user->getCreatedAt()
        ]);
        $conn = null;
    }
    function findUserByActivationCode($code){
        $conn =  $this->dbConnection->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email_activation_code=:email_activation_code LIMIT 1");
        $stmt->execute(['email_activation_code'=>$code]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        $conn = null;
        if(!$user) {
            $arr = [
                'uuid'=>null,
                'full_name'=>null,
                'email'=>null,
                'user_password'=>null,
                'is_user_activated'=>null,
                'created_at'=>null,
                'updated_at'=>null,
                'user_role'=>null,
                'email_activation_code'=>null
            ];
            return json_decode(json_encode($arr),false);
        }
        return $user;

    }
    function updateUserActivatedState(User $user){
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare("UPDATE users SET is_user_activated=1, updated_at=NOW() WHERE email_activation_code=:code");
        $stmt->execute([
            'code'=>$user->getActivationCode()
        ]);
        $conn = null;
    }

    function findUserByUuid($userUuid){

        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE uuid=:uuid LIMIT 1");
        $stmt->execute(['uuid'=>$userUuid]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if(!$user) {
            $arr = [
                'uuid'=>null,
                'full_name'=>null,
                'email'=>null,
                'user_password'=>null,
                'is_user_activated'=>null,
                'created_at'=>null,
                'updated_at'=>null,
                'user_role'=>null
            ];
            return json_decode(json_encode($arr),false);
        }
        return $user;
    }

    function updatePassword(User $user)
    {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare("UPDATE users SET user_password=:new_password, updated_at=NOW() WHERE uuid=:uuid");
        $stmt->execute([
            'new_password'=>$user->getPassword(),
            'uuid'=>$user->getUuid()
        ]);
        $conn = null;

    }
    function updateForgettenPasswordCode(User $user)
    {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare("UPDATE users SET forgetten_password_activation_code=:forgetten_password_activation_code, updated_at=NOW() WHERE uuid=:uuid");
        $stmt->execute([
            'forgetten_password_activation_code'=>$user->getForegettenPasswordCode(),
            'uuid'=>$user->getUuid()
        ]);
        $conn = null;

    }

    function findUserByForgettenPasswordCode($passwordCode){
        $conn =  $this->dbConnection->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE forgetten_password_activation_code=:forgetten_password_activation_code LIMIT 1");
        $stmt->execute(['forgetten_password_activation_code'=>$passwordCode]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        $conn = null;
        if(!$user) {
            $arr = [
                'uuid'=>null,
                'full_name'=>null,
                'email'=>null,
                'user_password'=>null,
                'is_user_activated'=>null,
                'created_at'=>null,
                'updated_at'=>null,
                'user_role'=>null
            ];
            return json_decode(json_encode($arr),false);
        }
        return $user;

    }
    function updateFullName(User $user)
    {
        $conn = $this->dbConnection->getConnection();
        $stmt = $conn->prepare("UPDATE users SET full_name=:full_name, updated_at=NOW() WHERE uuid=:uuid");
        $stmt->execute([
            'full_name'=>$user->getFullname(),
            'uuid'=>$user->getUuid()
        ]);
        $conn = null;

    }
}