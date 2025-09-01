<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Route::get('/db-check', function() {
    try {
        // Check if the table exists
        if (!Schema::hasTable('animales')) {
            return response()->json(['error' => 'Table animales does not exist'], 404);
        }

        // Get the column listing with their types
        $columns = DB::select('DESCRIBE animales');
        
        // Get the first row to see the data (if any)
        $firstRow = DB::table('animales')->first();
        
        return response()->json([
            'columns' => $columns,
            'first_row' => $firstRow
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
