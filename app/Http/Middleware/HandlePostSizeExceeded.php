<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class HandlePostSizeExceeded
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request method is POST and if the content is empty
        // This can happen when post_max_size is exceeded
        if ($request->isMethod('post') && empty($request->all()) && $request->getContent() !== '') {
            $contentLength = $request->header('Content-Length');
            $postMaxSize = $this->getPostMaxSize();
            
            $message = "The uploaded file exceeds the maximum allowed size of {$postMaxSize}. Your upload was {$this->formatBytes($contentLength)}.";
            
            if ($request->expectsJson() || $request->ajax()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => $message,
                    'error' => 'post_size_exceeded',
                    'max_size' => $postMaxSize,
                    'uploaded_size' => $contentLength
                ], 413);
            }
            
            // For regular form submissions, redirect back with error
            return back()
                ->withInput()
                ->withErrors(['file' => $message])
                ->with('error', $message);
        }

        return $next($request);
    }

    /**
     * Get the post max size in a human-readable format
     */
    private function getPostMaxSize()
    {
        $postMaxSize = ini_get('post_max_size');
        $size = (int) $postMaxSize;
        $unit = strtoupper(substr($postMaxSize, -1));
        
        if ($unit === 'K') {
            $size *= 1024;
        } elseif ($unit === 'M') {
            $size *= 1024 * 1024;
        } elseif ($unit === 'G') {
            $size *= 1024 * 1024 * 1024;
        }
        
        return $this->formatBytes($size);
    }

    /**
     * Format bytes to a human-readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if (!is_numeric($bytes)) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
