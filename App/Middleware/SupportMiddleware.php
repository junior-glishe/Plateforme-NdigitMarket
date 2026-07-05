<?php
namespace App\Middleware;

class SupportMiddleware
{
    public function handle(): void
    {
        (new RoleMiddleware())->handle(['support', 'admin']);
    }
}
