<?php
    define("DEBUG_MODE",false);
    define("CONFIG_FILE","database/config.txt");
    //define("DEBUG_MODE",true);  
    println("Caricamento funzioni php completato<BR>");

    if (!file_exists(CONFIG_FILE)) {
        exit("Unable to find database configuration file");
    }
    $configData = explode("\n", file_get_contents("database/config.txt") );
        
    $username = decrypt($configData[0],"vallauri");
    $password = decrypt($configData[1],"vallauri");
    $connection = getConnection('docente','vallauri','meteovelletri');

    function getConnection($username,$password,$database) : PDO{
        $connection;
        try {
            $connection = new PDO("mysql:host=localhost;dbname=$database", $username, $password, array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ));  
            println("Connessione stabilita con il Database");        
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $connection;
    }


    function query($connection, $sql, $params=null) {
        $stmt = $connection->prepare($sql);
        if(isset($param)){
            $stmt->execute($params);
        }else{
            $stmt->execute();
        }
        $result = $stmt->fetchAll();
        return $result;
    }

    function printData($data){
        echo "<TABLE>";
        foreach($data as $row){
            echo "<TR>";
            foreach($row as $key=>$value){
                echo "<TD>$value</TD>"; 
            }
            echo "</TR>";
        }
        echo "</TABLE>";
    }

    function println($message){
        if(DEBUG_MODE)
            echo $message;
    }

    function decrypt($encryption,$key){
        $ciphering = "AES-128-CTR";
        $decryption_iv = '1234567891011121';
        $options = 0;
        $decryption=openssl_decrypt ($encryption, $ciphering, 
        $key, $options, $decryption_iv);
        return $decryption;
    }

    function encrypt($message,$key){
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption = openssl_encrypt($message, $ciphering,
            $key, $options, $encryption_iv);
        return $encryption;
    }

    function saveConfig($data,$filename,$append=false){
        if($append){
            file_put_contents($filename,$data,FILE_APPEND);
        }else{
            file_put_contents($filename,$data);
        }
        
    }
?>