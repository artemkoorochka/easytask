<?
namespace Beegee\Tasks;

use mysqli;

class Main{

    private static $_instance;
    private $_pathSystem;
    private $_dbConnect;
    private $_modules;

    public static function getInstance()
    {

        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }

    public function setPathSystem($pathSystem)
    {
        $this->_pathSystem = $pathSystem;
    }

    public function getPathSystem()
    {
        return $this->_pathSystem;
    }

    public function getDbConnect()
    {
        return $this->_dbConnect;
    }

    public function openDbConnect()
    {
        $this->_dbConnect = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);

        if ($this->_dbConnect->connect_error) {
            die("Connection failed: " . $this->_dbConnect->connect_error);
        }

    }

    public function closeDbConnect()
    {
        mysqli_close($this->_dbConnect);
    }

    public function getDBLink()
    {
        return $this->_dbConnect;
    }

    public function setModules($modules)
    {
        if(!empty($modules)){
            foreach ($modules as $module){
                $this->_modules[$module] = [
                    "name" => $module,
                    "path" => ""
                ];
            }

            $this->loadModules();
        }
    }

    public function getModules()
    {
        return $this->_modules;
    }

    public function getModulePath($name)
    {
        return $this->_modules[$name]["path"];
    }

    public function loadModules(){
        if(is_array($this->getModules())){
            foreach ($this->getModules() as $module){
                $this->requireModuleDir($module["name"], "m", true);
            }
            foreach ($this->getModules() as $module){
                $this->requireModuleDir($module["name"], "c", false);
            }
        }
    }

    public static function d($value){
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }

    public function LoadView($moduleName, $fileName){
        $path = [
            $this->getModulePath($moduleName),
            "v",
            $fileName
        ];
        $path = implode("/", $path);

        ob_start();
        require_once $path;
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    protected function requireModuleDir($module, $dir, $addPath=false){

        $moduleDir = [
            self::getPathSystem(),
            "modules",
            $module
        ];
        $moduleDir = implode("/", $moduleDir);

        if($addPath)
            $this->_modules[$module]["path"] = $moduleDir;

        $moduleDir .= "/" . $dir . "/";

        $module = scandir($moduleDir);
        $module = array_diff($module, array('.', '..'));
        if(!empty($module)){
            foreach ($module as $value){
                global $APP;
                require_once $moduleDir . $value;
            }
        }
    }

    public function check_email($email, $bStrict=false)
    {
        if(!$bStrict)
        {
            $email = trim($email);
            if(preg_match("#.*?[<\\[\\(](.*?)[>\\]\\)].*#i", $email, $arr) && strlen($arr[1])>0)
                $email = $arr[1];
        }

        //http://tools.ietf.org/html/rfc2821#section-4.5.3.1
        //4.5.3.1. Size limits and minimums
        if(strlen($email) > 320)
        {
            return false;
        }

        //http://tools.ietf.org/html/rfc2822#section-3.2.4
        //3.2.4. Atom
        static $atom = "=_0-9a-z+~'!\$&*^`|\\#%/?{}-";

        //"." can't be in the beginning or in the end of local-part
        //dot-atom-text = 1*atext *("." 1*atext)
        if(preg_match("#^[".$atom."]+(\\.[".$atom."]+)*@(([-0-9a-z_]+\\.)+)([a-z0-9-]{2,20})$#i", $email))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}