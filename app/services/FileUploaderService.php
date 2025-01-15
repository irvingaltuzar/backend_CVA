<?php

namespace App\Services;

use App\Models\MessagesFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploaderService
{
	public function store($file, Array $payload)
	{
		$now = Carbon::now()->format('Y-m-d-H-m-s');

		$alias_file=substr(str_shuffle(str_repeat('ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz01234567890123456789012345678901234567890123456789',15)),0,15);

		$ext = $file->getClientOriginalExtension();
		$path = "{$payload['type']}/{$payload['id']}/";
		$filename = "{$alias_file}.{$ext}";

		$file->storeAs($path, "{$filename}", ['disk' => 'public']);
		// Storage::disk('public')->put($path, $file);
		return [
			'filename' => $filename
		];
	}

	// 	public function upload($file, Array $payload)
	// {
	//     // Generar un nombre de archivo único
	// 	$alias_file = substr(str_shuffle(str_repeat('ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz0123456789', 15)), 0, 15);
	// 	// $alias_file = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		
	// 	// Obtener la extensión del archivo desde el objeto $file
	// 	$ext = $file->getClientOriginalExtension();
		
	// 	// Generar el nombre final del archivo
	// 	$filename = "{$alias_file}.{$ext}";
		
	// 	// Definir la ruta donde se almacenará el archivo
	// 	$path = "{$payload['type']}/{$payload['id']}/";

	// 	// Almacenar el archivo en el disco 'public'
	// 	Storage::disk('public')->putFileAs($path, $file, $filename);

	// 	// Retornar el nombre del archivo
	// 	return [
	// 		'filename' => $filename
	// 	];
	// }

	public function upload($file, Array $payload)
	{
		  // Verificar si el archivo es una cadena base64
		  if (is_array($file) && isset($file['data']) && is_string($file['data']) && strpos($file['data'], 'data:image') === 0) {
			// Si es base64, extraer la parte base64 de la cadena
			$fileData = explode(',', $file['data']); // Se accede a la propiedad 'data' de $file
			$fileContent = base64_decode($fileData[1]);
	
			// Obtener la extensión del archivo a partir del tipo de archivo
			preg_match('/data:image\/(.*);base64/', $fileData[0], $matches);
			$ext = $matches[1];
	
			// Generar un nombre de archivo único
	        $alias_file = pathinfo($file['name'], PATHINFO_FILENAME); // Se accede a la propiedad 'name' de $file
			$filename = "{$alias_file}.{$ext}";
	
			// Definir la ruta donde se almacenará el archivo
			$path = "{$payload['type']}/{$payload['id']}/";
	
			// Almacenar el archivo en el disco 'public'
			Storage::disk('public')->put($path . $filename, $fileContent);
	
			// Retornar el nombre del archivo
			return [
				'filename' => $filename
			];
		}
	

    // Si no es base64, seguir con el procesamiento de archivos tradicionales
    else {
		if ($file instanceof \Illuminate\Http\UploadedFile) {
        // Generar un nombre de archivo único
    	$alias_file = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		
		// Obtener la extensión del archivo desde el objeto $file
		$ext = $file->getClientOriginalExtension();
		
		// Generar el nombre final del archivo
		$filename = "{$alias_file}.{$ext}";
		
		// Definir la ruta donde se almacenará el archivo
		$path = "{$payload['type']}/{$payload['id']}/";

		// Almacenar el archivo en el disco 'public'
		Storage::disk('public')->putFileAs($path, $file, $filename);

		// Retornar el nombre del archivo
		return [
			'filename' => $filename
		];
    }
	else {
	// Si el archivo no es un objeto UploadedFile ni un string base64, manejar el error
	throw new \Exception("El archivo no es válido.");
}
	}
}



		public function uploadDoc($file, array $payload)
		{
			// Verifica si el archivo es una instancia de UploadedFile y es válido
			if (!$file instanceof \Illuminate\Http\UploadedFile || !$file->isValid()) {
				throw new \Exception('El archivo no es válido o no se ha enviado correctamente');
			}
		
			// Generar un nombre de archivo único
			$alias_file = $this->getDocumentDetails($payload['cat_document_id']);
		
			// Obtener la extensión del archivo desde el objeto $file
			$ext = $file->getClientOriginalExtension();
			$time = time();    
			// Generar el nombre final del archivo
			$filename = "{$payload['id']}_{$time}_{$alias_file['code']}.{$ext}";
		
			// Definir la ruta donde se almacenará el archivo
			$path = "{$payload['type']}/{$payload['id']}/";
		
			// Almacenar el archivo en el disco 'public'
			Storage::disk('public')->putFileAs($path, $file, $filename);
		
			// Retornar el nombre del archivo
			return [
				'filename' => $filename
			];
		}
		function getDocumentDetails($id) {
			$documentMapping = [
				1 => ['name' => 'comprobante de vigencia de derechos del imss', 'code' => 'CVDIM_CPSUA'],
				2 => ['name' => 'formato de análisis de tarea segura', 'code' => 'FATS'],
				3 => ['name' => 'constancia de capacitación', 'code' => 'CC'],
				4 => ['name' => 'plan de trabajo a realizar', 'code' => 'PTAR'],
				5 => ['name' => 'Otros', 'code' => 'OTR'],
				5 => ['name' => 'Imagenes', 'code' => 'Img'],
			];
			
			return $documentMapping[$id] ?? ['name' => 'Desconocido', 'code' => 'N/A'];
		}

}
