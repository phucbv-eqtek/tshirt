<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as AppController;;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * WebCrawlerController
 * @author Crawler
 */
class WebCrawlerController extends AppController {

    /**
     * Search snapdeal products by given url
     * @param Request $request
     */
    public function searchProductsByUrl(Request $request) {
        $pageUrl = 'http://www.tradetu.com';
        if(!$pageUrl) {
            return response()->json($this->getActionStatus("ERROR", "Product page url not found."));
        }
        $errors = array();

        $result = $this->makeWebRequest($pageUrl, $errors);
        if(empty($errors)) {
            $response['content'] = $this->parseQuickProductsJson($result, '');
            $response['Status'] = 'SUCCESS';
            $response['Message'] = 'Products downloaded successfully';
        } else {
            $response['Errors'] = $errors;
            $response['Status'] = 'ERROR';
            $response['Message'] = "Error in fetching products from the url. Errors: " . implode('|', $errors);
        }
        return response()->json($response);
    }

    /**
     * Parse json string into array
     * @param string $result
     * @param string $category
     */
    private function parseQuickProductsJson($result, $category) {
        $response = '';
        try {
            $crawler = new Crawler($result);
            $filter = $crawler->filter('div.product-tuple-listing');

            foreach ($filter as $i => $domElement) {
                $_crawler = new Crawler($domElement);

                $arr[$i] = array(
                    'productName' => $_crawler->filter('p.product-title')->text(),
                    'productUrl' => $_crawler->filter('a.noUdLine')->attr('href'),
                    'imageUrl' => $_crawler->filter('input.compareImg')->attr('value'),
                    'offerPrice' => $_crawler->filter('span.product-price')->text(),
                    'inStock' => $_crawler->filter('span.badge-soldout')->count() > 0 ? false : true,
                    'size' => $_crawler->filter('span.attr-value')
                );
            }
        } catch (Exception $ex) {
        }
        return $arr;
    }

    /**
     * Make web request to affiliate server url
     * @param String $url
     */
    private function makeWebRequest($url, &$errors) {
        $client = new Client();
        $response = $client->get($url);

        if($response->getStatusCode() == 200) {
            return (string)$response->getBody();
        } else {
            array_push($errors, $response->getReasonPhrase());
            return;
        }
    }
}