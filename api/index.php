<?php

/**
 * Laravel Application Entry Point for Vercel Serverless
 * 
 * Este archivo maneja todas las solicitudes HTTP cuando la aplicación
 * se ejecuta en el entorno serverless de Vercel.
 */

// Configurar zona horaria por defecto
date_default_timezone_set('America/Mexico_City');

// Cargar el autoloader de Composer
require __DIR__ . '/../vendor/autoload.php';

// Cargar la aplicación Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Configurar el kernel HTTP
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Capturar la solicitud HTTP
$request = Illuminate\Http\Request::capture();

// Procesar la solicitud y generar respuesta
$response = $kernel->handle($request);

// Enviar la respuesta al cliente
$response->send();

// Terminar la aplicación
$kernel->terminate($request, $response);