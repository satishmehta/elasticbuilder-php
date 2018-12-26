<?php 

namespace Kaleyra\ElasticBuilder;

// use Kaleyra\ElasticBuilder\ElasticSearch;
// use Kaleyra\ElasticBuilder\Helper\CurlHelper;
require_once("ElasticSearch.php");
require_once("helper/CurlHelper.php");

class Query {


    /**
     * Elasticsearch connection instance
     * @var Connection
     */
    public $connection = null;

    /**
     * Filter operators
     * @var array
     */
    protected $operators = [
        "=",
        "!=",
        ">",
        ">=",
        "<",
        "<=",        
    ];

    /**
     * Index Mapping
     * @var array
     */
    protected $mapping;

    /**
     * Query array
     * @var
     */
    protected $query;

    /**
     * Query index name
     * @var
     */
    protected $index;

    /**
     * Query type name
     * @var
     */
    protected $type;

    /**
     * Query body
     * @var array
     */
    public $body = [];

    /**
     * Query bool filter
     * @var array
     */
    protected $filter = [];

    /**
     * Query bool must
     * @var array
     */
    public $must = [];

    /**
     * Query bool must not
     * @var array
     */
    public $must_not = [];

    /**
     * Query bool Should
     * @var array
     */
    public $should = [];

    /**
     * Query OR inside AND
     * @var array
     */
    public $and_or = [];

    /**
     * Query AND inside OR
     * @var array
     */
    public $or_and = [];

    /**
     * Query limit
     * @var int
     */
    protected $take = 0;

    /**
     * Summary Index Value
     */
    public $summaryIndex = null;

    /**
     * Raw Index Value
     */
    public $rawIndex = null;

    /**
     * Query constructor.
     * @param $connection
     */
    function __construct( $es )
    {
        $this->connection = $es;       
    }

    /**
     * Set the index name
     * @param $index
     * @return $this
     */
    public function from($indexString= '')
    {
        $this->index = $indexString;
        return $this;
    }

    /**
     * Get the index name
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set the type name
     * @param $type
     * @return $this
     */
    public function setType($type)
    {

        $this->type = $type;

        return $this;
    }

    /**
     * Get the type name
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * check if it's a valid operator from defined operator array
     * @param $string
     * @return bool
     */
    protected function isOperator($string)
    {

        if (in_array($string, $this->operators)) {
            return true;
        }

        return false;
    }


    /**
     * Set the query where clause
     * @param        $name
     * @param string $operator
     * @param null $value
     * @return $this
     */
    public function where($name, $operator = "=", $value = NULL)
    {

        if (!$this->isOperator($operator)) {
            $value = $operator;
            $operator = "=";
        }

        if ($operator == "=") {
            $this->must[] = ["term" => [$name => $value]];
        }

        if ($operator == ">") {
            $this->must[] = ["range" => [$name => ["gt" => $value]]];
        }

        if ($operator == ">=") {
            $this->must[] = ["range" => [$name => ["gte" => $value]]];
        }

        if ($operator == "<") {
            $this->must[] = ["range" => [$name => ["lt" => $value]]];
        }

        if ($operator == "<=") {
            $this->must[] = ["range" => [$name => ["lte" => $value]]];
        }

        if ($operator == "like") {
            $this->must[] = ["match" => [$name => $value]];
        }

        return $this;
    }

    /**
     * Set the query inverse where clause
     * @param        $name
     * @param string $operator
     * @param null $value
     * @return $this
     */
    public function whereNot($name, $operator = "=", $value = NULL)
    {

        if (!$this->isOperator($operator)) {
            $value = $operator;
            $operator = "=";
        }

        if ($operator == "=") {
            $this->must_not[] = ["term" => [$name => $value]];
        }

        if ($operator == ">") {
            $this->must_not[] = ["range" => [$name => ["gt" => $value]]];
        }

        if ($operator == ">=") {
            $this->must_not[] = ["range" => [$name => ["gte" => $value]]];
        }

        if ($operator == "<") {
            $this->must_not[] = ["range" => [$name => ["lt" => $value]]];
        }

        if ($operator == "<=") {
            $this->must_not[] = ["range" => [$name => ["lte" => $value]]];
        }

        if ($operator == "like") {
            $this->must_not[] = ["match" => [$name => $value]];
        }

        return $this;
    }

    /**
     * Set the query where between clause
     * @param $name
     * @param $first_value
     * @param $last_value
     * @return $this
     */
    public function whereBetween($name, $first_value, $last_value = null)
    {
        if (is_array($first_value) && count($first_value) == 2) {
            $last_value  = $first_value[1];
            $first_value = $first_value[0];
        }

        $this->must[] = ["range" => [$name => ["gte" => $first_value, "lte" => $last_value]]];

        return $this;
    }

    /**
     * Set the query where not between clause
     * @param $name
     * @param $first_value
     * @param $last_value
     * @return $this
     */
    public function whereNotBetween($name, $first_value, $last_value = null)
    {
        if (is_array($first_value) && count($first_value) == 2) {
            $last_value = $first_value[1];
            $first_value = $first_value[0];
        }

        $this->must_not[] = ["range" => [$name => ["gte" => $first_value,   "lte" => $last_value]]];

        return $this;
    }

    /**
     * Set the query where in clause
     * @param       $name
     * @param array $value
     * @return $this
     */
    public function whereIn($name, $value = [])
    {

        $this->must[] = ["terms" => [$name => $value]];

        return $this;
    }

