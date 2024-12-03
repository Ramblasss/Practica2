<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class CsvController extends Controller
{
    public function index(): JsonResponse
    {
        $files = Storage::files();
        $csvFiles = array_filter($files, fn($file) => pathinfo($file, PATHINFO_EXTENSION) === 'csv');

        return response()->json([
            'mensaje' => 'Listado de ficheros',
            'contenido' => array_values($csvFiles),
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $filename = $request->input('filename');
        $content = $request->input('content');

        if (!$filename || !$content) {
            return response()->json([
                'mensaje' => 'Parámetros inválidos',
            ], 422);
        }

        if (Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El fichero ya existe',
            ], 409);
        }

        Storage::put($filename, $content);

        return response()->json([
            'mensaje' => 'Guardado con éxito',
        ], 200);
    }

    public function show(string $id): JsonResponse
    {
        if (!Storage::exists($id)) {
            return response()->json([
                'mensaje' => 'El fichero no existe',
            ], 404);
        }

        $content = Storage::get($id);
        $lines = explode("\n", trim($content));
        $headers = str_getcsv(array_shift($lines));
        $data = array_map(fn($line) => array_combine($headers, str_getcsv($line)), $lines);

        return response()->json([
            'mensaje' => 'Fichero leído con éxito',
            'contenido' => $data,
        ], 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $filename = $id;
        $content = $request->input('content');

        if (!Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El fichero no existe',
            ], 404);
        }

        if (!$content || !is_string($content)) {
            return response()->json([
                'mensaje' => 'Contenido no válido',
            ], 415);
        }

        Storage::put($filename, $content);

        return response()->json([
            'mensaje' => 'Fichero actualizado exitosamente',
        ], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        if (!Storage::exists($id)) {
            return response()->json([
                'mensaje' => 'El fichero no existe',
            ], 404);
        }

        Storage::delete($id);

        return response()->json([
            'mensaje' => 'Fichero eliminado exitosamente',
        ], 200);
    }
}
