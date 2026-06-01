<div>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-md font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-list text-blue-600"></i>
            Detalle del historial
        </h3>
    </div>

    @php
        $serviciosCount = $servicios instanceof \Illuminate\Support\Collection ? $servicios->count() : 0;
    @endphp

    @if($serviciosCount === 0)
        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm text-gray-600">
            Este paciente no tiene historial cargado todavía.
        </div>
    @else
        <div class="space-y-4">
            @foreach($servicios as $servicio)
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-receipt"></i>
                                Servicio ID: <span class="font-semibold text-gray-700">{{ $servicio->id }}</span>
                            </div>
                            <div class="mt-1 text-sm">
                                Estado de muestra: <span class="font-semibold">{{ $servicio->estado_muestra }}</span>
                            </div>
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            <div>
                                Código: <span class="font-semibold text-gray-700">{{ $servicio->codigo_unico }}</span>
                            </div>
                            <div class="mt-1">
                                Pago: <span class="font-semibold">{{ $servicio->estado_pago }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 space-y-4">
                        {{-- Tipos de análisis incluidos en el servicio --}}
                        <div>
                            <div class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-vial-circle-check text-blue-600"></i>
                                Análisis solicitados
                            </div>

                            @if($servicio->tiposAnalisis && $servicio->tiposAnalisis->count())
                                <div class="flex flex-wrap gap-2">
                                    @foreach($servicio->tiposAnalisis as $tipo)
                                        <span class="px-3 py-1 rounded-full text-xs bg-blue-50 border border-blue-100 text-blue-700">
                                            {{ $tipo->nombre }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500">Sin análisis asociados.</div>
                            @endif
                        </div>

                        {{-- Resultados (si existen) --}}
                        <div>
                            <div class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-chart-line text-green-600"></i>
                                Resultados registrados
                            </div>

                            @if($servicio->resultados && $servicio->resultados->count())
                                <div class="space-y-2">
                                    @foreach($servicio->resultados as $resultado)
                                        <div class="flex items-center justify-between gap-4 bg-gray-50 border border-gray-100 rounded-xl p-3">
                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-gray-800 truncate">
                                                    {{ $resultado->tipoAnalisis?->nombre ?? '—' }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Alerta: {{ $resultado->alerta_rango }}
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-bold text-gray-900">{{ $resultado->valor_registrado }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500">Todavía no hay resultados para este servicio.</div>
                            @endif
                        </div>

                        {{-- Cultivos (si existen) --}}
                        <div>
                            <div class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-seedling text-purple-600"></i>
                                Cultivos registrados
                            </div>

                            @if($servicio->cultivos && $servicio->cultivos->count())
                                <div class="space-y-2">
                                    @foreach($servicio->cultivos as $cultivo)
                                        <div class="flex items-center justify-between gap-4 bg-gray-50 border border-gray-100 rounded-xl p-3">
                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-gray-800 truncate">
                                                    {{ $cultivo->tipoAnalisis?->nombre ?? '—' }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Estado: {{ $cultivo->estado_cultivo }}
                                                </div>
                                                @if(!empty($cultivo->cepa_bacteriana))
                                                    <div class="text-xs text-gray-500">
                                                        Cepa: {{ $cultivo->cepa_bacteriana }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500">Todavía no hay cultivos para este servicio.</div>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

