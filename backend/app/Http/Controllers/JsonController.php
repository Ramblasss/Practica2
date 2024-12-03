<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JsonController extends Controller
{
    // Método para obtener la lista de archivos JSON
    public function index()
    {
        // Obtiene los archivos que están en la carpeta 'app' y terminan en '.json'
        $files = Storage::files('app');
        $jsonFiles = array_filter($files, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'json';
        });

        // Devuelve los archivos JSON disponibles
        return response()->json([
            'mensaje' => 'Operación exitosa',
            'contenido' => array_values(array_map('basename', $jsonFiles)),  // Usar array_values para asegurar índices numéricos
        ]);
    }

    // Método para almacenar un nuevo archivo JSON
    public function store(Request $request)
    {
        // Recibe los datos enviados
        $filename = $request->input('filename');
        $content = $request->input('content');

        // Verifica si el contenido es un JSON válido
        $decodedContent = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'mensaje' => 'Contenido no es un JSON válido'
            ], 415);
        }

        // Ruta para almacenar el archivo
        $filePath = 'app/' . $filename;

        // Verifica si el archivo ya existe
        if (Storage::exists($filePath)) {
            return response()->json([
                'mensaje' => 'El fichero ya existe'
            ], 409);
        }

        // Almacena el archivo JSON
        Storage::put($filePath, $content);

        return response()->json([
            'mensaje' => 'Fichero guardado exitosamente'
        ], 200);
    }

    // Método para mostrar el contenido de un archivo JSON
    public function show($filename)
    {
        $filePath = 'app/' . $filename;

        // Verifica si el archivo existe
        if (!Storage::exists($filePath)) {
            return response()->json([
                'mensaje' => 'El fichero no existe'
            ], 404);
        }

        // Lee el contenido del archivo
        $content = json_decode(Storage::get($filePath), true);

        return response()->json([
            'mensaje' => 'Operación exitosa',
            'contenido' => $content,
        ]);
    }

    // Método para actualizar un archivo JSON
    public function update(Request $request, $filename)
    {
        $filePath = 'app/' . $filename;

        // Verifica si el archivo existe
        if (!Storage::exists($filePath)) {
            return response()->json([
                'mensaje' => 'El fichero no existe'
            ], 404);
        }

        // Recibe los datos enviados
        $content = $request->input('content');

        // Verifica si el contenido es un JSON válido
        $decodedContent = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'mensaje' => 'Contenido no es un JSON válido'
            ], 415);
        }

        // Actualiza el archivo con el nuevo contenido
        Storage::put($filePath, $content);

        return response()->json([
            'mensaje' => 'Fichero actualizado exitosamente'
        ], 200);
    }

    // Método para eliminar un archivo JSON
    public function destroy($filename)
    {
        $filePath = 'app/' . $filename;

        // Verifica si el archivo existe
        if (!Storage::exists($filePath)) {
            return response()->json([
                'mensaje' => 'El fichero no existe'
            ], 404);
        }

        // Elimina el archivo
        Storage::delete($filePath);

        return response()->json([
            'mensaje' => 'Fichero eliminado exitosamente'
        ], 200);
    }
}
