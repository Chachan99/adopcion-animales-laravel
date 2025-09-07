<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MemoryMonitoringMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        $startPeakMemory = memory_get_peak_usage(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        $endPeakMemory = memory_get_peak_usage(true);
        
        $executionTime = ($endTime - $startTime) * 1000; // en milisegundos
        $memoryUsed = $endMemory - $startMemory;
        $peakMemoryUsed = $endPeakMemory - $startPeakMemory;
        
        // Definir umbrales
        $memoryThreshold = 50 * 1024 * 1024; // 50MB
        $timeThreshold = 5000; // 5 segundos
        $peakMemoryThreshold = 100 * 1024 * 1024; // 100MB
        
        $shouldLog = false;
        $logLevel = 'info';
        $warnings = [];
        
        // Verificar uso de memoria
        if ($memoryUsed > $memoryThreshold) {
            $shouldLog = true;
            $logLevel = 'warning';
            $warnings[] = 'High memory usage detected';
        }
        
        // Verificar tiempo de ejecución
        if ($executionTime > $timeThreshold) {
            $shouldLog = true;
            $logLevel = 'warning';
            $warnings[] = 'Slow response time detected';
        }
        
        // Verificar memoria pico
        if ($peakMemoryUsed > $peakMemoryThreshold) {
            $shouldLog = true;
            $logLevel = 'warning';
            $warnings[] = 'High peak memory usage detected';
        }
        
        // Verificar memoria total del sistema
        $totalMemory = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        
        if ($memoryLimit !== '-1') {
            $limitBytes = $this->parseMemoryLimit($memoryLimit);
            $memoryPercentage = ($totalMemory / $limitBytes) * 100;
            
            if ($memoryPercentage > 80) {
                $shouldLog = true;
                $logLevel = 'error';
                $warnings[] = 'Critical memory usage: ' . round($memoryPercentage, 2) . '%';
            } elseif ($memoryPercentage > 60) {
                $shouldLog = true;
                $logLevel = 'warning';
                $warnings[] = 'High system memory usage: ' . round($memoryPercentage, 2) . '%';
            }
        }
        
        // Log si es necesario
        if ($shouldLog || config('app.debug')) {
            $logData = [
                'route' => $request->route() ? $request->route()->getName() : 'unknown',
                'method' => $request->method(),
                'url' => $request->url(),
                'user_id' => auth()->id(),
                'execution_time_ms' => round($executionTime, 2),
                'memory_used_mb' => round($memoryUsed / 1024 / 1024, 2),
                'peak_memory_used_mb' => round($peakMemoryUsed / 1024 / 1024, 2),
                'total_memory_mb' => round($totalMemory / 1024 / 1024, 2),
                'response_status' => $response->getStatusCode(),
                'warnings' => $warnings
            ];
            
            if ($logLevel === 'error') {
                Log::error('Critical performance issue detected', $logData);
            } elseif ($logLevel === 'warning') {
                Log::warning('Performance issue detected', $logData);
            } else {
                Log::info('Request performance metrics', $logData);
            }
        }
        
        // Agregar headers de debug si está habilitado
        if (config('app.debug')) {
            $response->headers->set('X-Memory-Used', round($memoryUsed / 1024 / 1024, 2) . 'MB');
            $response->headers->set('X-Execution-Time', round($executionTime, 2) . 'ms');
            $response->headers->set('X-Peak-Memory', round($endPeakMemory / 1024 / 1024, 2) . 'MB');
        }
        
        return $response;
    }
    
    /**
     * Parse memory limit string to bytes
     */
    private function parseMemoryLimit(string $limit): int
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int) $limit;
        
        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }
}