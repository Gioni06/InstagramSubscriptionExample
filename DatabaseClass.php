<?php

/**
 * @author    Jonas Duri <jd@oygo.com>
 *
 * Class DatabaseClass
 */

error_reporting(E_ALL);
ini_set('display_errors', True);

class DatabaseClass {
    protected $localConfig;

    /**
     * @param $localConfigPath
     * load the local configuration file
     */
    public function __construct($localConfigPath){
        $this->localConfig = $localConfigPath;
    }

    /**
     * @throws Exception
     * returns a Database Connection Object
     */
    public function getDB()
    {
        if (is_file($this->localConfig)) {

            $config = include($this->localConfig);
            // configuration
            $dbhost 	= $config['DB']['host'];
            $dbname		= $config['DB']['database'];
            $dbuser		= $config['DB']['username'];
            $dbpass		= $config['DB']['password'];

            $dbcon = new PDO(
                "mysql:host=$dbhost;dbname=$dbname",
                $dbuser,
                $dbpass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            return $dbcon;
        } else {
            throw new Exception('DB Error');
        }
    }


    public function saveInstagramData($firstname,$lastname,$instagram,$src,$type,$thumb,$pic,$vid){


        $sql = "INSERT INTO ihrseidbebe (firstname,lastname,instagram,src,type,thumb,pic,vid,host,added) VALUES (:firstname,:lastname,:instagram,:src,:type,:thumb,:pic,:vid,:host,:added)";

        $conn = $this->getDB();

        $q = $conn->prepare($sql);
        $q->execute(array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'instagram' => $instagram,
            'src' => $src,
            'type' => $type,
            'thumb' => $thumb,
            'pic' => $pic,
            'vid' => $vid,
            'host' => 'instagram',
            'added' => date("Y-m-d H:i:s", time()),
        ));
    }
