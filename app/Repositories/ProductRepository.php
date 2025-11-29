<?php
namespace App\Repositories;

use App\Services\HyperApiService;
use Illuminate\Support\Facades\Cache;

class ProductRepository
{
    protected $api;

    public function __construct(HyperApiService $api)
    {
        $this->api = $api;
    }

    public function getPaged($page,$per)
    {
        $key = "hyper:products:{$page}:{$per}";
        return Cache::remember($key, now()->addMinutes(5), function() use($page,$per){
            return $this->api->fetchProducts($page,$per);
        });
    }
}
