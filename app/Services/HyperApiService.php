<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class HyperApiService
{
    protected $base;
    protected $key;
    protected $token;

    public function __construct()
    {
        $this->base = rtrim(env('HYPER_API_BASE', 'https://api.hyperteknoloji.com.tr'), '/');
        $this->key = env('HYPER_API_KEY');
        $this->token = env('HYPER_API_TOKEN');
    }

  
    protected function headers()
    {
        return [
            'Accept' => 'application/json',
            'ApiKey' => $this->key,
            'Authorization' => 'Bearer '.$this->token,
        ];
    }

 
    protected function get($endpoint, $params = [])
    {
        $response = Http::withHeaders($this->headers())
                        ->timeout(10)
                        ->retry(3, 100)
                        ->withoutVerifying()
                        ->get($this->base.'/'.$endpoint, $params);

        return $this->handleResponse($response);
    }

  
    protected function post($endpoint, $data = [])
    {
        $response = Http::withHeaders($this->headers())
                        ->timeout(10)
                        ->retry(3, 100)
                        ->withoutVerifying()
                        ->post($this->base.'/'.$endpoint, $data);

        return $this->handleResponse($response);
    }

   
    protected function put($endpoint, $data = [])
    {
        $response = Http::withHeaders($this->headers())
                        ->timeout(10)
                        ->retry(3, 100)
                        ->withoutVerifying()
                        ->put($this->base.'/'.$endpoint, $data);

        return $this->handleResponse($response);
    }

  
    protected function delete($endpoint)
    {
        $response = Http::withHeaders($this->headers())
                        ->timeout(10)
                        ->retry(3, 100)
                        ->withoutVerifying()
                        ->delete($this->base.'/'.$endpoint);

        return $this->handleResponse($response);
    }
    
    public function fetchProducts($page = 1, $perPage = 12)
    {
    return $this->post('Products/List', [
        'Page' => $page,
        'PageSize' => $perPage
    ]);
    }

    public function fetchProductById($id)
    {
    return $this->post("Products/Detail/{$id}");
    }


    public function createProduct($data)
    {
    return $this->post('Products/Create', $data);
    }

    public function updateProduct($id, $data)
    {
    return $this->post("Products/Update/{$id}", $data);
    }

        public function deleteProduct($id)
    {
    return $this->post("Products/Delete/{$id}", []);
    }

    protected function handleResponse($response)
    {
        \Log::info('API RAW RESPONSE', [
        'status' => $response->status(),
        'body' => $response->body()
        ]);

        if ($response->successful()) {
        return $response->json();
        }

        $error = $response->json()['message'] ?? $response->body() ?? 'Unknown error';
        throw new \Exception("API Error {$response->status()}: {$error}");
    }


    
}

