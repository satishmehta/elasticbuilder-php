<?php
namespace Kaleyra\ElasticBuilder;

require_once('./src/ElasticSearchFacade.php');
require_once('./src/helper/ElasticSearchHelper.php');
// use Kaleyra\ElasticBuilder\ElasticSearchFacade;
// use Kaleyra\ElasticBuilder\Helper\ElasticSearchHelper;

$config = [
	"server" => [
		[
			"host" => '10.20.10.81',
			"port" => 9200,
			"scheme" => "http"
		]
	]
];

// echo "<pre>";

$facadeObj = new ElasticSearchFacade($config);

        // $index = ElasticSearchHelper::generateIndexRange('promo_', 20180902, 20180910);
        $index = "promo_20180902,promo_20180903,promo_20180904,promo_20180905,promo_20180906,promo_20180907,promo_20180908,promo_20180909,promo_2018091
        0";

        $res = $facadeObj->Search()
            ->from($index)
            ->where('fkuserid','1452405599')
            ->where('sender','BULKSMS')
            ->where('status','DELIVRD')
            ->groupBy([ ['status' => ['size' => 50] ] ])            
            ->groupBy([ ['fkuserid' => ['size' => 10, 'missing'=> 'other_status']] ])
            ->groupBy([ ['sender' => ['size' => 50]]])
            ->limit(20)
            ->offset(15)
            ->get();

        
        print_R($res); die;


?>
