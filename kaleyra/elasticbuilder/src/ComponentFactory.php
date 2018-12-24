<?php

namespace Kaleyra\ElasticBuilder;

use Kaleyra\ElasticBuilder\Search;
use Kaleyra\ElasticBuilder\Query;



class ComponentFactory
{

    public $es = null;
    public function __construct( $es )
    {
        $this->es = $es;
    }

    public function get( $name, $arguments = [])
    {        
        switch( strtolower($name) ) {
            case 'search':
                return new Search( $this->es );
            case 'query':
                return new Query( $this->es );
            default:
                return false;
        }
    }
}