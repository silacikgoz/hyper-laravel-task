<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    protected $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        
        $per = 48;
        $page = max(1, (int) $request->get('page', 1));

        try {
            $result = $this->repo->getPaged($page, $per);
        } catch (\Exception $e) {
            \Log::error('Error fetching products: '.$e->getMessage());
            $result = ['data' => [], 'meta' => ['current_page' => $page, 'last_page' => 1]];
        }
        if (is_array($result) && array_key_exists('data', $result)) {
            $products = $result['data'] ?? [];
            $meta = $result['meta'] ?? [
                'current_page' => $page,
                'last_page' => max(1, (int) ceil(count($products) / max(1, $per)))
            ];
            if (empty($result['meta']) && is_array($products) && count($products) > $per) {
                $total = count($products);
                $last = (int) ceil($total / $per);
                $start = ($page - 1) * $per;
                $slice = array_slice($products, $start, $per);
                $products = $slice;
                $meta = ['current_page' => $page, 'last_page' => $last];
            }
        }
        elseif (is_array($result)) {
            $all = $result;
            $total = count($all);
            $last = (int) ceil($total / $per);
            $start = ($page - 1) * $per;
            $slice = array_slice($all, $start, $per);
            $products = $slice;
            $meta = ['current_page' => $page, 'last_page' => max(1, $last)];
        }
        else {
            $products = [];
            $meta = ['current_page' => $page, 'last_page' => 1];
        }
        return view('products.index', [
            'products' => $products,
            'meta' => $meta
        ]);
    }
}