    /**
     * Set the query where not in clause
     * @param       $name
     * @param array $value
     * @return $this
     */
    public function whereNotIn($name, $value = [])
    {
        $this->must_not[] = ["terms" => [$name => $value]];

        return $this;
    }

    /**
     * Script in AND clasue     
     * @param array $scriptValue
     * @return
     */
    public function whereScript($scriptQuery)
    {
        $this->must[] = ["script" => ["script" => $scriptQuery]];
        return $this;
    }

    /**
     * OR condidtion inside AND   
     * @param array $scriptValue
     * @return
     */
    public function andOr( $orArray=''){
        
        foreach($orArray as $key => $val){                        
            $this->and_or[] = ["term" => $val ];
        }
        return $this;
    }

    /**
     * AND condidtion inside OR
     * @param array $scriptValue
     * @return
     */
    public function orAnd( $andArray=''){
        
        foreach($andArray as $key => $val){            
            $this->or_and[] = ["term" => $val ];
        }
        return $this;
    }

    /**
     * Set the Query OR clasue
     */
    public function esOr($name, $operator = "=", $value = NULL)
    {
        
        if (!$this->isOperator($operator)) {
            $value = $operator;
            $operator = "=";
        }

        if ($operator == "=") {          

            $this->should[] = ["term" => [$name => $value]];
        }

        if ($operator == ">") {
            $this->should[] = ["range" => [$name => ["gt" => $value]]];
        }

        if ($operator == ">=") {
            $this->should[] = ["range" => [$name => ["gte" => $value]]];
        }

        if ($operator == "<") {
            $this->should[] = ["range" => [$name => ["lt" => $value]]];
        }

        if ($operator == "<=") {
            $this->should[] = ["range" => [$name => ["lte" => $value]]];
        }

        if ($operator == "like") {
            $this->should[] = ["term" => [$name => $value]];
        }

        return $this;
    }

    /**
     * Generate the query body
     * @return array
     */
    public function getQueryBuild()
    {
        $body = $this->body;
        
        if (count($this->must)) {
            $body["query"]["bool"]["filter"]["bool"]["must"] = $this->must;
        }

        if (count($this->must_not)) {
            $body["query"]["bool"]["filter"]["bool"]["must_not"] = $this->must_not;
        }

        // if (count($this->filter)) {
        //     $body["query"]["bool"]["filter"]["bool"]["must"] = $this->filter;
        // }

        if (count($this->and_or)) {
            $body["query"]["bool"]["filter"]["bool"]["must"][]['bool']['should'] = $this->and_or;
        }

        if (count($this->should)) {
            $body["query"]["bool"]["filter"]["bool"]["should"] = $this->should;
        }

        if (count($this->or_and)) {
            $body["query"]["bool"]["filter"]["bool"]["should"][]['bool']['must'] = $this->or_and;
        }
        
        
        $this->body = $body;

        // return $body;
        return json_encode($body);
    }

    /**
     * set the query body array
     * @param array $body
     * @return $this
     */
    function body($body = [])
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Generate the query to be executed
     * @return array
     */
    public function getLastQuery()
    {
        $query = [];

        $query["index"] = $this->getIndex();

        if ($this->getType()) {
            $query["type"] = $this->getType();
        }

        $query["body"] = $this->getBody();

        $query["size"] = $this->getTake();
         
        return $query;
    }

    /**
     * 
     */
    public function get(){
        
        $assocArray = [
            'total_count' => 0,
            'data' => []
        ];
        
        $body = $this->body;
        
        if (count($this->must)) {
            $body["query"]["bool"]["filter"]["bool"]["must"] = $this->must;
        }

        if (count($this->must_not)) {
            $body["query"]["bool"]["filter"]["bool"]["must_not"] = $this->must_not;
        }       

        if (count($this->and_or)) {
            $body["query"]["bool"]["filter"]["bool"]["must"][]['bool']['should'] = $this->and_or;
        }

        if (count($this->should)) {
            $body["query"]["bool"]["filter"]["bool"]["should"] = $this->should;
        }

        if (count($this->or_and)) {
            $body["query"]["bool"]["filter"]["bool"]["should"][]['bool']['must'] = $this->or_and;
        }
        

        //Appending Size $ From
        $body['size'] = $this->getLimit();
        $body['from'] = $this->getOffset();
        
        $this->body = $body;

        $hostAddress = $this->connection->getHostAddress();
        $index       = Self::getIndex();
        $jsonBody    = json_encode($this->body);       

        $hostArray = [
            'server' => $hostAddress,
            'index'  => $index,
            'search' => '_search'
        ];
        print_R($this->body); die;
        $response = CurlHelper::ESHttpRequest($hostArray, $method = 'GET', $jsonBody);

        $res = CurlHelper::httpRequest($hostArray, $method = 'GET', $jsonBody);

        
        if(is_array($response['hits']) && $response['hits']['total'] >=1 ){
            $assocArray['total_count'] = $response['hits']['total'];

            foreach($response['hits']['hits'] as $key => $val){
                $assocArray['data'][] = $val['_source'];
            }
        }else{
            $assocArray['total_count'] = 0;
            $assocArray['data']  = []; 
        }

        echo "<pre>";
        print_r($assocArray);die;
        
    }




}