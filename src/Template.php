<?php 

namespace Kaleyra\ElasticBuilder;

// use Kaleyra\ElasticBuilder\ElasticSearch;
// use Kaleyra\ElasticBuilder\Helper\CurlHelper;
require_once("ElasticSearch.php");
require_once("helper/CurlHelper.php");

class Template extends ElasticSearch{


    /**
     * Connection
     */
    protected $indexPrefix;

    /**
     * Constructor Fucntion
     */
    public function __construct($connection = NULL) {        
        $this->connection = $connection;        
    }

    public function getMapping(){
        
        //$url         = $this->connection['server']['host'];
        //$indexPrefix = $this->coonection['indexPrefix'];

        $host = 'http://54.254.232.124:80/';
        $indexPrefix = 'alerts_appl_al_';
        
        $template = '_template/'. $indexPrefix. '*';

        $url = $host . $template;
        
        $response = CurlHelper::httpGetRequest($url);
        print_R($response); die;
        return $this;
    }

}