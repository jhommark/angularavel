<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register the application's response macros.
     *
     * @return void
     */
    public function boot(ResponseFactory $response)
    {
        $response->macro('success', function ($data = [], $message = 'OK') use ($response) {
            return $response->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ]);
        });
        $response->macro('error', function ($message, $status = 422, $errors = []) use ($response) {
            return $response->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], $status);
        });
    }
}
