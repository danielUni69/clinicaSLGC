<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">


{{-- Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
        <i class="fas fa-chart-bar text-cyan-600"></i>
        Centro de Reportes
    </h1>

    <p class="text-sm text-gray-500 mt-1">
        Genere reportes del sistema filtrando por rango de fechas.
    </p>
</div>
@if (session()->has('warning'))
    <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
        {{ session('warning') }}
    </div>
@endif
{{-- Filtros --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tipo de Reporte
            </label>

            <select
                wire:model="tipoReporte"
                class="w-full border-gray-300 rounded-lg">

                <option value="pacientes">Pacientes</option>
                <option value="servicios">Servicios</option>
                <option value="ingresos">Ingresos</option>
                <option value="general">General</option>

            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Fecha Inicio
            </label>

            <input
                type="date"
                wire:model="fechaInicio"
                max="{{ $fechaFin }}"
                class="w-full border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Fecha Fin
            </label>

                <input
                    type="date"
                    wire:model="fechaFin"
                    min="{{ $fechaInicio }}"
                    max="{{ now()->format('Y-m-d') }}"
                    class="w-full border-gray-300 rounded-lg">
        </div>

        <div class="flex items-end">
            <button
                wire:click="generar"
                class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2.5 rounded-lg font-medium w-full">

                <i class="fas fa-search mr-2"></i>
                Generar Reporte
            </button>
        </div>

    </div>

</div>
@if(empty($resultados))

<div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
    <div class="flex items-center gap-2">
        <i class="fas fa-circle-info text-yellow-600"></i>


    <span class="text-yellow-800 font-medium">
        Seleccione un tipo de reporte y presione "Generar Reporte".
    </span>
</div>


</div>

@endif


{{-- Resultados --}}
@if(!empty($resultados))

<div class="mt-8 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">


<div class="px-6 py-4 border-b flex items-center justify-between">

    <div>
        <h2 class="font-bold text-gray-800">
            Reporte de {{ ucfirst($tipoReporte) }}
        </h2>

        @if($tipoReporte != 'general')
            <p class="text-sm text-gray-500">
                Resultados encontrados: {{ count($resultados) }}
            </p>
        @endif
    </div>

    @if(!empty($resultados))
            <button
                wire:click="exportarPdf"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">

                <i class="fas fa-file-pdf mr-2"></i>
                Exportar PDF
            </button>
    @endif


</div>

{{-- PACIENTES --}}
@if($tipoReporte == 'pacientes')

    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left">CI</th>
                <th class="px-4 py-3 text-left">Nombre</th>
                <th class="px-4 py-3 text-left">Sexo</th>
                <th class="px-4 py-3 text-left">Teléfono</th>
                <th class="px-4 py-3 text-left">Registro</th>
            </tr>
        </thead>

        <tbody>
            @foreach($resultados as $paciente)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $paciente->ci }}</td>
                    <td class="px-4 py-3">{{ $paciente->nombre_completo }}</td>
                    <td class="px-4 py-3">{{ $paciente->sexo }}</td>
                    <td class="px-4 py-3">{{ $paciente->telefono ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $paciente->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif

{{-- SERVICIOS --}}
@if($tipoReporte == 'servicios')

    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left">Código</th>
                <th class="px-4 py-3 text-left">Paciente</th>
                <th class="px-4 py-3 text-left">Estado Pago</th>
                <th class="px-4 py-3 text-left">Estado Muestra</th>
                <th class="px-4 py-3 text-left">Fecha</th>
            </tr>
        </thead>

        <tbody>
            @foreach($resultados as $servicio)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $servicio->codigo_unico }}</td>
                    <td class="px-4 py-3">{{ $servicio->paciente?->nombre_completo }}</td>
                    <td class="px-4 py-3">{{ $servicio->estado_pago }}</td>
                    <td class="px-4 py-3">{{ $servicio->estado_muestra }}</td>
                    <td class="px-4 py-3">{{ $servicio->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif

{{-- INGRESOS --}}
{{-- INGRESOS --}}
@if($tipoReporte == 'ingresos')


<div class="p-6">

    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5">

        <div class="text-green-700 text-sm font-semibold">
            Total Ingresos Generados
        </div>

        <div class="text-3xl font-bold text-green-800 mt-2">
            Bs {{ number_format(collect($resultados)->sum('total'), 2) }}
        </div>

    </div>

    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left">N° Recibo</th>
                <th class="px-4 py-3 text-left">Subtotal</th>
                <th class="px-4 py-3 text-left">Descuento</th>
                <th class="px-4 py-3 text-left">Total</th>
                <th class="px-4 py-3 text-left">Pago</th>
            </tr>
        </thead>

        <tbody>
            @foreach($resultados as $recibo)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $recibo->numero_correlativo }}</td>
                    <td class="px-4 py-3">Bs {{ number_format($recibo->subtotal,2) }}</td>
                    <td class="px-4 py-3">Bs {{ number_format($recibo->descuento,2) }}</td>
                    <td class="px-4 py-3 font-bold text-green-600">
                        Bs {{ number_format($recibo->total,2) }}
                    </td>
                    <td class="px-4 py-3">{{ $recibo->medio_pago }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>


@endif


{{-- GENERAL --}}
@if($tipoReporte == 'general')

    <div class="p-6 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                <div class="text-blue-600 text-sm font-semibold">
                    Pacientes Registrados
                </div>

                <div class="text-3xl font-bold text-blue-800 mt-2">
                    {{ $resultados['pacientes'] }}
                </div>
            </div>

            <div class="bg-purple-50 border border-purple-200 rounded-xl p-5">
                <div class="text-purple-600 text-sm font-semibold">
                    Servicios Realizados
                </div>

                <div class="text-3xl font-bold text-purple-800 mt-2">
                    {{ $resultados['servicios'] }}
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-xl p-5">
                <div class="text-green-600 text-sm font-semibold">
                    Ingresos Generados
                </div>

                <div class="text-3xl font-bold text-green-800 mt-2">
                    Bs {{ number_format($resultados['ingresos'], 2) }}
                </div>
            </div>

        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
            <h3 class="font-bold text-gray-800 mb-2">
                Resumen General del Sistema
            </h3>

            <p class="text-gray-600">
                Este reporte resume la actividad registrada dentro del rango de fechas seleccionado.
                Incluye pacientes registrados, servicios realizados e ingresos generados.
            </p>
        </div>

    </div>

@endif


</div>

@endif



</div>
