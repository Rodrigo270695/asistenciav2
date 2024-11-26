@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
        <h1 class="text-4xl font-bold text-red-600 mb-4">Sin Conexión a Internet</h1>
        <p class="text-lg text-gray-700 mb-6">Parece que no estás conectado a ninguna red. Por favor, verifica tu conexión a Internet.</p>
        <button
            onclick="location.reload()"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300"
        >
            Reintentar
        </button>
    </div>
</div>
@endsection
