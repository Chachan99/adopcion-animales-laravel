@extends('layouts.admin')

@section('title', 'Gestión de Noticias')

@section('contenido')
<div class="bg-white shadow-lg rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Gestión de Noticias</h2>
        <a href="{{ route('admin.noticias.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
            <i class="fas fa-plus mr-2"></i> Nueva Noticia
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p class="font-bold">¡Éxito!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 text-left text-gray-700 font-semibold uppercase text-sm">Título</th>
                    <th class="py-3 px-4 text-left text-gray-700 font-semibold uppercase text-sm">Autor</th>
                    <th class="py-3 px-4 text-left text-gray-700 font-semibold uppercase text-sm">Estado</th>
                    <th class="py-3 px-4 text-left text-gray-700 font-semibold uppercase text-sm">Publicación</th>
                    <th class="py-3 px-4 text-right text-gray-700 font-semibold uppercase text-sm">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($noticias as $noticia)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-4">
                        <div class="flex items-center">
                            @if($noticia->imagen)
                            <div class="flex-shrink-0 h-10 w-10 mr-3">
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/noticias/' . $noticia->imagen) }}" alt="{{ $noticia->titulo }}">
                            </div>
                            @endif
                            <div>
                                <p class="text-gray-900 font-medium">{{ $noticia->titulo }}</p>
                                @if($noticia->destacada)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Destacada
                                </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-gray-600">{{ $noticia->autor ?? 'Sistema' }}</td>
                    <td class="py-4 px-4">
                        @php
                            $estadoClases = [
                                'borrador' => 'bg-gray-100 text-gray-800',
                                'publicada' => 'bg-green-100 text-green-800',
                                'archivada' => 'bg-red-100 text-red-800',
                            ][$noticia->estado];
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $estadoClases }}">
                            {{ ucfirst($noticia->estado) }}
                        </span>
                    </td>
                    <td class="py-4 px-4 text-gray-600">
                        {{ $noticia->publicado_en ? $noticia->publicado_en->format('d/m/Y H:i') : 'No publicada' }}
                    </td>
                    <td class="py-4 px-4 text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('noticias.show', $noticia) }}" target="_blank" class="text-blue-600 hover:text-blue-900" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.noticias.edit', $noticia) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.noticias.destroy', $noticia) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta noticia?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">
                        <i class="fas fa-newspaper text-4xl mb-2 block text-gray-300"></i>
                        <p>No hay noticias registradas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($noticias->hasPages())
    <div class="mt-6">
        {{ $noticias->links() }}
    </div>
    @endif
</div>
@endsection
