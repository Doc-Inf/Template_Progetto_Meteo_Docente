<?php
    define("DEBUG_MODE",false);
    define("CONFIG_FILE","database/config.txt");
    define("KEY","vallauri");
   
    println("Caricamento funzioni php completato<BR>");

    if (!file_exists(CONFIG_FILE)) {
        exit("Unable to find database configuration file");
    }
    $configData = explode("\n", file_get_contents("database/config.txt") );
        
    $username = decrypt($configData[0],KEY);
    $password = decrypt($configData[1],KEY);
    $database = decrypt($configData[2],KEY);
    $connection = getConnection($username,$password,$database);

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

    function getLast($connection){
        $stmt = $connection->prepare("SELECT * FROM rilevazioni2023 r WHERE date(r.data)=(SELECT max(date(r2.data)) FROM rilevazioni2023 r2)");
        $stmt->execute();
        return $stmt->fetchAll();
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

    function printRow($row){
        $infoData = explode(" ", $row["data"]); 
        $dataRilevazione = $infoData[0];
        $oraRilevazione = $infoData[1];
        $pressione = $row["pressione"];
        $umidita = $row["umidita"];
        $direzioneVento = $row["direzioneVento"];
        $intensitaVento = $row["ventoKm_h"];
        $imgTemperatura;

        $output = "";
        $temp = $row["temperatura"];
       
        if( $temp < 10){
            $imgTemperatura = "images/temperatura-freddo.png";
        }else{
            if($temp >= 10 && $temp <20){
                $imgTemperatura = "images/temperaturaAutunno.jpg";
            }else{
                if($temp >= 20 && $temp <35){
                    $imgTemperatura = "images/caldo-estivo.jpg"; 
                }else{
                    $imgTemperatura = "images/caldissimo.webp";     
                }                
            }
        }
        $output = <<<END
                        <div class="container">
                            <div class="row mt-5 mb-5 justify-content-between">
                                <div class="col-sm-12 col-md-5 col-lg-3  text-center shadow-lg rounded mb-5 me-1 pt-3 pd-3 pe-3">
                                    <img class="img-fluid mb-3 rounded shadow-lg" src="images/calendario2.webp" alt="Calendario">
                                    <p><strong>Data: </strong> $dataRilevazione <strong class="ms-4">Ora: </strong> $oraRilevazione</p>                                    
                                </div>
                                <div class="col-sm-12 col-md-5 col-lg-3 text-center shadow-lg rounded mb-5 me-1 pt-3 pd-3 pe-3">
                                    <img class="img-fluid mb-3 rounded shadow-lg" src="$imgTemperatura" alt="Temperatura">
                                    <p><strong>Temperatura:</strong> $temp °C</p>
                                </div>
                                <div class="col-sm-12 col-md-5 col-lg-3  text-center shadow-lg rounded mb-5 me-1 pt-3 pd-3 pe-3">
                                    <img class="img-fluid mb-3 rounded shadow-lg" src="images/barometro2.webp" alt="Barometro">
                                    <p><strong>Pressione: </strong> $pressione </p>
                                </div>
                                <div class="col-sm-12 col-md-5 col-lg-3  text-center shadow-lg rounded mb-5 me-1 pt-3 pd-3 pe-3">
                                    <img class="img-fluid mb-3 rounded shadow-lg" src="images/umidita2.webp" alt="Umidità">
                                    <p><strong>Umidità: </strong> $umidita </p>                                
                                </div>
                                <div class="col-sm-12 col-md-5 col-lg-3  text-center shadow-lg rounded mb-5 me-1 pt-3 pd-3 pe-3">
                                    <img class="img-fluid mb-3 rounded shadow-lg" src="images/paleVento.jpg" alt="Direzione vento">
                                    <p><strong>Direzione Vento: </strong> $direzioneVento </p>
                                </div>
                                <div class="col-sm-12 col-md-5 col-lg-3  text-center shadow-lg rounded mb-5 me-1 pt-3 pd-3 pe-3">
                                    <img class="img-fluid mb-3 rounded shadow-lg" src="images/forzaVento5.jpg" alt="Forza Vento">
                                    <p><strong>Intensità vento: </strong> $intensitaVento </p>
                                </div>
                            </div>
                        </div>
                        END;
        echo $output;
        
    }

    function println($message){
        if(DEBUG_MODE)
            echo $message;
    }

    function decrypt($encryption,$key){
        $ciphering = "AES-128-CTR";
        $decryption_iv = '1234567891011121';
        $options = 0;
        $decryption=openssl_decrypt($encryption, $ciphering, 
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