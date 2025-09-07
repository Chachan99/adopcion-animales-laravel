# Solución para Reinicios Constantes en Render

Este documento proporciona una guía completa para diagnosticar y solucionar los reinicios constantes en aplicaciones Laravel desplegadas en Render.com.

## 🔍 Causas Comunes de Reinicios

### 1. **Problemas de Memoria**
- Aplicación excede el límite de RAM del plan
- Memory leaks en el código
- Logs muy grandes
- Cache excesivo en memoria

### 2. **Errores de Configuración**
- Variables de entorno faltantes o incorrectas
- Configuración de base de datos incorrecta
- Drivers de cache/sesión mal configurados

### 3. **Problemas de Base de Datos**
- Conexiones no cerradas correctamente
- Consultas que consumen mucha memoria
- Timeouts de conexión

### 4. **Errores en el Código**
- Excepciones no manejadas
- Bucles infinitos
- Procesos que no terminan

## 🛠️ Pasos para Diagnosticar

### Paso 1: Ejecutar Script de Diagnóstico
```bash
php render-restart-diagnostics.php
```

### Paso 2: Revisar Logs de Render
1. Ve al Dashboard de Render
2. Selecciona tu servicio
3. Ve a la pestaña "Logs"
4. Busca mensajes como:
   - `Process exited with code`
   - `Out of memory`
   - `Fatal error`
   - `Connection timeout`

### Paso 3: Verificar Métricas de Recursos
1. En el Dashboard de Render
2. Ve a la pestaña "Metrics"
3. Revisa:
   - Uso de CPU
   - Uso de memoria
   - Tiempo de respuesta

## ⚡ Soluciones Inmediatas

### 1. Optimizar Variables de Entorno

Agrega estas variables en Render:

```env
# Optimización para producción
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stderr

# Cache y sesiones optimizadas
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Configuración de base de datos
DB_CONNECTION=pgsql
# DATABASE_URL ya configurada automáticamente

# Optimización de memoria
PHP_MEMORY_LIMIT=512M
```

### 2. Agregar Redis (Recomendado)

1. En Render Dashboard:
   - Crea un nuevo servicio Redis
   - Copia la URL de conexión

2. Agrega estas variables:
```env
REDIS_URL=redis://tu-redis-url
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 3. Optimizar Configuración de Laravel

Crea/actualiza el archivo `config/cache.php`:

```php
// Configuración optimizada para Render
'default' => env('CACHE_DRIVER', 'redis'),

'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
    ],
],
```

### 4. Comandos de Optimización

Agrega estos comandos al build de Render:

```bash
# En el script de build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 5. Configurar Health Checks

Crea una ruta de health check en `routes/web.php`:

```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'memory' => memory_get_usage(true),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected'
    ]);
});
```

## 🔧 Configuración Avanzada

### 1. Optimizar PHP-FPM

Crea `docker/php-fpm.conf`:

```ini
[www]
pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
pm.max_requests = 500
```

### 2. Configurar Nginx

Actualiza `docker/nginx.conf`:

```nginx
worker_processes auto;
worker_connections 1024;

http {
    client_max_body_size 10M;
    keepalive_timeout 65;
    
    # Configuración de buffer
    client_body_buffer_size 128k;
    client_header_buffer_size 1k;
    large_client_header_buffers 4 4k;
    output_buffers 1 32k;
    postpone_output 1460;
}
```

### 3. Limpiar Logs Automáticamente

Crea un comando artisan para limpiar logs:

```php
// app/Console/Commands/ClearLogs.php
class ClearLogs extends Command
{
    protected $signature = 'logs:clear';
    
    public function handle()
    {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
            $this->info('Logs cleared successfully');
        }
    }
}
```

Programa la limpieza en `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('logs:clear')->daily();
}
```

## 📊 Monitoreo y Prevención

### 1. Implementar Logging Estructurado

```php
// En tus controladores
Log::info('User action', [
    'user_id' => auth()->id(),
    'action' => 'view_animals',
    'memory_usage' => memory_get_usage(true)
]);
```

### 2. Middleware de Monitoreo

```php
class MemoryMonitoringMiddleware
{
    public function handle($request, Closure $next)
    {
        $startMemory = memory_get_usage(true);
        
        $response = $next($request);
        
        $endMemory = memory_get_usage(true);
        $memoryUsed = $endMemory - $startMemory;
        
        if ($memoryUsed > 50 * 1024 * 1024) { // 50MB
            Log::warning('High memory usage detected', [
                'route' => $request->route()->getName(),
                'memory_used' => $memoryUsed,
                'total_memory' => $endMemory
            ]);
        }
        
        return $response;
    }
}
```

## 🚨 Soluciones de Emergencia

### Si los reinicios continúan:

1. **Upgrade del Plan de Render**
   - Considera un plan con más RAM
   - Más CPU puede ayudar con el procesamiento

2. **Implementar Circuit Breaker**
   ```php
   // Para consultas pesadas
   try {
       $result = Cache::remember('heavy_query', 3600, function () {
           return DB::table('animals')->with('relations')->get();
       });
   } catch (Exception $e) {
       Log::error('Database query failed', ['error' => $e->getMessage()]);
       return collect(); // Retorna colección vacía como fallback
   }
   ```

3. **Configurar Rate Limiting**
   ```php
   // En routes/web.php
   Route::middleware(['throttle:60,1'])->group(function () {
       // Rutas que consumen muchos recursos
   });
   ```

## ✅ Checklist de Verificación

- [ ] Variables de entorno configuradas correctamente
- [ ] Redis configurado para cache y sesiones
- [ ] Comandos de optimización ejecutados
- [ ] Logs limpiados regularmente
- [ ] Health checks implementados
- [ ] Monitoreo de memoria activo
- [ ] Rate limiting configurado
- [ ] Plan de Render adecuado para la carga

## 📞 Soporte Adicional

Si el problema persiste después de implementar estas soluciones:

1. Ejecuta `render-restart-diagnostics.php` y comparte el resultado
2. Proporciona los logs más recientes de Render
3. Indica el plan actual de Render que estás usando
4. Describe el patrón de los reinicios (cada cuánto tiempo, en qué momentos)

---

**Nota**: La mayoría de problemas de reinicio se solucionan configurando Redis y optimizando las variables de entorno. Si tienes un plan gratuito de Render, considera hacer upgrade para obtener más recursos.