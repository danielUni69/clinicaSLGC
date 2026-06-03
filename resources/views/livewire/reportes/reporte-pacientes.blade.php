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
                <select wire:model="tipoReporte" class="w-full border-gray-300 rounded-lg">
                    <option value="pacientes">Pacientes Registrados</option>
                    <option value="servicios">Órdenes de Servicio</option>
                    <option value="ingresos">Ingresos Económicos</option>
                    <option value="analisis">Top Análisis (Más solicitados)</option>
                    <option value="medicos">Ranking de Médicos Derivadores</option>
                    <option value="general">Resumen General</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha Inicio
                </label>
                <input type="date" wire:model="fechaInicio" max="{{ $fechaFin }}"
                    class="w-full border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha Fin
                </label>
                <input type="date" wire:model="fechaFin" min="{{ $fechaInicio }}" max="{{ now()->format('Y-m-d') }}"
                    class="w-full border-gray-300 rounded-lg">
            </div>

            <div class="flex items-end">
                <button wire:click="generar"
                    class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2.5 rounded-lg font-medium w-full shadow-sm transition-colors">
                    <i class="fas fa-search mr-2"></i> Generar Reporte
                </button>
            </div>

        </div>
    </div>

    @if (empty($resultados))
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
    @if (!empty($resultados))
        <div
            class="mt-8 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-[fadeIn_0.3s_ease-out]">

            <div class="px-6 py-4 border-b flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="font-bold text-gray-800 uppercase tracking-wider text-sm">
                        <i class="fas fa-file-alt text-gray-400 mr-2"></i> Reporte de {{ ucfirst($tipoReporte) }}
                    </h2>
                    @if ($tipoReporte != 'general')
                        <p class="text-xs font-bold text-gray-500 mt-1">
                            Resultados encontrados: {{ count($resultados) }}
                        </p>
                    @endif
                </div>

                @if (!empty($resultados))
                    <button wire:click="exportarPdf"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                    </button>
                @endif
            </div>

            <div class="overflow-x-auto">

                {{-- PACIENTES --}}
                @if ($tipoReporte == 'pacientes')
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider text-xs">
                            <tr>
                                <th class="px-4 py-3 text-left">CI</th>
                                <th class="px-4 py-3 text-left">Nombre</th>
                                <th class="px-4 py-3 text-left">Sexo</th>
                                <th class="px-4 py-3 text-left">Teléfono</th>
                                <th class="px-4 py-3 text-left">Registro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($resultados as $paciente)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $paciente->ci }}</td>
                                    <td class="px-4 py-3">{{ $paciente->nombre_completo }}</td>
                                    <td class="px-4 py-3">{{ $paciente->sexo }}</td>
                                    <td class="px-4 py-3">{{ $paciente->telefono ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $paciente->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- SERVICIOS --}}
                @if ($tipoReporte == 'servicios')
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider text-xs">
                            <tr>
                                <th class="px-4 py-3 text-left">Código</th>
                                <th class="px-4 py-3 text-left">Paciente</th>
                                <th class="px-4 py-3 text-left">Estado Pago</th>
                                <th class="px-4 py-3 text-left">Estado Muestra</th>
                                <th class="px-4 py-3 text-left">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($resultados as $servicio)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-bold text-gray-900">{{ $servicio->codigo_unico }}</td>
                                    <td class="px-4 py-3">{{ $servicio->paciente?->nombre_completo }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 text-[10px] uppercase font-bold rounded-md {{ $servicio->estado_pago === 'pagado' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ $servicio->estado_pago }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 text-[10px] uppercase font-bold rounded-md {{ $servicio->estado_muestra === 'completada' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $servicio->estado_muestra }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $servicio->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- INGRESOS --}}
                @if ($tipoReporte == 'ingresos')
                    <div class="p-6">
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5 shadow-sm">
                            <div class="text-green-700 text-sm font-semibold uppercase tracking-wider">Total Ingresos
                                Generados</div>
                            <div class="text-3xl font-black text-green-800 mt-1">Bs
                                {{ number_format(collect($resultados)->sum('total'), 2) }}</div>
                        </div>

                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider text-xs">
                                <tr>
                                    <th class="px-4 py-3 text-left">N° Recibo</th>
                                    <th class="px-4 py-3 text-left">Subtotal</th>
                                    <th class="px-4 py-3 text-left">Descuento</th>
                                    <th class="px-4 py-3 text-left">Total</th>
                                    <th class="px-4 py-3 text-left">Medio Pago</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($resultados as $recibo)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-bold text-gray-900">{{ $recibo->numero_correlativo }}
                                        </td>
                                        <td class="px-4 py-3">Bs {{ number_format($recibo->subtotal, 2) }}</td>
                                        <td class="px-4 py-3 text-red-500 font-medium">Bs
                                            {{ number_format($recibo->descuento, 2) }}</td>
                                        <td class="px-4 py-3 font-black text-green-600">Bs
                                            {{ number_format($recibo->total, 2) }}</td>
                                        <td class="px-4 py-3"><span
                                                class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-bold uppercase">{{ $recibo->medio_pago }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- NUEVO: ANÁLISIS MÁS SOLICITADOS --}}
                @if ($tipoReporte == 'analisis')
                    <table class="w-full text-sm">
                        <thead
                            class="bg-blue-50 text-blue-800 uppercase tracking-wider text-xs font-bold border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-center w-16">Rank</th>
                                <th class="px-4 py-3 text-left">Examen Clínico</th>
                                <th class="px-4 py-3 text-left">Categoría</th>
                                <th class="px-4 py-3 text-center">Cant. Solicitudes</th>
                                <th class="px-4 py-3 text-right">Ingresos Generados</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($resultados as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-center font-black text-gray-400">#{{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3 font-bold text-gray-800"><i
                                            class="fas fa-microscope text-blue-400 mr-2 opacity-50"></i>{{ $item['nombre'] }}
                                    </td>
                                    <td class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">
                                        {{ $item['categoria'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-black">{{ $item['solicitudes'] }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-green-600 font-black">Bs
                                        {{ number_format($item['ingresos'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- NUEVO: MËDICOS DERIVADORES --}}
                @if ($tipoReporte == 'medicos')
                    <table class="w-full text-sm">
                        <thead
                            class="bg-indigo-50 text-indigo-800 uppercase tracking-wider text-xs font-bold border-b border-indigo-100">
                            <tr>
                                <th class="px-4 py-3 text-center w-16">Rank</th>
                                <th class="px-4 py-3 text-left">Médico Solicitante</th>
                                <th class="px-4 py-3 text-left">Especialidad</th>
                                <th class="px-4 py-3 text-center">Pacientes Derivados</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($resultados as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-center font-black text-gray-400">#{{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3 font-bold text-gray-800"><i
                                            class="fas fa-user-md text-indigo-400 mr-2 opacity-50"></i>{{ $item['nombre'] }}
                                    </td>
                                    <td class="px-4 py-3 text-xs font-bold text-gray-500 uppercase">
                                        {{ $item['especialidad'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full font-black">{{ $item['derivaciones'] }}
                                            pacientes</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- GENERAL --}}
                @if ($tipoReporte == 'general')
                    <div class="p-6 space-y-6 bg-gray-50/30">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 shadow-sm">
                                <div class="text-blue-600 text-sm font-semibold uppercase tracking-wider"><i
                                        class="fas fa-users mr-1"></i> Pacientes Atendidos</div>
                                <div class="text-4xl font-black text-blue-800 mt-2">{{ $resultados['pacientes'] }}
                                </div>
                            </div>

                            <div class="bg-purple-50 border border-purple-200 rounded-xl p-5 shadow-sm">
                                <div class="text-purple-600 text-sm font-semibold uppercase tracking-wider"><i
                                        class="fas fa-file-medical mr-1"></i> Servicios Creados</div>
                                <div class="text-4xl font-black text-purple-800 mt-2">{{ $resultados['servicios'] }}
                                </div>
                            </div>

                            <div class="bg-green-50 border border-green-200 rounded-xl p-5 shadow-sm">
                                <div class="text-green-600 text-sm font-semibold uppercase tracking-wider"><i
                                        class="fas fa-money-bill-wave mr-1"></i> Ingresos Totales</div>
                                <div class="text-4xl font-black text-green-800 mt-2 truncate">Bs
                                    {{ number_format($resultados['ingresos'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
