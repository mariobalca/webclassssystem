<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Website;
use App\Category;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class WebsitesController extends Controller
{
    public function index() {
        $websites = Website::with('categories')->get();
        
        return response()->json($websites);
    }

    public function show(Website $website) {
    	if(!$website) {
            return response()->json([
                'message'   => 'Record not found',
            ], 404);
        }

    	$categories = array();
    	foreach ($website->categories()->get() as $category) {
    		array_push($categories, $category);
    	}
        return response()->json(['url' => $website->url, 'categories' => $categories]);
    }

    public function store(Request $request) {
    	$website = Website::firstOrCreate(['url' => $request->url]);

    	$key = env('WEBSHRINKER_KEY');
    	$secret_key = env('WEBSHRINKER_SECRET');
		
		$base64_target_website = str_replace(array('+', '/'), array('-', '_'), base64_encode($website->url));
		$url = sprintf("https://api.webshrinker.com/categories/v3/%s", $base64_target_website);

    	$httpClient = new Client();
    	$res = $httpClient->request('GET', $url, [
    		'auth' => [$key, $secret_key],
    		'exceptions' => false
    	]);

    	$result = json_decode($res->getBody());
        $category_data = $result->data[0]->categories;

        $categories = array();

        foreach ($category_data as $entry){
        	$category = Category::firstOrCreate(
        		['api_id' => $entry->id],
        		['label' => $entry->label],
        		['score' => $entry->score],
        		['confident' => $entry->confident],
        		['parent' => $entry->parent]
        	);
        	array_push($categories, $category->id);
        }
        $website->categories()->syncWithoutDetaching($categories);

        return response()->json($website, 201);
    }
}
