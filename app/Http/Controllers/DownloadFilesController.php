<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
class DownloadFilesController extends Controller
{
    public function downloadPDF(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'file_url' => 'required|url'
        ]);

        $fileUrl = $request->input('file_url');

     // Extraer la ruta relativa del archivo desde la URL
     $filePath = $this->extractRelativePath($fileUrl);
     // Verificar si el archivo existe en el disco local
     if (!Storage::disk('public')->exists($filePath)) {
         return response()->json(['error' => 'File not found'], 404);
     }

     // Obtener el nombre del archivo
     $fileName = basename($filePath);

     // Opcional: definir encabezados personalizados
     $headers = [
         'Content-Type' => Storage::disk('public')->mimeType($filePath),
         'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
     ];

     // Retornar la respuesta de descarga
     return Storage::disk('public')->download($filePath, $fileName, $headers);
 }

 private function extractRelativePath($fileUrl)
 {
     // Extraer la ruta después de '/storage/'
     $parsedUrl = parse_url($fileUrl);
     $path = $parsedUrl['path'] ?? '';

     // Reemplazar '/storage/' con ''
     $relativePath = trim(str_replace('/storage/', '', $path), '/');

     return $relativePath;
 }
}
