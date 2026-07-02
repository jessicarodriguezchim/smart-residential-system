<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fraccionamiento Inteligente - Panel de Control</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .tab-btn.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(168, 85, 247, 0.15) 100%);
            border-color: rgba(129, 140, 248, 0.4);
            color: #fff;
        }
    </style>
</head>
<body class="h-full flex flex-col overflow-hidden">

    <!-- Alerts / Toasts -->
    @if(session('success') || session('error'))
    <div class="fixed top-6 right-6 z-50 max-w-md w-full animate-bounce">
        @if(session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/30 text-emerald-200 px-4 py-3 rounded-lg shadow-xl backdrop-blur-md flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-semibold text-sm">Operación Exitosa</p>
                <p class="text-xs opacity-90">{{ session('success') }}</p>
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-rose-950/80 border border-rose-500/30 text-rose-200 px-4 py-3 rounded-lg shadow-xl backdrop-blur-md flex items-center gap-3">
            <svg class="w-6 h-6 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="font-semibold text-sm">Ocurrió un Error</p>
                <p class="text-xs opacity-90">{{ session('error') }}</p>
            </div>
        </div>
        @endif
    </div>
    <script>
        setTimeout(() => {
            const toast = document.querySelector('.fixed.top-6');
            if (toast) {
                toast.style.transition = 'opacity 0.5s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000);
    </script>
    @endif

    <div class="flex h-full w-full overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside class="w-72 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0">
            <!-- Brand Logo -->
            <div class="h-16 px-6 border-b border-slate-800 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-sm tracking-wide bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">FRACC. INTELIGENTE</h1>
                    <span class="text-[10px] font-semibold tracking-wider text-indigo-400 uppercase">Panel Administrativo</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <button onclick="switchTab('resumen')" id="tab-btn-resumen" class="tab-btn active w-full flex items-center gap-3 px-4 py-3 rounded-lg border border-transparent text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition duration-150">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    Resumen General
                </button>
                
                <button onclick="switchTab('visitas')" id="tab-btn-visitas" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg border border-transparent text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition duration-150">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Control de Accesos (Visitas)
                </button>

                <button onclick="switchTab('lotes')" id="tab-btn-lotes" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg border border-transparent text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition duration-150">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Lotes y Residentes
                </button>

                <button onclick="switchTab('finanzas')" id="tab-btn-finanzas" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg border border-transparent text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition duration-150">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Finanzas y Mantenimiento
                </button>

                <button onclick="switchTab('auditoria')" id="tab-btn-auditoria" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg border border-transparent text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition duration-150">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Auditoría y Notificaciones
                </button>
            </nav>

            <!-- Active User Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-950 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white shadow-md">
                    AP
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate">Admin Principal</p>
                    <p class="text-[10px] text-slate-400 truncate">admin@fracc.com</p>
                </div>
            </div>
        </aside>

        <!-- Main Body Wrapper -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-950">
            <!-- Header/Topbar -->
            <header class="h-16 border-b border-slate-800 px-8 flex items-center justify-between shrink-0 glass-panel">
                <div>
                    <h2 id="section-title" class="text-lg font-semibold text-white">Resumen General</h2>
                    <p id="section-subtitle" class="text-xs text-slate-400">Indicadores clave y estado actual del fraccionamiento.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs font-semibold text-white" id="live-time"></p>
                        <p class="text-[10px] text-indigo-400">Estado de Servidores: Activos</p>
                    </div>
                    <script>
                        setInterval(() => {
                            const now = new Date();
                            document.getElementById('live-time').innerText = now.toLocaleDateString('es-ES', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }) + ' - ' + now.toLocaleTimeString('es-ES');
                        }, 1000);
                    </script>
                </div>
            </header>

            <!-- Main Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto p-8">

                <!-- PANEL: RESUMEN -->
                <div id="panel-resumen" class="tab-panel space-y-8">
                    <!-- KPI Cards Row -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Card 1 -->
                        <div class="glass-card rounded-xl p-6 relative overflow-hidden group hover:border-indigo-500/30 transition-all duration-300">
                            <div class="absolute right-0 top-0 translate-x-2 -translate-y-2 opacity-5 text-indigo-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-400 tracking-wider uppercase">Lotes Totales</p>
                            <h3 class="text-3xl font-bold mt-2 text-white">{{ $totalLots }}</h3>
                            <div class="mt-4 flex gap-4 text-xs">
                                <span class="text-emerald-400 font-medium">{{ $soldLots }} Vendidos</span>
                                <span class="text-indigo-400 font-medium">{{ $availableLots }} Disponibles</span>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="glass-card rounded-xl p-6 relative overflow-hidden group hover:border-emerald-500/30 transition-all duration-300">
                            <div class="absolute right-0 top-0 translate-x-2 -translate-y-2 opacity-5 text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-400 tracking-wider uppercase">Recaudación Total</p>
                            <h3 class="text-3xl font-bold mt-2 text-emerald-400">${{ number_format($totalIncome, 2) }}</h3>
                            <div class="mt-4 text-xs text-slate-400">
                                Total de cuotas cobradas en el portal
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="glass-card rounded-xl p-6 relative overflow-hidden group hover:border-rose-500/30 transition-all duration-300">
                            <div class="absolute right-0 top-0 translate-x-2 -translate-y-2 opacity-5 text-rose-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-400 tracking-wider uppercase">Adeudos de Mantenimiento</p>
                            <h3 class="text-3xl font-bold mt-2 text-rose-400">${{ number_format($totalPendingAmount, 2) }}</h3>
                            <div class="mt-4 flex gap-4 text-xs">
                                <span class="text-amber-400 font-medium">{{ $pendingFeesCount }} Pendientes</span>
                                <span class="text-rose-400 font-medium">{{ $overdueFeesCount }} Vencidos</span>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="glass-card rounded-xl p-6 relative overflow-hidden group hover:border-purple-500/30 transition-all duration-300">
                            <div class="absolute right-0 top-0 translate-x-2 -translate-y-2 opacity-5 text-purple-400 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-400 tracking-wider uppercase">Accesos Activos</p>
                            <h3 class="text-3xl font-bold mt-2 text-purple-400">{{ $activeVisitsCount }}</h3>
                            <div class="mt-4 text-xs text-slate-400">
                                Visitantes dentro del fraccionamiento hoy
                            </div>
                        </div>
                    </div>

                    <!-- Charts & Live Activities -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Chart Column -->
                        <div class="lg:col-span-2 glass-card rounded-xl p-6 space-y-6">
                            <h3 class="text-base font-semibold text-white">Visualización de Datos y Finanzas</h3>
                            <div class="h-80 relative flex items-center justify-center">
                                <canvas id="chartFinanzas" class="w-full h-full"></canvas>
                            </div>
                        </div>

                        <!-- Quick Actions & Quick Notification -->
                        <div class="glass-card rounded-xl p-6 space-y-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-white mb-4">Acceso Rápido de Vigilancia</h3>
                                <div class="space-y-3">
                                    <button onclick="switchTab('visitas')" class="w-full py-3 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 font-semibold text-sm text-center transition duration-150 flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/20">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Registrar Nueva Visita
                                    </button>
                                    <button onclick="switchTab('finanzas')" class="w-full py-3 px-4 rounded-lg bg-slate-800 hover:bg-slate-700 font-semibold text-sm text-slate-200 text-center transition duration-150 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        Cobrar Cuota de Lote
                                    </button>
                                </div>
                            </div>
                            <div class="border-t border-slate-800 pt-6">
                                <h4 class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-2">Simulación de Notificación</h4>
                                <p class="text-xs text-slate-400 mb-4">Envía una alerta de prueba a los residentes del fraccionamiento para validar el portal.</p>
                                <div class="bg-indigo-950/30 border border-indigo-500/20 rounded-lg p-3 text-[11px] text-slate-300">
                                    <strong>Último Log de Notificación:</strong>
                                    @if($notifications->count() > 0)
                                        <p class="mt-1 line-clamp-2 italic">"{{ $notifications->first()->content }}"</p>
                                    @else
                                        <p class="mt-1 text-slate-500">Ninguna notificación enviada aún.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- PANEL: VISITAS -->
                <div id="panel-visitas" class="tab-panel hidden space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Form Column -->
                        <div class="glass-card rounded-xl p-6 space-y-6 h-fit">
                            <div class="border-b border-slate-800 pb-4">
                                <h3 class="text-base font-semibold text-white">Registrar Entrada de Visitante</h3>
                                <p class="text-xs text-slate-400 mt-1">Ingresa los datos del visitante en caseta de vigilancia.</p>
                            </div>
                            <form action="{{ route('visits.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Lote a Visitar *</label>
                                    <select name="lot_id" required class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-indigo-500 transition duration-150">
                                        <option value="">Selecciona Lote...</option>
                                        @foreach($lots->whereNotNull('owner_id') as $lot)
                                        <option value="{{ $lot->id }}">{{ $lot->number }} - {{ $lot->street }} ({{ $lot->owner->first_name }} {{ $lot->owner->last_name }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Nombre del Visitante *</label>
                                    <input type="text" name="visitor_name" required placeholder="Ej. Pedro Picapiedra" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition duration-150">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Placas de Vehículo</label>
                                    <input type="text" name="vehicle_plate" placeholder="Ej. ABC-123-A" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition duration-150">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Notas u Observaciones</label>
                                    <textarea name="notes" rows="3" placeholder="Proveedor de servicios, familiar, etc..." class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition duration-150"></textarea>
                                </div>

                                <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 font-semibold text-sm text-white transition duration-150 flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/20">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Registrar Entrada
                                </button>
                            </form>
                        </div>

                        <!-- Data List Column -->
                        <div class="lg:col-span-2 glass-card rounded-xl p-6 space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                                <div>
                                    <h3 class="text-base font-semibold text-white">Registro de Visitas Activas</h3>
                                    <p class="text-xs text-slate-400 mt-1">Bitácora en tiempo real de entradas y salidas.</p>
                                </div>
                                <span class="bg-indigo-950 text-indigo-400 text-xs font-semibold px-2.5 py-1 rounded-full border border-indigo-500/20">
                                    {{ $visits->where('status', 'activo')->count() }} Activas
                                </span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-300">
                                    <thead class="text-xs uppercase text-slate-400 bg-slate-900/50">
                                        <tr>
                                            <th class="py-3 px-4 rounded-l-lg">Lote</th>
                                            <th class="py-3 px-4">Visitante</th>
                                            <th class="py-3 px-4">Placas</th>
                                            <th class="py-3 px-4">Entrada</th>
                                            <th class="py-3 px-4">Salida</th>
                                            <th class="py-3 px-4">Estado</th>
                                            <th class="py-3 px-4 rounded-r-lg text-right">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800/50">
                                        @foreach($visits as $visit)
                                        <tr class="hover:bg-slate-900/30 transition-colors">
                                            <td class="py-3.5 px-4 font-semibold text-white">{{ $visit->lot->number }}</td>
                                            <td class="py-3.5 px-4">
                                                <div class="font-medium text-slate-200">{{ $visit->visitor_name }}</div>
                                                <div class="text-[10px] text-slate-500">{{ $visit->qr_code }}</div>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="font-mono text-xs bg-slate-900 px-2 py-0.5 rounded border border-slate-800 text-slate-400">{{ $visit->vehicle_plate ?: 'N/A' }}</span>
                                            </td>
                                            <td class="py-3.5 px-4 text-xs">
                                                {{ $visit->entry_at->format('d/m/Y H:i') }}
                                                <span class="block text-[10px] text-slate-500">Por: {{ $visit->entryRegisteredBy->name ?? 'Sistema' }}</span>
                                            </td>
                                            <td class="py-3.5 px-4 text-xs">
                                                @if($visit->exit_at)
                                                    {{ $visit->exit_at->format('d/m/Y H:i') }}
                                                    <span class="block text-[10px] text-slate-500">Por: {{ $visit->exitRegisteredBy->name ?? 'Sistema' }}</span>
                                                @else
                                                    <span class="text-slate-500 font-medium italic">En sitio</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4">
                                                @if($visit->status == 'activo')
                                                    <span class="bg-indigo-950 text-indigo-400 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-indigo-500/20">En Sitio</span>
                                                @elseif($visit->status == 'completado')
                                                    <span class="bg-emerald-950 text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-emerald-500/20">Completado</span>
                                                @else
                                                    <span class="bg-slate-800 text-slate-400 text-[10px] font-semibold px-2 py-0.5 rounded-full">{{ $visit->status }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-right">
                                                @if($visit->status == 'activo')
                                                <form action="{{ route('visits.exit', $visit->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-xs bg-rose-950/50 hover:bg-rose-900 border border-rose-500/20 text-rose-300 font-medium px-2.5 py-1 rounded transition duration-150">
                                                        Registrar Salida
                                                    </button>
                                                </form>
                                                @else
                                                <span class="text-xs text-slate-600 font-medium">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>


                <!-- PANEL: LOTES -->
                <div id="panel-lotes" class="tab-panel hidden space-y-8">
                    <!-- Grid Visual Maps -->
                    <div class="glass-card rounded-xl p-6 space-y-6">
                        <div>
                            <h3 class="text-base font-semibold text-white">Mapa e Inventario de Lotes</h3>
                            <p class="text-xs text-slate-400 mt-1">Distribución y propiedad de cada espacio residencial.</p>
                        </div>

                        <!-- Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                            @foreach($lots as $lot)
                            <div class="p-4 rounded-xl border text-center flex flex-col justify-between h-36 transition-all duration-300 hover:scale-[1.03] 
                                @if($lot->status == 'disponible')
                                    bg-slate-900/40 border-slate-800 text-slate-200 hover:border-slate-600
                                @elseif($lot->status == 'vendido')
                                    bg-emerald-950/20 border-emerald-500/20 text-emerald-100 hover:border-emerald-500/40
                                @elseif($lot->status == 'apartado')
                                    bg-amber-950/20 border-amber-500/20 text-amber-100 hover:border-amber-500/40
                                @endif">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-mono font-bold">{{ $lot->number }}</span>
                                        @if($lot->status == 'disponible')
                                            <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                                        @elseif($lot->status == 'vendido')
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        @elseif($lot->status == 'apartado')
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 truncate">{{ $lot->street }}</p>
                                </div>
                                <div class="my-2">
                                    <p class="text-xs font-semibold truncate">
                                        @if($lot->owner)
                                            {{ $lot->owner->first_name }} {{ $lot->owner->last_name }}
                                        @else
                                            <span class="text-slate-500 font-normal italic">Sin Asignar</span>
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-slate-500">{{ $lot->surface_area }} m²</p>
                                </div>
                                <div class="text-[10px] uppercase font-bold tracking-wider opacity-95">
                                    @if($lot->status == 'disponible')
                                        <span class="text-slate-400">Disponible</span>
                                    @elseif($lot->status == 'vendido')
                                        <span class="text-emerald-400">Vendido</span>
                                    @elseif($lot->status == 'apartado')
                                        <span class="text-amber-400">Apartado</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Owners List -->
                    <div class="glass-card rounded-xl p-6 space-y-6">
                        <div>
                            <h3 class="text-base font-semibold text-white">Directorio de Propietarios y Residentes</h3>
                            <p class="text-xs text-slate-400 mt-1">Lista completa de personas dadas de alta en el sistema.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-300">
                                <thead class="text-xs uppercase text-slate-400 bg-slate-900/50">
                                    <tr>
                                        <th class="py-3 px-4 rounded-l-lg">Residente</th>
                                        <th class="py-3 px-4">Teléfono</th>
                                        <th class="py-3 px-4">Email</th>
                                        <th class="py-3 px-4">Lotes Relacionados</th>
                                        <th class="py-3 px-4">Cuenta de Portal</th>
                                        <th class="py-3 px-4 rounded-r-lg">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/50">
                                    @foreach($owners as $owner)
                                    <tr class="hover:bg-slate-900/30 transition-colors">
                                        <td class="py-3.5 px-4 font-semibold text-white">
                                            {{ $owner->first_name }} {{ $owner->last_name }}
                                        </td>
                                        <td class="py-3.5 px-4 font-mono text-xs">{{ $owner->phone ?: 'N/A' }}</td>
                                        <td class="py-3.5 px-4 text-xs text-slate-400">{{ $owner->email }}</td>
                                        <td class="py-3.5 px-4 text-xs">
                                            @php
                                                $relatedLots = $lots->where('owner_id', $owner->id);
                                            @endphp
                                            @if($relatedLots->count() > 0)
                                                <div class="flex gap-1.5 flex-wrap">
                                                    @foreach($relatedLots as $rl)
                                                    <span class="bg-indigo-950/40 text-indigo-400 text-[10px] font-mono px-2 py-0.5 rounded border border-indigo-500/10">{{ $rl->number }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-slate-500 italic">Ninguno</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-xs">
                                            @if($owner->user_id)
                                                <span class="text-emerald-400 font-medium">Vinculada ({{ $owner->user->name ?? 'Usuario' }})</span>
                                            @else
                                                <span class="text-slate-500 italic">Sin cuenta de acceso</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if($owner->status == 'activo')
                                            <span class="bg-emerald-950 text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-emerald-500/20 uppercase tracking-wider">Activo</span>
                                            @else
                                            <span class="bg-slate-800 text-slate-400 text-[10px] font-semibold px-2 py-0.5 rounded-full uppercase tracking-wider">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <!-- PANEL: FINANZAS -->
                <div id="panel-finanzas" class="tab-panel hidden space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Form: Generate Maintenance Fee -->
                        <div class="glass-card rounded-xl p-6 space-y-6 h-fit">
                            <div class="border-b border-slate-800 pb-4">
                                <h3 class="text-base font-semibold text-white">Generar Nueva Cuota</h3>
                                <p class="text-xs text-slate-400 mt-1">Crea una obligación de pago para un lote específico.</p>
                            </div>
                            <form action="{{ route('fees.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Lote Destinatario *</label>
                                    <select name="lot_id" required class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-indigo-500 transition duration-150">
                                        <option value="">Selecciona Lote...</option>
                                        @foreach($lots->whereNotNull('owner_id') as $lot)
                                        <option value="{{ $lot->id }}">{{ $lot->number }} ({{ $lot->owner->first_name }} {{ $lot->owner->last_name }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Mes *</label>
                                        <select name="month" required class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-indigo-500 transition duration-150">
                                            @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>{{ \Illuminate\Support\Carbon::create()->month($m)->monthName }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Año *</label>
                                        <input type="number" name="year" value="{{ date('Y') }}" min="2020" max="2030" required class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-indigo-500 transition duration-150">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Monto a Cobrar ($) *</label>
                                    <input type="number" name="amount" value="1500.00" step="0.01" required class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-indigo-500 transition duration-150">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Fecha de Vencimiento *</label>
                                    <input type="date" name="due_date" value="{{ date('Y-m-10') }}" required class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-indigo-500 transition duration-150">
                                </div>

                                <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 font-semibold text-sm text-white transition duration-150 flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/20">
                                    Generar Cuota
                                </button>
                            </form>
                        </div>

                        <!-- Data List: Maintenance Fees -->
                        <div class="lg:col-span-2 glass-card rounded-xl p-6 space-y-6">
                            <div>
                                <h3 class="text-base font-semibold text-white">Estado de Cuotas de Mantenimiento</h3>
                                <p class="text-xs text-slate-400 mt-1">Control de pagos y morosidad de los condóminos.</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-300">
                                    <thead class="text-xs uppercase text-slate-400 bg-slate-900/50">
                                        <tr>
                                            <th class="py-3 px-4 rounded-l-lg">Lote</th>
                                            <th class="py-3 px-4">Propietario</th>
                                            <th class="py-3 px-4">Periodo</th>
                                            <th class="py-3 px-4">Monto</th>
                                            <th class="py-3 px-4">Vence</th>
                                            <th class="py-3 px-4">Estado</th>
                                            <th class="py-3 px-4 rounded-r-lg text-right">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800/50">
                                        @foreach($maintenanceFees as $fee)
                                        <tr class="hover:bg-slate-900/30 transition-colors">
                                            <td class="py-3.5 px-4 font-semibold text-white">{{ $fee->lot->number }}</td>
                                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-200">
                                                {{ $fee->owner->first_name }} {{ $fee->owner->last_name }}
                                            </td>
                                            <td class="py-3.5 px-4 text-xs font-medium text-slate-300">
                                                {{ \Illuminate\Support\Carbon::create()->month($fee->month)->monthName }} {{ $fee->year }}
                                            </td>
                                            <td class="py-3.5 px-4 text-xs">
                                                <div class="font-bold text-white">${{ number_format($fee->amount, 2) }}</div>
                                                @if($fee->penalty_amount > 0)
                                                <div class="text-[10px] text-rose-400">Recargo: +${{ number_format($fee->penalty_amount, 2) }}</div>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-xs font-mono text-slate-400">{{ date('d/m/Y', strtotime($fee->due_date)) }}</td>
                                            <td class="py-3.5 px-4">
                                                @if($fee->status == 'pagado')
                                                    <span class="bg-emerald-950 text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-emerald-500/20 uppercase tracking-wider">Pagado</span>
                                                @elseif($fee->status == 'pendiente')
                                                    <span class="bg-amber-950 text-amber-400 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-amber-500/20 uppercase tracking-wider">Pendiente</span>
                                                @elseif($fee->status == 'vencido')
                                                    <span class="bg-rose-950 text-rose-400 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-rose-500/20 uppercase tracking-wider">Vencido</span>
                                                @else
                                                    <span class="bg-slate-800 text-slate-400 text-[10px] font-semibold px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $fee->status }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-right">
                                                @if($fee->status !== 'pagado')
                                                <!-- Quick payment form trigger / simulation -->
                                                <form action="{{ route('payments.store') }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="maintenance_fee_id" value="{{ $fee->id }}">
                                                    <input type="hidden" name="amount" value="{{ $fee->amount + $fee->penalty_amount }}">
                                                    <input type="hidden" name="payment_method" value="transferencia">
                                                    <button type="submit" class="text-xs bg-emerald-950/50 hover:bg-emerald-900 border border-emerald-500/20 text-emerald-300 font-semibold px-2.5 py-1 rounded transition duration-150">
                                                        Simular Pago
                                                    </button>
                                                </form>
                                                @else
                                                <span class="text-xs text-slate-600 font-medium">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Logs (Recibidos) -->
                    <div class="glass-card rounded-xl p-6 space-y-6">
                        <div>
                            <h3 class="text-base font-semibold text-white">Transacciones y Pagos Registrados</h3>
                            <p class="text-xs text-slate-400 mt-1">Historial de pagos recibidos exitosamente por la plataforma.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-300">
                                <thead class="text-xs uppercase text-slate-400 bg-slate-900/50">
                                    <tr>
                                        <th class="py-3 px-4 rounded-l-lg">ID Transacción</th>
                                        <th class="py-3 px-4">Lote</th>
                                        <th class="py-3 px-4">Monto</th>
                                        <th class="py-3 px-4">Método de Pago</th>
                                        <th class="py-3 px-4">Fecha</th>
                                        <th class="py-3 px-4 rounded-r-lg">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/50">
                                    @foreach($payments as $payment)
                                    <tr class="hover:bg-slate-900/30 transition-colors">
                                        <td class="py-3.5 px-4 font-mono text-xs text-slate-400">{{ $payment->transaction_id }}</td>
                                        <td class="py-3.5 px-4 font-semibold text-white">
                                            {{ $payment->maintenanceFee->lot->number ?? 'N/A' }}
                                        </td>
                                        <td class="py-3.5 px-4 font-bold text-white">${{ number_format($payment->amount, 2) }}</td>
                                        <td class="py-3.5 px-4 text-xs capitalize">
                                            @if($payment->payment_method == 'stripe')
                                                <span class="bg-indigo-950 text-indigo-400 px-2.5 py-0.5 rounded border border-indigo-500/20 font-medium">Stripe</span>
                                            @elseif($payment->payment_method == 'efectivo')
                                                <span class="bg-slate-800 text-slate-300 px-2.5 py-0.5 rounded border border-slate-700 font-medium">Efectivo</span>
                                            @elseif($payment->payment_method == 'transferencia')
                                                <span class="bg-blue-950 text-blue-400 px-2.5 py-0.5 rounded border border-blue-500/20 font-medium">SPEI/Transf.</span>
                                            @else
                                                <span class="bg-purple-950 text-purple-400 px-2.5 py-0.5 rounded border border-purple-500/20 font-medium">{{ $payment->payment_method }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-xs font-mono text-slate-400">{{ $payment->payment_date }}</td>
                                        <td class="py-3.5 px-4">
                                            @if($payment->status == 'aprobado')
                                            <span class="bg-emerald-950 text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-emerald-500/20 uppercase tracking-wider">Aprobado</span>
                                            @elseif($payment->status == 'pendiente')
                                            <span class="bg-amber-950 text-amber-400 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-amber-500/20 uppercase tracking-wider">Pendiente</span>
                                            @else
                                            <span class="bg-rose-950 text-rose-400 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-rose-500/20 uppercase tracking-wider">{{ $payment->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <!-- PANEL: AUDITORIA -->
                <div id="panel-auditoria" class="tab-panel hidden space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <!-- Notifications Audit -->
                        <div class="glass-card rounded-xl p-6 space-y-6">
                            <div>
                                <h3 class="text-base font-semibold text-white">Notificaciones Enviadas a Residentes</h3>
                                <p class="text-xs text-slate-400 mt-1">Bitácora de alertas de visitas, cuotas y avisos del fraccionamiento.</p>
                            </div>
                            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                                @foreach($notifications as $notif)
                                <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-4 space-y-2 hover:border-slate-700 transition duration-150">
                                    <div class="flex justify-between items-start">
                                        <span class="text-xs font-semibold text-indigo-400 uppercase tracking-wide">{{ $notif->type }}</span>
                                        <span class="text-[10px] font-mono text-slate-500">{{ $notif->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <h4 class="text-sm font-semibold text-slate-100">{{ $notif->title }}</h4>
                                    <p class="text-xs text-slate-400 leading-relaxed">{{ $notif->content }}</p>
                                    <div class="flex items-center justify-between pt-2 border-t border-slate-800/50">
                                        <span class="text-[10px] text-slate-500">Destinatario: {{ $notif->user->name ?? 'Usuario' }}</span>
                                        <span class="bg-slate-950 text-slate-400 text-[9px] px-2 py-0.5 rounded border border-slate-800 capitalize font-medium">{{ $notif->channel }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- System Action Logs -->
                        <div class="glass-card rounded-xl p-6 space-y-6">
                            <div>
                                <h3 class="text-base font-semibold text-white">Bitácora de Auditoría (Audit Logs)</h3>
                                <p class="text-xs text-slate-400 mt-1">Registro inmutable de actividades administrativas y de caseta.</p>
                            </div>
                            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                                @foreach($auditLogs as $log)
                                <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-4 space-y-2 text-xs">
                                    <div class="flex justify-between items-start">
                                        <span class="bg-indigo-950 text-indigo-400 font-mono text-[10px] px-2 py-0.5 rounded border border-indigo-500/20 font-bold uppercase">{{ $log->action }}</span>
                                        <span class="text-[10px] font-mono text-slate-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                    <p class="text-slate-300">
                                        El usuario <strong class="text-white">{{ $log->user->name ?? 'Sistema' }}</strong> realizó una acción en la entidad <strong class="text-slate-200">{{ class_basename($log->model_type) }} (ID: {{ $log->model_id }})</strong>.
                                    </p>
                                    <div class="flex gap-4 text-[10px] text-slate-500 pt-2 border-t border-slate-800/40">
                                        <span>IP: {{ $log->ip_address }}</span>
                                        <span class="truncate max-w-[200px]" title="{{ $log->user_agent }}">Browser: {{ $log->user_agent }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </main>

    </div>

    <!-- Script for interactive SPA Tab-Switching -->
    <script>
        function switchTab(tabId) {
            // Hide all tab panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            
            // Remove active style from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected panel
            const activePanel = document.getElementById('panel-' + tabId);
            if (activePanel) {
                activePanel.classList.remove('hidden');
            }

            // Mark button as active
            const activeBtn = document.getElementById('tab-btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.add('active');
            }

            // Update title & subtitle in header
            const titleEl = document.getElementById('section-title');
            const subtitleEl = document.getElementById('section-subtitle');
            
            if (tabId === 'resumen') {
                titleEl.innerText = 'Resumen General';
                subtitleEl.innerText = 'Indicadores clave y estado actual del fraccionamiento.';
            } else if (tabId === 'visitas') {
                titleEl.innerText = 'Control de Accesos (Caseta)';
                subtitleEl.innerText = 'Gestión de entrada/salida de visitantes y vehículos.';
            } else if (tabId === 'lotes') {
                titleEl.innerText = 'Lotes y Residentes';
                subtitleEl.innerText = 'Mapa catastral del fraccionamiento e información de propietarios.';
            } else if (tabId === 'finanzas') {
                titleEl.innerText = 'Finanzas y Mantenimiento';
                subtitleEl.innerText = 'Control de cobranza de cuotas ordinarias y registro de pagos.';
            } else if (tabId === 'auditoria') {
                titleEl.innerText = 'Auditoría y Notificaciones';
                subtitleEl.innerText = 'Historial inmutable de auditoría del sistema y envío de alertas.';
            }
        }

        // Initialize Chart.js for data visualization on Dashboard
        window.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('chartFinanzas').getContext('2d');
            
            // Fetch status distribution of lots
            const lotsData = {
                vendidos: {{ $soldLots }},
                apartados: {{ $lots->where('status', 'apartado')->count() }},
                disponibles: {{ $lots->where('status', 'disponible')->count() }}
            };

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Mayo', 'Junio', 'Julio'],
                    datasets: [
                        {
                            label: 'Cobrado ($)',
                            data: [3000, 1500, 0],
                            backgroundColor: 'rgba(16, 185, 129, 0.65)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1,
                            borderRadius: 6
                        },
                        {
                            label: 'Pendiente/Vencido ($)',
                            data: [0, 3150, 1500],
                            backgroundColor: 'rgba(239, 68, 68, 0.65)',
                            borderColor: 'rgb(239, 68, 68)',
                            borderWidth: 1,
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#94a3b8',
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: 11
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: 11
                                },
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
