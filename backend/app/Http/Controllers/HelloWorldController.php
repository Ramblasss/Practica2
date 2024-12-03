<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HelloWorldController extends Controller
{
    // Listado de archivos
    public function index()
    {
        $files = Storage::files(); // Obtiene todos los archivos en el disco local
        return response()->json([
            'mensaje' => 'Listado de ficheros',
            'contenido' => $files,
        ], 200);
    }

    // Almacenar archivo
    public function store(Request $request)
    {
        $filename = $request->input('filename');
        $content = $request->input('content');

        if (Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo ya existe',
            ], 409); // Conflicto, el archivo ya existe
        }

        Storage::put($filename, $content);

        return response()->json([
            'mensaje' => 'Guardado con éxito',
        ], 200);
    }

    // Mostrar archivo
    public function show($filename)
    {
        if (!Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'Archivo no encontrado',
            ], 404); // No encontrado
        }

        $content = Storage::get($filename);

        return response()->json([
            'mensaje' => 'Archivo leído con éxito',
            'contenido' => $content,
        ], 200);
    }

    // Actualizar archivo
    public function update(Request $request, $filename)
    {
        if (!Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo no existe',
            ], 404); // No encontrado
        }

        $content = $request->input('content');
        Storage::put($filename, $content);

        return response()->json([
            'mensaje' => 'Actualizado con éxito',
        ], 200);
    }

    // Eliminar archivo
    public function destroy($filename)
    {
        if (!Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo no existe',
            ], 404); // No encontrado
        }

        Storage::delete($filename);

        return response()->json([
            'mensaje' => 'Eliminado con éxito',
        ], 200);
    }
}
