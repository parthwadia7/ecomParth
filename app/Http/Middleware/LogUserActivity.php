<?php
// app/Http/Middleware/LogUserActivity.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            Log::info("User ID {$request->user()->id} accessed {$request->path()} at " . now());
        }
        return $next($request);
    }
}

