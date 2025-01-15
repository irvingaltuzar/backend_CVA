<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizePermitRequest;
use App\Http\Requests\StoreWorkPermitRequest;
use Illuminate\Support\Facades\Storage;
use App\Services\FileUploaderService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkPermitPdf;
use Illuminate\Http\Request;
use App\Models\WorkPermit;
use App\Models\User;
use App\Models\WorkPermitFile;
use App\Models\WorkPermitComments;
use App\Models\CatHighRiskJobs;
use App\Models\WorkPermitFileDocuments;
use App\Services\SendEmailService;
use ZipArchive;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Pagination\LengthAwarePaginator;
class WorkPermitController extends Controller
{
	private $userRepository, $fileUploader, $sendEmail;

	public function __construct(UserRepository $userRepository, FileUploaderService $fileUploader, SendEmailService $sendEmail)
	{
			$this->userRepository = $userRepository;
			$this->fileUploader = $fileUploader;
			$this->sendEmail = $sendEmail;
	}

    public function store(StoreWorkPermitRequest $request)
	{
		$user = User::where('SEG_USUARIOS_usuarioId', Auth::user()->usuarioId)->first();
		$high_risk_bol = $request->has('high_risk_bol') ? 1 : 0;
		$high_risk_type = $request->has('cat_high_risk_type') ?  $request['cat_high_risk_type'] : null;
		$work_permit = WorkPermit::create([
			'cat_work_permit_type_id' => $request['cat_work_permit_type_id'],
			'cat_brand_id' => $request['cat_brand_id'],
			'qr_code' => Uuid::uuid4(),
			'description' => $request['description'],
			'work_area' => $request['work_area'],
			'involved_staff' => $request['involved_staff'],
			'warning_phone' => $request['warning_phone'],
			'start' => $request['permit_date'],
			'end' => $request['end_date'],
			'responsable_id' => $user->id,
			'authorized_by_id' => 1,
			'authorized' => 0,
			'high_risk' => $high_risk_bol,
			'cat_high_risk_id' => $high_risk_type,
			'environment_id' => $request->current_environment,
			'created_at' => Carbon::now(),
		]);

		

		 if (!!$request['files']) {
			foreach ($request['files'] as $index => $file) {
				// Subir el archivo usando el método upload
				$uploadedFile = $this->fileUploader->upload($file, [
					'type' => 'WorkPermits',
					'id' => $work_permit->id
				]);
		
				// Almacenar la información del archivo en la base de datos
				$this->storeWorkPermitFile($work_permit->id, $uploadedFile['filename'], 6);
			}
		} else {
			// return false;
		}

		if ($request->has('high_risk_files') && $request->has('high_risk_files_cat')) {
			foreach ($request->file('high_risk_files') as $key => $file) {
				// Asegúrate de que el archivo es válido
				if ($file->isValid()) {
					$catValue = $request->input("high_risk_files_cat.$key", null); // Obtén el valor correspondiente de `high_risk_files_cat`
					
					  // Validar si el archivo es PDF y su MIME type
					  if ($file->getMimeType() === 'application/pdf') {
						// Procesar PDF normalmente
						$filef = $this->fileUploader->uploadDoc($file, [
						'type' => 'WorkPermits',
						'id' => $work_permit->id,
						'cat_document_id' => $catValue
					]);
		
					// Guarda el archivo y su categoría en la base de datos
					$this->storeWorkPermitFile($work_permit->id, $filef['filename'], $catValue);
				}else if ($file->getMimeType() === 'application/zip') {
					// Validar el contenido del archivo ZIP
					$zip = new \ZipArchive;
					if ($zip->open($file->getRealPath()) === true) {
						$allowedExtensions = ['pdf', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx']; // Extensiones permitidas
						$isValidZip = true;
	
						for ($i = 0; $i < $zip->numFiles; $i++) {
							$filename = $zip->getNameIndex($i);
							$extension = pathinfo($filename, PATHINFO_EXTENSION);
	
							if (!in_array(strtolower($extension), $allowedExtensions)) {
								$isValidZip = false;
								break;
							}
						}
	
						$zip->close();
	
						if ($isValidZip) {
							// Procesar archivo ZIP si contiene solo archivos permitidos
							$filef = $this->fileUploader->uploadDoc($file, [
								'type' => 'WorkPermits',
								'id' => $work_permit->id,
								'cat_document_id' => $catValue
							]);
	
							$this->storeWorkPermitFile($work_permit->id, $filef['filename'], $catValue);
						} else {
							// Manejo de error: ZIP contiene archivos no permitidos
							return response()->json(['error' => 'El archivo ZIP contiene archivos no permitidos. Archivo: '.$file->getClientOriginalName()], 400);
						}
					} else {
						// Manejo de error: no se pudo abrir el archivo ZIP
						return response()->json(['error' => 'No se pudo abrir el archivo ZIP. Archivo: '.$file->getClientOriginalName()], 400);
					}
				} else {
					// Manejo de error: tipo de archivo no permitido
					return response()->json(['error' => 'Tipo de archivo no permitido.'], 400);
				}
				}
			}
		} 

		if($high_risk_bol==1){
			$usersMails = $this->userRepository->getUserPermitHighRisk();

			$this->sendEmail->newWorkHighRiskPermit($work_permit, 6,  $work_permit->environment_id,$usersMails);
		 }else{
			$this->sendEmail->newWorkPermit($work_permit, 1, $request->current_environment);

		 }


		$work_permit->load(['user.userSec', 'type', 'authorizedBy']);

		return response()->json($work_permit, JSON_UNESCAPED_UNICODE);
	}
	public function update(StoreWorkPermitRequest $request)
	{
		$user = User::where('SEG_USUARIOS_usuarioId', Auth::user()->usuarioId)->first();

		  // Buscar el permiso de trabajo por ID
		  $work_permit = WorkPermit::findOrFail($request["id"]);
		  $high_risk_bol = $request->has('high_risk_bol') ? 1 : 0;
		  $high_risk_type = $request->has('cat_high_risk_type') ?  $request['cat_high_risk_type'] : null;
		  // Actualizar los campos
		  $work_permit->update([
			//   'cat_work_permit_type_id' => (int) $request->input('cat_work_permit_type_id'), // Asegurar que sea un entero
			//   'qr_code' => Uuid::uuid4(), // Generar un nuevo UUID
			  'description' => $request['description'],
			  'work_area' => $request['work_area'],
			  'involved_staff' => $request['involved_staff'],
			  'warning_phone' =>$request['warning_phone'],
			  'start' => $request['permit_date'],
			  'end' => $request['end_date'],
			  'responsable_id' => $user->id,
			  'authorized_by_id' => 1, // Puedes cambiar esto según tus necesidades
			  'authorized' => 0,
			  'high_risk' => $high_risk_bol,
			  'cat_high_risk_id' => $high_risk_type,
			  'updated_at' => Carbon::now(),
		  ]);

		if (!!$request['files']) {
			foreach ($request['files'] as $file) {
				$file = $this->fileUploader->upload($file, [
							'type' => 'WorkPermits',
							'id' => $request["id"]
						]);

				$this->storeWorkPermitFile($request["id"], $file['filename'],6);
			}
		} else {
			false;
		}

		if (!!$request['high_risk_files']) {
			foreach ($request['high_risk_files'] as $key => $file) {
				$catValue = $request['high_risk_files_cat'][$key] ?? null;

				$filef = $this->fileUploader->uploadDoc($file, [
							'type' => 'WorkPermits',
							'id' => $request["id"],
							'cat_document_id'=>$catValue
						]);

				$this->storeWorkPermitFile($request["id"], $filef['filename'],$catValue);
			}
		} else {
			false;
		}
		  //envio correo de edición
		  $this->sendEmail->updateWorkPermit($work_permit, 9, $request->current_environment);
		  
		$work_permit->load(['user.userSec', 'type', 'authorizedBy']);

		return response()->json($work_permit, JSON_UNESCAPED_UNICODE);
	}

	public function storeWorkPermitFile(int $work_permit_id, String $filename,int $catdocumentsid)
	{
		WorkPermitFile::create([
			'work_permit_id' => $work_permit_id,
			'file' => $filename,
			'cat_documents_workpermit_id' => $catdocumentsid
		]);
	}

	public function dt(Request $request) 
	{
		// Filtrar permisos según el environment_id directamente en la tabla work_permit
		try {
			$user = $this->userRepository->getUser();
			if (!$user || !isset($user->user[0]->id)) {
				return response()->json(['error' => 'User ID is not valid'], 400);
			}

			// array de permisos en $user->permissions
			$permissions = $user->permissions;

			// Encontrar el permiso con subsecId: 5 /permits
			$permission = collect($permissions)->firstWhere('subsecId', 5);
			// Obtener el loginCrud y hacer split
			$loginCrud = $permission['permisos']; // Suponiendo que 'permisos' es la clave que contiene el CRUD
			$permissionsArray = explode(',', $permission->loginCrud); // Esto convierte "C,R,U,D,S" en ["C", "R", "U", "D", "S"]

			if (in_array('S', $permissionsArray) && $request->high_risk==1) {
				$permits = WorkPermit::with(['user.userSec', 'type','type_highrisk', 'files','filesImg','authorizedBySecurity','brand','user' => function ($q) {
					return $q->with([
					'mails',
					'phones',
					'type',
					'permitBoss' => function ($query) {
					return $query->with(['permitType'])->where('deleted', false);
				},
			]);
		},
				'boss' => function ($query) use ($user) {
					$query->where('users_id', $user->user[0]->id)
						  ->where('deleted', false);
				}
			])
				->where('environment_id', $request->current_environment)
				->where('high_risk', $request->high_risk)
				
				->where(function ($query) use ($request) {
			
			  // Verificar si el valor de búsqueda es una fecha en formato d-m-Y
			  $formattedDate = null;
			  if (!empty($request->search) && preg_match('/^\d{2}-\d{2}-\d{4}$/', $request->search)) {
				  try {
					  // Convertir la fecha de búsqueda al formato Y-m-d
					  $formattedDate = Carbon::createFromFormat('d-m-Y', $request->search)->format('Y-m-d');
				  } catch (\Exception $e) {
					  // Si falla la conversión, dejar null
					  $formattedDate = null;
				  }
			  }
			
			//   // Buscar por id o fechas (start, end)
			  $query->where('id', 'like', '%' . $request->search . '%')
					->orWhereDate('start', '=', $formattedDate)
					->orWhereDate('end', '=', $formattedDate);
	   
		$query->orWhereHas('brand', function ($query) use ($request) {
				$query->where('description', 'like', '%' . $request->search . '%');
			});
		// Búsqueda en relación 'user' con concat de nombre y apepa
		$query->orWhereHas('user.userSec', function ($query) use ($request) {
			$query->whereRaw("concat(nombre, ' ', apepa, ' ',apema) like ?", ["%{$request->search}%"]);
		});
		// Búsqueda en relación 'user' con concat de usuario
		$query->orWhereHas('user.userSec', function ($query) use ($request) {
			$query->where('usuario', 'like', '%' . $request->search . '%');
		});
	
	})
	// ->orderBy('id', 'desc')
	// ->paginate(100);
	->get();
				$currentDate = Carbon::now();

				$permits = $permits->map(function ($permit) use ($currentDate) {
					if ($permit->authorized == 0 && $permit->created_at) {
						$createdDate = Carbon::parse($permit->created_at);
						if ($createdDate->diffInDays($currentDate) > 30) {
							$permit->authorized = 3; // Cambiar a "Vencido"
						}
					}
					return $permit;
				});
					// Ordenar los datos según `id`
					$permits = $permits->sortByDesc('id')->values();

					// Paginar manualmente
					$page = $request->input('page', 1);
					$perPage = 100; // Cambiar si necesitas otro tamaño
					$total = $permits->count();
					$pagedPermits = $permits->slice(($page - 1) * $perPage, $perPage)->values();

					// Crear un paginador manual
					$paginated = new LengthAwarePaginator(
						$pagedPermits,
						$total,
						$perPage,
						$page,
						['path' => $request->url(), 'query' => $request->query()]
					);
			}else{


		$permits = WorkPermit::with(['user.userSec', 'type','type_highrisk', 'files','filesImg','authorizedBySecurity','brand','user' => function ($q) {
								return $q->with([
								'mails',
								'phones',
								'type',
						]);
					},
					'boss' => function ($query) use ($user) {
						$query->where('users_id', $user->user[0]->id)
							  ->where('deleted', false);
					}
				])
				->whereHas('boss', function ($query) use ($user) {
					$query->where('users_id', $user->user[0]->id)->where('deleted', false);
				})
				->where('environment_id', $request->current_environment)
				->where('high_risk', $request->high_risk)
				
				->where(function ($query) use ($request) {
					    
					      // Verificar si el valor de búsqueda es una fecha en formato d-m-Y
						  $formattedDate = null;
						  if (!empty($request->search) && preg_match('/^\d{2}-\d{2}-\d{4}$/', $request->search)) {
							  try {
								  // Convertir la fecha de búsqueda al formato Y-m-d
								  $formattedDate = Carbon::createFromFormat('d-m-Y', $request->search)->format('Y-m-d');
							  } catch (\Exception $e) {
								  // Si falla la conversión, dejar null
								  $formattedDate = null;
							  }
						  }
						
						//   // Buscar por id o fechas (start, end)
						  $query->where('id', 'like', '%' . $request->search . '%')
								->orWhereDate('start', '=', $formattedDate)
								->orWhereDate('end', '=', $formattedDate);
				   
					$query->orWhereHas('brand', function ($query) use ($request) {
							$query->where('description', 'like', '%' . $request->search . '%');
						});
					// Búsqueda en relación 'user' con concat de nombre y apepa
					$query->orWhereHas('user.userSec', function ($query) use ($request) {
						$query->whereRaw("concat(nombre, ' ', apepa, ' ',apema) like ?", ["%{$request->search}%"]);
					});
					// Búsqueda en relación 'user' con concat de usuario
					$query->orWhereHas('user.userSec', function ($query) use ($request) {
						$query->where('usuario', 'like', '%' . $request->search . '%');
					});
				
				})
				// ->orderBy('id', 'desc')
				// ->paginate(100);
				->get();
				$currentDate = Carbon::now();

				$permits = $permits->map(function ($permit) use ($currentDate) {
					if ($permit->authorized == 0 && $permit->created_at) {
						$createdDate = Carbon::parse($permit->created_at);
						if ($createdDate->diffInDays($currentDate) > 30) {
							$permit->authorized = 3; // Cambiar a "Vencido"
						}
					}
					return $permit;
				});
					// Ordenar los datos según `id`
					$permits = $permits->sortByDesc('id')->values();

					// Paginar manualmente
					$page = $request->input('page', 1);
					$perPage = 200; // Cambiar si necesitas otro tamaño
					$total = $permits->count();
					$pagedPermits = $permits->slice(($page - 1) * $perPage, $perPage)->values();

					// Crear un paginador manual
					$paginated = new LengthAwarePaginator(
						$pagedPermits,
						$total,
						$perPage,
						$page,
						['path' => $request->url(), 'query' => $request->query()]
					);
			}
	
			return response()->json($paginated);
		} catch (\Exception $e) {
			Log::error('Error fetching permits: ' . $e->getMessage());
			return response()->json(['error' => 'Error fetching permits'], 500);
		}
	}

	public function fetchSupplierPermit()
	{
		$permits = WorkPermit::with(['user.userSec', 'type','type_highrisk', 'authorizedBy','files','filesImg'])
					->where('responsable_id', $this->userRepository->getUser()->user[0]->id)
					->where('deleted', 0)
					->orderBy('start', 'desc')
					->get() ->map(function ($permit) {
						 // Convertir created_at a un objeto Carbon si no lo es
						 $createdAt = Carbon::parse($permit->created_at);
						 // Verificar si la fecha de creación es mayor a 30 días de la fecha actual
						 if ($createdAt->diffInDays(now()) > 30 && $permit->authorized == 0) {
							 // Modificar el estado a "Vencido" (3)
							 $permit->authorized = 3;
						 }
						 return $permit;
					});
		return response()->json($permits);
	}

	public function fetchSecurityPermit(Request $request)
	{
		// $brands = $this->userRepository->getBrandsByEnvironment($current_environment);

		$permits = WorkPermit::with(['user.userSec', 'type', 'type_highrisk','authorizedBy','brand'])
					->where('authorized', true)
					->where('deleted', 0)
					->where('environment_id',  $request->current_environment)
					->where(function ($query) use ($request) {
						//incluyendo busqueda fechas
						$query->where('id', 'like', '%' . $request->search . '%');
						// 	  ->orWhere('end', 'like', '%' . $request->search . '%')
						$query->orWhereHas('brand', function ($query) use ($request) {
								$query->where('description', 'like', '%' . $request->search . '%');
							});
						// Búsqueda en relación 'user' con concat de nombre y apepa
						$query->orWhereHas('user.userSec', function ($query) use ($request) {
							$query->whereRaw("concat(nombre, ' ', apepa, ' ',apema) like ?", ["%{$request->search}%"]);
						});
						// Búsqueda en relación 'user' con concat de usuario
						$query->orWhereHas('user.userSec', function ($query) use ($request) {
							$query->where('usuario', 'like', '%' . $request->search . '%');
						});
					})
					->orderBy('start', 'desc')
					->paginate(100);

		return response()->json($permits);
	}

	public function authorizePermit(AuthorizePermitRequest $request)
	{
		$signature = $this->userRepository->getSignature($this->userRepository->getUser()->user[0]->id, $this->userRepository->getUser()->usuario);

		if (!!!$signature) {
			return response()->json([
				'message' => 'No tienes firma registrada.'
			], 401);
		} else {
			$work_permit = WorkPermit::where('id', $request->id)
								->firstOrFail();

			$work_permit->authorized = true;
			$work_permit->start = $request->start;
			$work_permit->end = $request->end;
			$work_permit->supervisor_notes = $request->notes;
			$work_permit->authorized_by_id = $this->userRepository->getUser()->user[0]->id;
			$work_permit->authorized_supervisor_date =  Carbon::now();
			$work_permit->save();

			$this->sendEmail->sendStatus($work_permit, 2, $request->environment, $request->start, $request->end);

			return response()->json([
				'work_permit' => $work_permit
			]);
		}
	}

	public function cancelPermit(Request $request)
	{
		$work_permit = WorkPermit::where('id', $request->id)
							->firstOrFail();

		$work_permit->authorized = 2;
		$work_permit->authorized_by_id = $this->userRepository->getUser()->user[0]->id;
		$work_permit->comments = $request->comment;
		$work_permit->save();

		$this->sendEmail->sendStatus($work_permit, 3, $request->environment);

		return response()->json([
			'work_permit' => $work_permit
		]);
	}

	public function ReasignPermit(Request $request)
	{
		  // Buscar el permiso de trabajo por ID
		  $work_permit = WorkPermit::where('id', $request->id)
		  ->firstOrFail();

		  // Actualizar los campos
		  $work_permit->cat_work_permit_type_id= $request['cat_work_permit_type_id']; // Asegurar que sea un entero
		  $work_permit->updated_at = Carbon::now();
		  $work_permit->save();

		  $work_permit->load(['user.userSec', 'type', 'authorizedBy']);

		$this->sendEmail->sendStatus($work_permit, 4, $request->environment);
		$this->sendEmail->newWorkPermit($work_permit, 1, $work_permit->environment_id);

		return response()->json([
			'work_permit' => $work_permit
		]);
	}

	public function showPdf(Request $request)
	{
		try {
			$data = WorkPermit::with(['environment','user' => function ($q) {
				$q->with(['phones', 'mail', 'brand_environment.environment']);
			}, 'type','type_highrisk', 'authorizedBy','authorizedBySecurity', 'files'])->find($request->id);
			if (!$data) {
				Log::error('No se encontró el permiso de trabajo con el ID proporcionado.');
				return response()->json(['error' => 'Permiso de trabajo no encontrado'], 404);
			}
	
			$is_saved = WorkPermitPdf::where('work_permit_id', $data->id)->where("deleted", 0)->first();
			
			if (!$is_saved) {
				return $this->saveFile($data);
			} else {
				return response()->json([
					'url' => Storage::disk('public')->url("pdf/work_permit/{$is_saved->file}")
				]);
			}
		} catch (\Exception $e) {
			Log::error('Error en showPdf: ' . $e->getMessage());
			return response()->json(['error' => 'Error al generar el archivo'], 500);
		}
	}

	public function saveFile($data)
	{
		try {
			$owner_signature = $this->userRepository->getSignaturePDF($data->user->id, $data->user->userSec->usuario);
			$authorized_by_signature = $this->userRepository->getSignaturePDF($data->authorizedBy->id, $data->authorizedBy->userSec->usuario);
			//firma de security
			$authorized_by_security_signature = $data->authorizedBySecurity 
			? $this->userRepository->getSignaturePDF($data->authorizedBySecurity->id, $data->authorizedBySecurity->userSec->usuario)
			: null;
					
			$owner_url = isset($owner_signature['url']) ? parse_url($owner_signature['url']) : null;
			$authorized_by_url = isset($authorized_by_signature['url']) ? parse_url($authorized_by_signature['url']) : null;
			$authorized_by_security_url = isset($authorized_by_security_signature['url']) 
			? parse_url($authorized_by_security_signature['url']) 
			: null;

			$files = $data->files;

			$pdf = PDF::loadView('pdf.show', [
				'data' => $data,
				'id' => $data->qr_code,
				'logo' => $data->environment->logo,
				'owner_signature' => $owner_url['path'] ?? null,
    			'authorized_by_signature' => $authorized_by_url['path'] ?? null,
    			'authorized_by_security_signature' => $authorized_by_security_url['path'] ?? null,
				'date' => Carbon::now()->translatedFormat('j M Y'),
				'files' => $files
			], [], [
				'format' => 'A4',
				'orientation' => 'L'
			]);
			
			$filename = $data->id . '-' . time() . '_' . date('Y-m-d') . '.pdf';
			$pdfPath = "pdf/work_permit/{$filename}";
	
			// Guardar el archivo PDF
			Storage::disk('public')->put($pdfPath, $pdf->output());
	
			// Verificar si el archivo se guardó correctamente
			if (!Storage::disk('public')->exists($pdfPath)) {
				Log::error('Error al guardar el archivo PDF: el archivo no se creó en ' . $pdfPath);
				return response()->json(['error' => 'Error al guardar el archivo'], 500);
			}
	
			// Almacenar la información del archivo en la base de datos
			$file = $this->storeFile($data->id, $filename);
	
			if (!$file) {
				Log::error('Error al guardar la información del archivo en la base de datos.');
				return response()->json(['error' => 'Error al guardar la información del archivo'], 500);
			}
	
			return response()->json([
				'url' => Storage::disk('public')->url($pdfPath)
			]);
	
		} catch (\Exception $e) {
			Log::error('Error en saveFile: ' . $e->getMessage());
			return response()->json(['error' => 'Error al generar el archivo'], 500);
		}
	}

	public function testPDF()
	{
		$data = WorkPermit::with(['user' => function ($q) {
			$q->with(['phones', 'mail', 'brand_environment.environment']);
		}, 'type', 'authorizedBy', 'files'])->find(89);

		$owner_signature = $this->userRepository->getSignaturePDF($data->user->id, $data->user->userSec->usuario);

		$authorized_by_signature = $this->userRepository->getSignaturePDF($data->authorizedBy->id, $data->authorizedBy->userSec->usuario);

		$authorized_by_url = parse_url($authorized_by_signature['url']);

		$files = $data->files;

		$owner_url = parse_url($owner_signature['url']);
		$pdf = PDF::loadView('pdf.show',  [
			'data' => $data,
			'id' => $data->qr_code,
			'logo' => $data->user->brand_environment->environment->logo,
			'owner_signature' => $owner_url['path'],
			'authorized_by_signature' => $authorized_by_url['path'],
			'date' => Carbon::now()->translatedFormat('j M Y'),
			'files' => $files
			], [], [
				'format' => 'A4',
				'orientation' => 'L'
			]);

		return $pdf->stream();


		$filename = $data->id.'-'.time().'_'.date('Y-m-d').'.pdf';

		$file = $this->storeFile($data->id, $filename);

		$pdf->save(public_path("/storage/pdf/work_permit/{$data->user->userSec->usuario}/{$filename}"));

		return response()->json([
			'url' => Storage::disk('public')->url("work_permit/{{$data->user->userSec->usuario}/{$filename}")
		]);
	}

	public function storeFile(int $id, String $filename)
	{
		try {
		$file = WorkPermitPdf::create([
			'work_permit_id' => $id,
			'file' => $filename
		]);

		return $file;
		} catch (\Exception $e) {
			Log::error('Error al guardar el registro en la base de datos: ' . $e->getMessage());
			return false;
		}
	}

	public function addComments(Request $request)
	{
		$user = User::where('SEG_USUARIOS_usuarioId', Auth::user()->usuarioId)->first();

		$work_permit_comment = WorkPermitComments::create([
			'message' => $request['message'],
			'user_id' => $user->id,
			'work_permit_id' => $request["id"],
			'created_at' => Carbon::now(),
		]);		
		
		$this->sendEmail->newComments($work_permit_comment, 8);

		return response()->json($work_permit_comment, JSON_UNESCAPED_UNICODE);
	}
	public function getComments(Request $request)
	{
		$work_permit_comments = WorkPermitComments::with('user')->where("work_permit_id",$request["id"])->orderBy("created_at","asc")->get();

		return response()->json($work_permit_comments, 200);
	}

	protected function DeleteFilesWorkPermit(Request $request){
        try {
            $files =  WorkPermitFile::where('file',$request["name"])->delete();
            $name=$request["name"];
            $id=$request["id"];
            unlink("../Storage/app/public/WorkPermits/$id/$name");
            // $supplier= DmiabaDocumentsSupplier::where("dmiaba_supplier_registration_id",$request["supplier_id"])->get();

            return response()->json(['success'=>'Se ha eliminado archivo correctamente.'],200);
            
        } catch (\Throwable $th) {
            throw $th;
        }

    }
	public function getCatHighRisk(Request $request)
	{
		$CatHighRiskJobs = CatHighRiskJobs::where("deleted_at",null)->get();

		return response()->json($CatHighRiskJobs, 200);
	}

	public function downloadZip(Request $request)
    {
        // Recibir el parámetro 'work_permit_id'
        $workPermitId = $request->input('work_permit_id');

        // Obtener los documentos asociados a este work_permit_id desde la base de datos
        $documents = WorkPermitFile::where('work_permit_id', $workPermitId)->where("cat_documents_workpermit_id","<>",6)->get();
        
        if ($documents->isEmpty()) {
            return response()->json(['error' => 'No se encontraron archivos para este Work Permit'], 404);
        }
        // Nombre del archivo ZIP
        $zipFileName = 'archivos_' . $workPermitId .'.zip';
        $zipPath = storage_path($zipFileName);

        // Crear un archivo ZIP
        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($documents as $document) {
                $filePath = storage_path('app/public/WorkPermits/'.$workPermitId.'/' . $document->file);

                // Verificar si el archivo existe antes de agregarlo al ZIP
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath)); // Añadir archivo al ZIP
                }
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }

        // Retornar el archivo ZIP como respuesta
        return response()->download($zipPath)->deleteFileAfterSend(true); // Eliminar el ZIP después de enviarlo
    }
	public function active_highrisk(Request $request)
	{
		// Buscar el permiso de trabajo por ID

		 $work_permit = WorkPermit::with([
			'user.brand',
			'environment',
			'type',
			'brand',
		])->findOrFail($request["id"]); // Cambia $id por el ID del WorkPermit que deseas buscar

		 // Actualizar los campos
		 $work_permit->update([
			"high_risk" => $request["high_risk"]
		 ]);
		 if($request["high_risk"]===1){
			$usersMails = $this->userRepository->getUserPermitHighRisk();
	$a=	 $this->sendEmail->newWorkHighRiskPermit($work_permit, 6,  $work_permit->environment_id, $usersMails);
	return $a;

		 }

		return response()->json("Se envio correo", 200);
	}

	public function authorizeSecurityPermit(Request $request)
	{
		$user = User::where('SEG_USUARIOS_usuarioId', Auth::user()->usuarioId)->first();
		 // Buscar el permiso de trabajo por ID
		 $work_permit = WorkPermit::findOrFail($request["id"]);

		 // Actualizar los campos
		 $work_permit->update([
			"authorized_security_id" => $user->id,
			"authorized_security_date" => Carbon::now(),
			"security_notes" => $request["note_security"],
		 ]);

		$this->sendEmail->lastsignWorkPermit($work_permit, 7, $request->current_environment);

		return response()->json($work_permit, 200);
	}

	public function storeFileDoc(Request $request){
			// Obtén todos los registros previos que coincidan con el 'cat_documents_work_permit_id' proporcionado
			$previousFiles = WorkPermitFileDocuments::where('cat_documents_work_permit_id', $request["cat_documents_work_permit_id"])->get();

			// Itera sobre cada archivo encontrado y elimina tanto el archivo físico como el registro en la base de datos
			foreach ($previousFiles as $file) {
				// Construye la ruta completa del archivo en el almacenamiento
				$filePath = 'WorkPermits/Documents/' . $file->file;

				// Verifica si el archivo físico existe y elimínalo
				if (Storage::exists($filePath)) {
					Storage::delete($filePath);
				}

				// Elimina el registro en la base de datos
				$file->delete();
			}

		 // Elimina todos los registros previos con el mismo 'cat_documents_work_permit_id'
		 WorkPermitFileDocuments::where('cat_documents_work_permit_id', $request["cat_documents_work_permit_id"])->delete();

		$filef = $this->fileUploader->upload($request->file, [
			'type' => 'WorkPermits',
			'id' => 'Documents',
		]);
		WorkPermitFileDocuments::create([
			'file' => $filef["filename"],
			'cat_documents_work_permit_id' => $request["cat_documents_work_permit_id"]
		]);
		return response()->json("Archivo subido correctamente", 200);
	}

	public function getFileUrl(Request $request)
	{
    // Buscar el archivo en la base de datos según el `cat_documents_work_permit_id`
	$document = WorkPermitFileDocuments::where('cat_documents_work_permit_id', $request->cat_documents_work_permit_id)
	->whereNull('deleted_at') // Filtra para que no esté eliminado
	->first();

    // Verificar si el documento existe
    if (!$document) {
        return response()->json(['error' => 'Documento no encontrado'], 404);
    }

    // Generar la URL del archivo
    $filePath = storage_path("app/public/WorkPermits/Documents/" . $document->file);

    if (!file_exists($filePath)) {
        return response()->json(['error' => 'Archivo físico no encontrado'], 404);
    }

    $url = asset("storage/WorkPermits/Documents/" . $document->file);

    return response()->json(['url' => $url], 200);
}

}
