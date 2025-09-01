<?php

namespace App\Http\Controllers;

use App\Models\SolicitudAdopcion;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdopcionController extends Controller
{
    public function showForm($animalId)
    {
        $animal = Animal::with('fundacion')->findOrFail($animalId);
        
        // Verificar si el usuario ya tiene una solicitud pendiente para este animal
        if (Auth::check()) {
            $solicitudExistente = SolicitudAdopcion::where('usuario_id', Auth::id())
                ->where('animal_id', $animalId)
                ->whereIn('estado', ['pendiente', 'en_revision'])
                ->first();
                
            if ($solicitudExistente) {
                return redirect()->route('publico.animal', $animalId)
                    ->with('error', 'Ya tienes una solicitud pendiente para este animal.');
            }
        }
        
        return view('publico.solicitud-adopcion', compact('animal'));
    }

    public function store(Request $request)
    {
        // Validar los campos del formulario
        $validated = $request->validate([
            'animal_id' => 'required|exists:animales,id',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'identificacion' => 'required|string|max:20',
            'edad' => 'required|integer|min:18|max:100',
            'ocupacion' => 'required|string|max:100',
            'direccion_solicitante' => 'required|string|max:500',
            'barrio' => 'required|string|max:100',
            'referencia' => 'required|string|max:20',
            'tipo_vivienda' => 'required|in:casa,apartamento,duplex,otro',
            'tiene_patio' => 'required|in:si,no',
            'otros_mascotas' => 'required|in:si,no',
            'experiencia_mascotas' => 'required|in:nada,poca,moderada,mucha',
            'motivo_adopcion' => 'required|string|max:1000',
            'tiempo_disponible' => 'required|in:poco,moderado,mucho',
            'bienestar_mascota' => 'required|string|max:2000',
            'conocimiento_cuidados' => 'required|string|max:2000',
            'compromiso_esterilizacion' => 'required|in:si,no,consultar',
            'preguntas_seguridad' => 'required|array',
            'preguntas_seguridad.experiencia' => 'required|string|max:1000',
            'preguntas_seguridad.emergencias' => 'required|string|max:1000',
            'preguntas_seguridad.perdida' => 'required|string|max:1000',
            'mensaje' => 'nullable|string|max:1000',
        ]);

        $animal = Animal::with('fundacion')->findOrFail($request->animal_id);
        $perfilFundacion = \App\Models\PerfilFundacion::where('usuario_id', $animal->fundacion_id)->first();

        // Verificar si el animal está disponible
        if ($animal->estado !== 'disponible') {
            return back()->withErrors(['animal_id' => 'Este animal ya no está disponible para adopción.']);
        }

        // Verificar si el usuario ya tiene una solicitud pendiente (solo si está autenticado)
        if (Auth::check()) {
            $solicitudExistente = SolicitudAdopcion::where('usuario_id', Auth::id())
                ->where('animal_id', $request->animal_id)
                ->whereIn('estado', ['pendiente', 'en_revision'])
                ->first();
                
            if ($solicitudExistente) {
                return back()->withErrors(['animal_id' => 'Ya tienes una solicitud pendiente para este animal.']);
            }
        }

        // Crear la solicitud con todos los campos
        $solicitud = SolicitudAdopcion::create([
            'usuario_id' => Auth::id(), // Puede ser null si el usuario no está autenticado
            'animal_id' => $request->animal_id,
            'fundacion_id' => $perfilFundacion ? $perfilFundacion->id : null,
            'nombre_solicitante' => $request->nombre,
            'email_solicitante' => $request->email,
            'telefono_solicitante' => $request->telefono,
            'identificacion' => $request->identificacion,
            'edad_solicitante' => $request->edad,
            'ocupacion' => $request->ocupacion,
            'direccion_solicitante' => $request->direccion_solicitante,
            'barrio' => $request->barrio,
            'referencia' => $request->referencia,
            'tipo_vivienda' => $request->tipo_vivienda,
            'tiene_patio' => $request->tiene_patio,
            'otros_mascotas' => $request->otros_mascotas,
            'experiencia_mascotas' => $request->experiencia_mascotas,
            'motivo_adopcion' => $request->motivo_adopcion,
            'tiempo_disponible' => $request->tiempo_disponible,
            'bienestar_mascota' => $request->bienestar_mascota,
            'conocimiento_cuidados' => $request->conocimiento_cuidados,
            'compromiso_esterilizacion' => $request->compromiso_esterilizacion,
            'preguntas_seguridad' => json_encode($request->preguntas_seguridad),
            'mensaje' => $request->mensaje,
            'estado' => 'pendiente',
        ]);

        // Aquí podrías enviar notificaciones por email
        // $this->enviarNotificaciones($solicitud);

        return redirect()->route('publico.animal', $request->animal_id)
            ->with('success', '¡Tu solicitud de adopción ha sido enviada exitosamente! Te contactaremos pronto.');
    }

    public function gestionar()
    {
        $solicitudes = SolicitudAdopcion::with(['usuario', 'animal', 'fundacion'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    public function cambiarEstado($id, $estado)
    {
        $solicitud = SolicitudAdopcion::findOrFail($id);
        $solicitud->estado = $estado;
        $solicitud->save();

        // Si se aprueba la solicitud, cambiar el estado del animal
        if ($estado === 'aprobada') {
            $solicitud->animal->update(['estado' => 'adoptado']);
        }

        return back()->with('success', 'Estado de la solicitud actualizado correctamente.');
    }

    public function solicitudesFundacion()
    {
        $fundacion = Auth::user()->perfilFundacion;
        
        if (!$fundacion) {
            return redirect()->back()->with('error', 'No tienes un perfil de fundación asociado.');
        }

        $solicitudes = SolicitudAdopcion::with(['usuario', 'animal'])
            ->where('fundacion_id', $fundacion->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('fundacion.solicitudes.index', compact('solicitudes'));
    }

    public function cambiarEstadoFundacion(Request $request, $id, $estado)
    {
        $solicitud = \App\Models\SolicitudAdopcion::findOrFail($id);
        $solicitud->estado = $estado;
        if ($estado === 'rechazado') {
            $solicitud->comentario = $request->input('comentario');
        }
        $solicitud->save();
        return redirect()->route('fundacion.solicitudes')->with('success', 'Estado de la solicitud actualizado');
    }

    private function enviarNotificaciones($solicitud)
    {
        // Enviar email a la fundación
        if ($solicitud->fundacion && $solicitud->fundacion->email) {
            // Mail::to($solicitud->fundacion->email)->send(new SolicitudAdopcionFundacion($solicitud));
        }

        // Enviar email al solicitante
        // Mail::to($solicitud->email_solicitante)->send(new SolicitudAdopcionConfirmacion($solicitud));

        // Enviar email al admin
        // Mail::to(config('mail.admin_email'))->send(new SolicitudAdopcionAdmin($solicitud));
    }
}
