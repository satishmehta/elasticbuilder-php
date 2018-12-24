<?php 
namespace Kaleyra\ElasticBuilder\Helper;

use GuzzleHttp\Client;

class CurlHelper {
   

    public static function httpRequest($path = array(), $method = 'GET', $data = null){

        if (!$path['index']) {
            echo 'Warning: Index needs a value.';
            exit;
        }
        
        $url = $path['server'] . $path['index'] . '/' .$path['search'] .'/';

        $client = new \GuzzleHttp\Client([
            'base_uri' => $url,
        ]);
          

        $response = $client->post([
            'debug'        => TRUE,
            'body'         => $data,
            'headers'      => [
            'Content-Type' => 'application/json',
            ]
        ]);

        $body = $response->getBody();
        print_r(json_decode((string) $body));
        die;
    }



    /**
     * Http Client Request for Elastic Search
     * @param  array  $path   [description]
     * @param  string $method [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    public static function httpGetRequest($url){
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Http Client Request for Elastic Search
     * @param  array  $path   [description]
     * @param  string $method [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    public static function ESHttpRequest($path = array(), $method = 'GET', $data = null)
    {
        if (!$path['index']) {
            echo 'Index needs a value.';
            exit;
        }
        
        $url = $path['server'] . $path['index'] . '/' .$path['search'] .'/';
 
        $headers = array('Accept: application/json', 'Content-Type: application/json', );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, '9200');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        switch($method) {
            case 'GET' :
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'POST' :
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT' :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'DELETE' :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        $response = curl_exec($ch);
        
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return json_decode($response, true);
    }
}