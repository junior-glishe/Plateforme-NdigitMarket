<?php
namespace App\Middleware;

class ModeratorMiddleware
{
    public function handle(): void
    {
        (new RoleMiddleware())->handle(['moderator', 'admin']);
    }
}
