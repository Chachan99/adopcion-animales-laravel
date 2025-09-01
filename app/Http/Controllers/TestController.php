<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            // Verificar si podemos acceder al modelo Noticia
            $noticias = Noticia::take(5)->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'El modelo Noticia funciona correctamente',
                'noticias' => $noticias
            ]);
        } catch (\Exception $e) {
            Log::error('Error al acceder al modelo Noticia: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error al acceder al modelo Noticia',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
