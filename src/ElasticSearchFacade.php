<?php
namespace Kaleyra\ElasticBuilder;

// use Kaleyra\ElasticBuilder\ElasticSearch;
// use Kaleyra\ElasticBuilder\ComponentFactory;
require_once("ComponentFactory.php");
require_once("ElasticSearch.php");

class ElasticSearchFacade
{
    public $es               = null;
    public $componentFactory = null;
    

    /**
     * Constructer Function
     */
    public function __construct($connection = null)
    {
        $this->es               = new ElasticSearch($connection);        
        $this->componentFactory = new ComponentFactory( $this->es );        
    }

    /**
     * Getter for Elastic Search
     */
    public function getEs()
    {
        return $this->es;
    }

    /**
     * Magic Fucntion Creator
     */
    public function __call( $name, $arguments=[])
    {
        //Need to Handle Exception        
        return $this->componentFactory->get( $name );
    }

}


// $facadeObj = new ElasticSearchFacade();
// $facadeObj->Search()->from('dsdsd') // should check for same index pattern?!
//     ->where('fkuserid','9562155222')
//     ->where('sender','MEHTAS')
//     ->esOr("Name", "Satish")
//     ->andOr([ ['status' => "DELIVRD"], ['SENDER' => "ABCDE"] ])
//     ->orAnd([ ['status' => "DELIVRD"] ])
//     ->run();