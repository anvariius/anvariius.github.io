<?php
$siteurl = 'http://aa.ru';
$siteurls = 'http://aa.ru';
$siteurl = 'http://audioalerts.ru';
$siteurls = 'http://audioalerts.ru';
// $siteurls = 'https://audioalerts.ru';

function authCheck($type = 0, $userid = 0) {
    if (isset($_COOKIE['userid']) && isset($_COOKIE['hash'])) {

        $hash = md5($_COOKIE['userid'].'audioalerts2017');
        if ($type) {
            if ($hash != $_COOKIE['hash']) {    
                return 0;
            } else {
                if ($userid) {
                    if ($_COOKIE['userid'] == $userid) {
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    return 1;
                }
            }
        } else {
            if ($hash != $_COOKIE['hash']) {    
                header("Location: /auth.php");
                die('auth error');
            }
        }
    } else {
        if ($type) {
            return 0;
        } else {
            header("Location: /auth.php");
            die('auth error');
        }
    }
}

function sequery($data,$var = 0) {
    if ($var == 0) {
       return DB::getInstance()->sequery($data);
    } else {
        return DB::getInstance()->sequery($data,$var);
    }
}

function pequery($data,$var = 0) {
    if ($var == 0) {
       return DB::getInstance()->pequery($data);
    } else {
        return DB::getInstance()->pequery($data,$var);
    }
}

function query($data,$var = 0) {
    if ($var == 0) {
       return DB::getInstance()->query($data);
    } else {
        return DB::getInstance()->query($data,$var);
    }
}

function fgc($fgc) {
    //return json_decode(file_get_contents($fgc), true);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fgc);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $data = curl_exec($ch);   
    curl_close($ch);
    return json_decode($data, true);
}


// $last_insert_id = DB::getInstance()->lastInsertId();
class DB {
    /**
     * @var null|PDO
     */
    private $engine = null;
    /**
     * @var bool
     */
    private static $instance = false;

    /**
     * Возвращает объект класса DB
     * @return DB
     */
    public static function getInstance () {
        if (self::$instance === false) {
            self::$instance = new DB;
        }
        return self::$instance;
    }

    /**
     * Инициализация PDO
     */
    
    private function __construct () {
        try {
            $this->engine = new PDO("mysql:host=localhost;dbname=audioalerts;charset=UTF8", 'elikeruser', 'elikeruser');
            $this->engine = new PDO("mysql:host=localhost;dbname=audioalerts;charset=UTF8", 'sul', 'qwe123');
            $this->engine->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->engine->exec('SET NAMES utf8');
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Отключить PDO
     */
    public function __destruct() {
        $this->engine = null;
        self::$instance = false;
    }

    /**
     * Выполнить запрос к базе данных
     * @param string $sQuery prepared строка с запросом
     * @param array $data ассоциативный массив с данными для запроса
     * @return PDOStatement
     */
    public function query ($sQuery, $data = array()) {
        try {
            $result = $this->engine->prepare ($sQuery);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute($data);
        }
        catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $result;

    }

    public function sequery ($sQuery, $data = array()) {
        try {
            $result = $this->engine->prepare ($sQuery);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute($data);
            $result2 = $result->fetchAll();
            if (!isset($result2[1]) && isset($result2[0])) {
                $result2 = $result2[0];
            }
        }
        catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $result2;

    }

    public function pequery ($sQuery, $data = array()) {
        try {
            $result = $this->engine->prepare ($sQuery);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute($data);
            $result2 = $result->fetchAll();
        }
        catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $result2;

    }

    public function preparing ($sQuery, $data = array()) {
        try {
            $result = $this->engine->prepare ($sQuery);/*
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute($data);
            $result2 = $result->fetchAll();
            if (!isset($result2[1]) && isset($result2[0])) {
                $result2 = $result2[0];
            }*/
        }
        catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $result;

    }

    /**
     * Получить id последнего вставленного элемента
     * @return string
     */
    public function lastInsertId(){
        return $this->engine->lastInsertId();
    }

    /**
     * Количество обновленных строк
     * @return integer
     */
    public function rowCount(){
        return (int) $this->engine->rowCount();
    }
} // DB

?>