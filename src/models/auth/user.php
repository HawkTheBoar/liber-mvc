<?php
require_once("models/pdoconnect.php");
require_once("utils/helpers.php");
require_once("utils/gen_hash.php");

class AuthenticatedUser extends User{
    function __construct($username, $email, $role){
        parent::__construct($email);
        $this->isAuthenticated = true;
        $this->username = $username;
        $this->role = $role;
        $this->email = $email;
    }
}
class User{
    private static $pdo;
    private static $config;
    protected string $username;
    protected string $email;
    protected string $role;
    protected bool $isAuthenticated;
    
    function __construct($email){
        if(self::$pdo === null){
            self::$pdo = pdoconnect::getInstance();
        }
        
        $this->isAuthenticated = false;
        $this->username = 'unknown';
        $this->email = $email;
        $this->role = 'unauthenticated';
    }
    static function GetUserFromSession(): AuthenticatedUser | User | null{

        if(isset($_SESSION['user'])){
            return $_SESSION['user'];
        }
        return null;
    }
    static function Logout(){
        unset($_SESSION['user']);
    }
    /**
     * Sets the user to the session
     */
    function Login(){
        $_SESSION['user'] = $this;
    }
    function getIsAuthenticated(): bool{
        return $this->isAuthenticated;
    }
    /**
     * Authenticates the user
     * @return bool TRUE if the user password is correct, FALSE otherwise
     */
    function Authenticate(string $password): bool{
        if(self::$pdo === null){
            self::$pdo = pdoconnect::getInstance();
        }

        preg_match('/[a-zA-Z0-9_]+@[a-zA-Z0-9_]+\.[a-zA-Z0-9_]+/', $this->email, $matches);
        if(count($matches) === 0){
            throw new InvalidArgumentException('Invalid email format');
        }

        $stmt = self::$pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        $res = $stmt->fetch();
        if($res === false){
            return false;
        }

        $result = password_verify($password, $res['password']);
        if($result){
            $this->isAuthenticated = true;
            $this->role = $res['role'];
            $this->username = $res['username'];
            return true;
        }
        return false;
    }
    public function getRole(): string{
        return $this->role;
    }
    public function getUsername(): string{
        return $this->username;
    }
    public function getEmail(): string{
        return $this->email;
    }
}
class UserFactory{
    private static $pdo;
    private static $config;
    
    public static function CreateUser(string $username, string $email, string $password, $role = 'user'): User | AuthenticatedUser{
        if(self::$pdo === null){
            self::$pdo = pdoconnect::getInstance();
        }
        $stmt = self::$pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $hash = gen_hash($password);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $hash);
        $stmt->bindParam(4, $role);
        $stmt->execute();
        $user = new AuthenticatedUser($username, $email, $role);
        return $user;
    }
    public static function CreateAdmin(string $username, string $email, string $password): User{
        return self::CreateUser($username, $email, $password, 'admin');
    }
}
