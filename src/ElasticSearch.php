<?php 

namespace Kaleyra\ElasticBuilder;

// use Kaleyra\ElasticBuilder\Helper\CurlHelper;
require_once("helper/CurlHelper.php");

class ElasticSearch {


    /**
     * Elastic Connection instance
     * @var array
     */
    protected $connection;

    /**
     * Elastic host address
     * @var array
     */
    protected $hostAddress;

    /**
     * Elastic Search Version
     * @var array
     */
    protected $version;

    /**
     * Elastic Cluster Health
     * @var
     */
    protected $health;

    /**
     *  Constructor.
     * @param $connection
     */
    public function __construct($connection = NULL) {
        $this->connection = $connection;
        self::setHostAddress();
        Self::version();
        Self::ping();
    }


    public function setHostAddress(){
        $addr = $this->connection['server'][0]['host'] . ':' . $this->connection['server'][0]['port'] . '/';
        $this->hostAddress = $addr;
    }

    public function getHostAddress(){
        return $this->hostAddress;
    }

    /**
     * Connection Instance
     * @return array
     */
    public function getConnection(){
        return $this;
    }

    /**
     * Fucntion to get Elastic Search Version
     * @param
     */
    public function version(){                
        $url = $this->getHostAddress();

        $response = CurlHelper::httpGetRequest($url);
        
        if( is_array($response['version']) ){
            $this->version = $response['version']['number'];
        }        
    }

    /**
     * Getter Elastic version
     * @param
     */
    public function getVersion(){
        return $this->version;
    }

    /**
     * 
     * @param
     * @return
     */
    public function ping(){

        $query = '_cluster/health';
        // $url =  $this->connection['server']['host'] . "/" . $query;
        $host = $this->getHostAddress();
        $url  = $host.$query;

        $healthResponse = CurlHelper::httpGetRequest($url);

        if( is_array($healthResponse) ){
            $this->health = $healthResponse['status'];
        }

    }

    /**      
     * @param
     * @return
     */
    public function getHealth(){

        return $this->health;
    }

}