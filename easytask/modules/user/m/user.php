<?
namespace Beegee\Tasks;

class User{

    private static $_instance;
    protected $_login;
    protected $_password;

    public static function getInstance()
    {

        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }

    public function authorizeByLogin($login, $password)
    {
        $this->_login = htmlentities(stripslashes($login));
        $this->_password = htmlentities(stripslashes($password));

        return $this->checkAuthorize();
    }

    public function checkAuthorize(){

        if($this->_login === "admin" && $this->_password === "123"){
            return true;
        }
        else{
             return false;
        }

    }

    public function isAdmin(){

        if(defined("AUTHORIZED") && AUTHORIZED === "Y")
            return true;
        else
            return false;
    }

}