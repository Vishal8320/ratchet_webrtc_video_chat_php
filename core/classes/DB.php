<?php
namespace MyApp;
use PDO;
class DB {
    function connect() {
        
            $db = new PDO("mysql:host=127.0.0.1;dbname=webtest", "root", "");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
    }
}

?>
