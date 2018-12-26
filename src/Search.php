<?php 

namespace Kaleyra\ElasticBuilder;

// use Kaleyra\ElasticBuilder\Query;
require_once("Query.php");

//class Search extends Controller {
class Search extends Query {


    /**
     * Default From is 0
     */
    protected $from = 0;

    /**
     * Default Size is 10
     */
    protected $size = 10;

    /**
     * Constructor Fucntion
     */
    public function __construct( $es ) {
        parent::__construct( $es );        
    }

    /**
     * Elastic search Size
     */
    public function limit($size = null){
        $this->size = $size;
        return $this;
    }

    /**
     * Getter for Limit
     */
    public function getLimit(){
        return $this->size;
    }

    /**
     * Elastic Search From
     */
    public function offset($from = null){
        $this->from = $from;
        return $this;
    }

    /**
     * Getter for Offset
     */
    public function getOffset(){
        return $this->from;
    }
}