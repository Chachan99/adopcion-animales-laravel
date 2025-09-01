<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

Route::get('/check-fundacion', function() {
    try {
        // Check if the table exists
        if (!Schema::hasTable('perfil_fundaciones')) {
            return response()->json(['error' => 'Table perfil_fundaciones does not exist'], 404);
        }

        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'No authenticated user'], 401);
        }

        // Get the fundacion profile for the current user
        $fundacion = DB::table('perfil_fundaciones')
            ->where('usuario_id', $user->id)
            ->first();

        // Get the table structure
        $columns = DB::select('DESCRIBE perfil_fundaciones');
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'fundacion' => $fundacion,
            'table_structure' => $columns
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
