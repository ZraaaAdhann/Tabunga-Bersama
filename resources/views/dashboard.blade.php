<x-app-layout :currentProject="$currentProject">
    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 bg-slate-50 min-h-screen" x-data="{
        sidebarOpen: true,
        showDepositModal: false,
        showNewProjectModal: false,
        showEditProjectModal: false,
        showInviteModal: false,
        showLightboxModal: false,
        showQrisModal: false,
        activeLightboxImage: '',
        copiedToast: false,
        
        copyAccount() {
            navigator.clipboard.writeText('{{ $currentProject->account_number ?? '' }}');
            this.copiedToast = true;
            setTimeout(() => this.copiedToast = false, 2500);
        },

        openLightbox(imageUrl) {
            this.activeLightboxImage = imageUrl;
            this.showLightboxModal = true;
        }
    }" @toggle-sidebar.window="sidebarOpen = !sidebarOpen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Alert Flash Message Success --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Alert Validation Errors --}}
            @if($errors->any())
                <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Banner Undangan Project Masuk --}}
            @if(isset($pendingInvitations) && $pendingInvitations->isNotEmpty())
                <div class="mb-6 space-y-3">
                    @foreach($pendingInvitations as $invitation)
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex flex-wrap items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800 bg-amber-100/80 px-2 py-0.5 rounded">Undangan Project</span>
                                    <p class="text-sm font-medium text-slate-800 mt-0.5">
                                        <strong class="font-semibold text-slate-900">{{ $invitation->sender->name }}</strong> mengundang Anda untuk bergabung ke project <strong class="font-semibold text-slate-900">"{{ $invitation->project->title }}"</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('invitations.decline', $invitation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-200/80 hover:bg-slate-300 rounded-xl transition">Tolak</button>
                                </form>
                                <form action="{{ route('invitations.accept', $invitation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition">Terima</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Flex Layout Utama -->
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <!-- SIDEBAR TOGGLEABLE -->
                <aside x-show="sidebarOpen" 
                       x-cloak 
                       x-transition:enter="transition-all ease-out duration-300 transform"
                       x-transition:enter-start="opacity-0 -translate-x-full -ml-72"
                       x-transition:enter-end="opacity-100 translate-x-0 ml-0"
                       x-transition:leave="transition-all ease-in duration-250 transform"
                       x-transition:leave-start="opacity-100 translate-x-0 ml-0"
                       x-transition:leave-end="opacity-0 -translate-x-full -ml-72"
                       class="w-full lg:w-72 shrink-0 space-y-4 overflow-hidden">
                    
                    <!-- Card Manajemen Tabungan (Klik untuk Kembali ke Semua Project) -->
                    <a href="{{ route('dashboard') }}" 
                       class="block p-3.5 bg-emerald-50/70 border-2 border-emerald-500 hover:bg-emerald-100/80 rounded-2xl shadow-sm transition group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm leading-tight group-hover:text-emerald-800">Manajemen Tabungan</h4>
                                    <p class="text-[11px] text-emerald-700 font-medium mt-0.5">Semua Project</p>
                                </div>
                            </div>
                            <div class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs">
                                ✓
                            </div>
                        </div>
                    </a>

                    <!-- Sidebar Container Box -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm space-y-5">
                        
                        <!-- 1. Daftar Project -->
                        <div>
                            <div class="flex items-center justify-between mb-2.5">
                                <h3 class="text-xs font-extrabold text-emerald-800 tracking-wide uppercase">Daftar Project</h3>
                                <button @click="showNewProjectModal = true" class="text-emerald-600 hover:text-emerald-800 p-1" title="Tambah Project">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                            
                            <div class="space-y-1">
                                @forelse($projects as $proj)
                                    <a href="{{ route('dashboard', ['project_id' => $proj->id]) }}" 
                                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium transition {{ $currentProject && $currentProject->id === $proj->id ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span class="truncate flex-1">{{ $proj->title }}</span>
                                        @if($currentProject && $currentProject->id === $proj->id)
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        @endif
                                    </a>
                                @empty
                                    <p class="text-xs text-slate-400 py-1">Belum ada project.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- 2. Aktivitas Terkini -->
                        <div class="pt-3 border-t border-slate-100">
                            <h3 class="text-xs font-extrabold text-emerald-800 tracking-wide uppercase mb-2.5">Aktivitas Terkini</h3>
                            <div class="space-y-2.5">
                                @forelse($activityLogs as $log)
                                    <div class="text-[11px] border-b border-slate-50 pb-2 last:border-0 last:pb-0">
                                        <p class="font-medium text-slate-700 leading-snug">{{ $log['title'] }}</p>
                                        <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $log['timestamp'] }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400">Belum ada catatan.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </aside>

                <!-- Section Konten Utama -->
                <main class="flex-1 w-full min-w-0 space-y-8">
                    @if(!$currentProject)
                        <!-- TAMPILAN HALAMAN UTAMA: KUMPULAN CARD PROJECT -->
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h1 class="text-xl font-bold text-slate-900">Kumpulan Project Tabungan</h1>
                                    <p class="text-xs text-slate-500 mt-0.5">Pilih project untuk melihat detail setoran atau buat project baru.</p>
                                </div>
                                <button @click="showNewProjectModal = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-sm transition inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Buat Project Baru</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                @forelse($projects as $proj)
                                    @php
                                        $projCollected = $proj->deposits->where('status', 'verified')->sum('amount');
                                        $projPercentage = $proj->total_target > 0 
                                            ? min(100, round(($projCollected / $proj->total_target) * 100, 1)) 
                                            : 0;
                                    @endphp
                                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-3">
                                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    {{ $proj->category }}
                                                </span>
                                                <span class="text-xs text-slate-400 font-medium">{{ $proj->members->count() }} Anggota</span>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $proj->title }}</h3>
                                            <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">{{ $proj->description ?? 'Tidak ada deskripsi project.' }}</p>

                                            <!-- Progress Bar Mini -->
                                            <div class="space-y-1.5 mb-5">
                                                <div class="flex justify-between text-[11px] font-medium">
                                                    <span class="text-slate-500">Terkumpul</span>
                                                    <span class="text-emerald-700 font-bold">Rp {{ number_format($projCollected, 0, ',', '.') }} ({{ $projPercentage }}%)</span>
                                                </div>
                                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $projPercentage }}%"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                            <span class="text-[11px] text-slate-400">Target: Rp {{ number_format($proj->daily_target, 0, ',', '.') }}/hari</span>
                                            <a href="{{ route('dashboard', ['project_id' => $proj->id]) }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition inline-flex items-center gap-1">
                                                <span>Buka Project</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full bg-white rounded-3xl p-10 text-center shadow-sm border border-slate-200">
                                        <div class="w-14 h-14 bg-slate-100 text-slate-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-7 h-7 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Project Tabungan</h3>
                                        <p class="text-slate-500 text-sm mb-6">Mulai buat project tabungan bersama untuk mengelola alokasi dana dan target finansial secara efisien.</p>
                                        <button @click="showNewProjectModal = true" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition inline-flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            <span>Buat Project Pertama</span>
                                        </button>
                                    </div>
                                @endforelse

                                <!-- Card Tambah Project Baru -->
                                @if($projects->isNotEmpty())
                                    <button @click="showNewProjectModal = true" class="bg-slate-50 border-2 border-dashed border-slate-300 hover:border-emerald-500 hover:bg-emerald-50/30 rounded-2xl p-6 transition flex flex-col items-center justify-center text-center min-h-[220px] group">
                                        <div class="w-12 h-12 rounded-full bg-white border border-slate-200 group-hover:border-emerald-300 text-emerald-600 flex items-center justify-center mb-3 shadow-sm transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-800 group-hover:text-emerald-700">Buat Project Tabungan Baru</span>
                                        <span class="text-xs text-slate-400 mt-1">Mulai alokasi tabungan baru bersama pasangan/anggota</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                    @else
                        @php
                            $totalCollected = $currentProject->deposits->where('status', 'verified')->sum('amount');
                            $targetPercentage = $currentProject->total_target > 0 
                                ? min(100, round(($totalCollected / $currentProject->total_target) * 100, 1)) 
                                : 0;
                            $isCompleted = $currentProject->status === 'completed';
                            $isTargetReached = $currentProject->total_target > 0 && $totalCollected >= $currentProject->total_target;
                            $isLocked = $isCompleted || $isTargetReached;
                        @endphp

                        <!-- TAMPILAN DETAIL PROJECT YANG DIPILIH -->
                        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-lg border border-slate-800 relative overflow-hidden">
                            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        {{ $currentProject->category }}
                                    </span>
                                    @if($isCompleted)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                            Project Selesai
                                        </span>
                                    @elseif($isTargetReached)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Target Tercapai
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="text-xs text-slate-300 bg-slate-800/90 px-3 py-1 rounded-full border border-slate-700">
                                        Target Harian: <strong class="text-emerald-400">Rp {{ number_format($currentProject->daily_target, 0, ',', '.') }}</strong> / Hari
                                    </div>
                                    <a href="{{ route('projects.export-csv', $currentProject->id) }}" class="px-3.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-full border border-slate-700 transition inline-flex items-center gap-1.5" title="Unduh File Rekap CSV">
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Export CSV</span>
                                    </a>
                                    @if($currentProject->created_by === Auth::id())
                                        <button @click="showEditProjectModal = true" class="px-3.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-full border border-slate-700 transition">
                                            Pengaturan Project
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-extrabold mb-2 tracking-tight text-white">{{ $currentProject->title }}</h1>
                            <p class="text-slate-400 text-sm max-w-2xl mb-6 leading-relaxed">{{ $currentProject->description }}</p>

                            <!-- Progress Bar Total Target -->
                            <div class="mb-6 bg-slate-800/90 p-4 rounded-2xl border border-slate-700/80">
                                <div class="flex justify-between items-center text-xs mb-2">
                                    <span class="text-slate-400 font-medium">Progres Akumulasi Tabungan (Terverifikasi)</span>
                                    <span class="font-bold text-emerald-400">Rp {{ number_format($totalCollected, 0, ',', '.') }} / Rp {{ number_format($currentProject->total_target, 0, ',', '.') }} ({{ $targetPercentage }}%)</span>
                                </div>
                                <div class="w-full h-3 bg-slate-900 rounded-full overflow-hidden p-0.5 border border-slate-700">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500" style="width: {{ $targetPercentage }}%"></div>
                                </div>
                            </div>

                            <!-- Status Setoran Anggota Hari Ini (Dilengkapi Foto Profil/Avatar) -->
                            <div class="mb-6 p-4 rounded-2xl bg-slate-800/80 border border-slate-700/80">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-medium text-slate-400">Status Setoran Anggota Hari Ini</span>
                                    <button @click="showInviteModal = true" class="px-3 py-1 bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-medium rounded-lg border border-slate-600 transition inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                        <span>Undang Anggota</span>
                                    </button>
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    @foreach($currentProject->members as $member)
                                        @php
                                            $todayDeposit = $currentProject->deposits
                                                ->where('user_id', $member->id)
                                                ->filter(function ($deposit) {
                                                    return \Carbon\Carbon::parse($deposit->deposit_date)->isToday();
                                                })
                                                ->sortByDesc('id')
                                                ->first();

                                            $memberAvatar = $member->avatar 
                                                ? ((str_starts_with($member->avatar, 'data:') || str_starts_with($member->avatar, 'http'))
                                                    ? $member->avatar 
                                                    : asset('storage/' . $member->avatar))
                                                : 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=10b981&color=fff';
                                        @endphp
                                        <div class="flex items-center gap-2.5 bg-slate-900/90 px-3 py-1.5 rounded-xl border border-slate-700 text-xs">
                                            <img src="{{ $memberAvatar }}" alt="{{ $member->name }}" class="w-6 h-6 rounded-full object-cover border border-emerald-500/40">
                                            <span class="font-semibold text-slate-200">{{ $member->name }}</span>
                                            @if($todayDeposit)
                                                @if($todayDeposit->status === 'verified')
                                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Verified</span>
                                                @elseif($todayDeposit->status === 'rejected')
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">Ditolak</span>
                                                        @if($member->id === Auth::id() && !$isLocked)
                                                            <button @click="showDepositModal = true" class="text-[11px] text-amber-400 hover:text-amber-300 font-semibold underline transition">
                                                                Setor Ulang
                                                            </button>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">Menunggu</span>
                                                @endif
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-800 text-slate-400 border border-slate-700">Belum Setor</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Info Rekening Transfer & Tombol Aksi (Terkunci Otomatis Jika Project Selesai/Target Tercapai) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div>
                                    <div class="text-xs text-slate-400">Rekening Tujuan Transfer:</div>
                                    <div class="text-sm font-bold text-slate-100 mt-0.5 tracking-wide">
                                        {{ $currentProject->bank_name }} &bull; {{ $currentProject->account_number }}
                                    </div>
                                    <div class="text-xs text-slate-400">a.n {{ $currentProject->account_holder }}</div>
                                </div>
                                <div class="flex items-center gap-2 justify-start md:justify-end flex-wrap">
                                    <button @click="copyAccount()" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl border border-slate-700 transition inline-flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <span>Salin Rekening</span>
                                    </button>
                                    <button @click="showQrisModal = true" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl border border-slate-700 transition inline-flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        <span>QRIS Pembayaran</span>
                                    </button>
                                    
                                    @if($isLocked)
                                        <button disabled class="px-4 py-2 bg-slate-800/80 text-slate-400 text-xs font-semibold rounded-xl border border-slate-700 cursor-not-allowed inline-flex items-center gap-1.5 shadow-none opacity-80" title="{{ $isCompleted ? 'Project ini telah ditandai selesai.' : 'Target akumulasi tabungan sudah tercapai.' }}">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            <span>{{ $isCompleted ? 'Project Selesai' : 'Target Tercapai' }}</span>
                                        </button>
                                    @else
                                        <button @click="showDepositModal = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-sm transition inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            <span>Masukkan Tabungan</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Visualisasi Grafik Statistik -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Tren Setoran (7 Hari Terakhir)</h3>
                                <div class="h-56">
                                    <canvas id="trendChart"></canvas>
                                </div>
                            </div>
                            <div class="md:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Kontribusi Anggota</h3>
                                <div class="h-56 flex items-center justify-center">
                                    <canvas id="contributionChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Riwayat Transaksi -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                            <h3 class="text-base font-bold text-slate-900 mb-4">Riwayat Setoran Tabungan</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-600">
                                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-200">
                                        <tr>
                                            <th class="p-3">Anggota</th>
                                            <th class="p-3">Tanggal Setor</th>
                                            <th class="p-3">Nominal</th>
                                            <th class="p-3">Status Verifikasi</th>
                                            <th class="p-3">Bukti Transfer</th>
                                            @if($currentProject->created_by === Auth::id())
                                                <th class="p-3 text-right">Aksi Owner</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($deposits as $deposit)
                                        <tr class="hover:bg-slate-50/80">
                                            <td class="p-3 font-semibold text-slate-800 flex items-center gap-2">
                                                @php
                                                    $depositUserAvatar = $deposit->user->avatar 
                                                        ? ((str_starts_with($deposit->user->avatar, 'data:') || str_starts_with($deposit->user->avatar, 'http'))
                                                            ? $deposit->user->avatar 
                                                            : asset('storage/' . $deposit->user->avatar))
                                                        : 'https://ui-avatars.com/api/?name='.urlencode($deposit->user->name).'&background=10b981&color=fff';
                                                @endphp
                                                <img src="{{ $depositUserAvatar }}" alt="{{ $deposit->user->name }}" class="w-6 h-6 rounded-full object-cover border border-slate-200">
                                                <span>{{ $deposit->user->name }}</span>
                                            </td>
                                            <td class="p-3 text-slate-600">{{ $deposit->deposit_date->format('d M Y') }}</td>
                                            <td class="p-3 font-bold text-emerald-700">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</td>
                                            <td class="p-3">
                                                @if($deposit->status === 'verified')
                                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Terverifikasi</span>
                                                @elseif($deposit->status === 'rejected')
                                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                                @else
                                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-50 text-amber-700 border border-amber-200">Menunggu Verifikasi</span>
                                                @endif
                                            </td>
                                            <td class="p-3">
                                                @if($deposit->proof_image)
                                                    @php
                                                        $proofUrl = str_starts_with($deposit->proof_image, 'data:') ? $deposit->proof_image : asset('storage/' . $deposit->proof_image);
                                                    @endphp
                                                    <button @click="openLightbox('{{ $proofUrl }}')" 
                                                            class="inline-flex items-center gap-1.5 text-xs text-emerald-700 hover:text-emerald-800 font-medium bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2.5 py-1 rounded-lg transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        <span>Lihat Bukti</span>
                                                    </button>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">Tidak Ada</span>
                                                @endif
                                            </td>
                                            @if($currentProject->created_by === Auth::id())
                                                <td class="p-3 text-right">
                                                    @if($deposit->status === 'pending')
                                                        <div class="flex items-center justify-end gap-1.5">
                                                            <form action="{{ route('deposits.verify', $deposit->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition">Setujui</button>
                                                            </form>
                                                            <form action="{{ route('deposits.reject', $deposit->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg transition">Tolak</button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <span class="text-xs text-slate-400 font-medium">Selesai</span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="{{ $currentProject->created_by === Auth::id() ? 6 : 5 }}" class="text-center py-8 text-slate-400">Belum ada riwayat setoran tabungan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </main>

            </div>

        </div>

        <!-- MODAL FORM & DIALOGS -->

        <!-- Modal Edit Project -->
        @if(isset($currentProject) && $currentProject && $currentProject->created_by === Auth::id())
        <div x-show="showEditProjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak x-transition>
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-100 max-h-[90vh] overflow-y-auto" @click.outside="showEditProjectModal = false">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Pengaturan Project</h3>
                    <button @click="showEditProjectModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('projects.update', $currentProject->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Judul Project</label>
                        <input type="text" name="title" required value="{{ old('title', $currentProject->title) }}" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Kategori</label>
                            <input type="text" name="category" required value="{{ old('category', $currentProject->category) }}" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Target Harian (Rp)</label>
                            <input type="number" name="daily_target" required min="0" value="{{ old('daily_target', $currentProject->daily_target) }}" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Total Target Akhir (Rp)</label>
                        <input type="number" name="total_target" required min="0" value="{{ old('total_target', $currentProject->total_target) }}" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Deskripsi & Motivasi</label>
                        <textarea name="description" rows="2" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">{{ old('description', $currentProject->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Nama Bank</label>
                            <input type="text" name="bank_name" required value="{{ old('bank_name', $currentProject->bank_name) }}" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">No. Rekening</label>
                            <input type="text" name="account_number" required value="{{ old('account_number', $currentProject->account_number) }}" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Atas Nama</label>
                            <input type="text" name="account_holder" required value="{{ old('account_holder', $currentProject->account_holder) }}" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Ganti Gambar QRIS (Opsional)</label>
                        <input type="file" name="qris_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    </div>

                    <div class="flex gap-2 pt-3">
                        <button type="button" @click="showEditProjectModal = false" class="w-1/2 py-2.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                        <button type="submit" class="w-1/2 py-2.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition">Simpan Perubahan</button>
                    </div>
                </form>

                <div class="border-t border-slate-100 mt-6 pt-4 space-y-2">
                    @if($currentProject->status !== 'completed')
                        <form action="{{ route('projects.complete', $currentProject->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Tandai project ini sebagai Selesai?')" class="w-full py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Tandai Project Selesai</button>
                        </form>
                    @endif
                    <form action="{{ route('projects.destroy', $currentProject->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus project ini beserta seluruh riwayat setoran secara permanen?')" class="w-full py-2 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition">Hapus Project Permanen</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Modal Tambah Project Baru -->
        <div x-show="showNewProjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak x-transition>
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-100" @click.outside="showNewProjectModal = false">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Buat Project Tabungan Baru</h3>
                    <button @click="showNewProjectModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Judul Project</label>
                        <input type="text" name="title" required placeholder="Contoh: Rumah Impian Masa Depan" class="w-full text-sm border border-slate-300 rounded-xl p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Kategori</label>
                            <input type="text" name="category" required value="Couple Goals" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Target Harian (Rp)</label>
                            <input type="number" name="daily_target" required min="0" placeholder="100000" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Total Target Akhir (Rp)</label>
                        <input type="number" name="total_target" required min="0" placeholder="50000000" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Deskripsi & Motivasi</label>
                        <textarea name="description" rows="2" placeholder="Tujuan menabung..." class="w-full text-sm border border-slate-300 rounded-xl p-2.5"></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Nama Bank</label>
                            <input type="text" name="bank_name" required placeholder="BCA" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">No. Rekening</label>
                            <input type="text" name="account_number" required placeholder="1234567890" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Atas Nama</label>
                            <input type="text" name="account_holder" required placeholder="Nama Pemilik" class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Gambar QRIS (Opsional)</label>
                        <input type="file" name="qris_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    </div>

                    <div class="flex gap-2 pt-3">
                        <button type="button" @click="showNewProjectModal = false" class="w-1/2 py-2.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                        <button type="submit" class="w-1/2 py-2.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition">Simpan Project</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Form Setor Tabungan -->
        @if(isset($currentProject) && $currentProject && !$isLocked)
        <div x-show="showDepositModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak x-transition>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100" @click.outside="showDepositModal = false">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Form Setor Tabungan</h3>
                    <button @click="showDepositModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('deposits.store', $currentProject->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Tanggal Setoran</label>
                        <input type="text" value="{{ date('d F Y') }}" readonly class="w-full text-sm bg-slate-100 border border-slate-200 rounded-xl p-2.5 text-slate-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Jumlah Nominal (Rp)</label>
                        <input type="number" name="amount" required min="1000" placeholder="Contoh: 100000" class="w-full text-sm border border-slate-300 rounded-xl p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Upload Bukti Transfer</label>
                        <input type="file" name="proof_image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Catatan (Opsional)</label>
                        <input type="text" name="notes" placeholder="Catatan setoran..." class="w-full text-sm border border-slate-300 rounded-xl p-2.5">
                    </div>

                    <div class="flex gap-2 pt-3">
                        <button type="button" @click="showDepositModal = false" class="w-1/2 py-2.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                        <button type="submit" class="w-1/2 py-2.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition">Kirim Setoran</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Modal Form Undang Anggota -->
        @if(isset($currentProject) && $currentProject)
        <div x-show="showInviteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak x-transition>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100" @click.outside="showInviteModal = false">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Undang Anggota / Pasangan</h3>
                    <button @click="showInviteModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div x-data="{
                    searchQuery: '',
                    searchResults: [],
                    selectedUser: null,
                    isLoading: false,
                    search() {
                        if (this.searchQuery.trim().length < 2) { this.searchResults = []; return; }
                        this.isLoading = true;
                        fetch(`/users/search?q=${encodeURIComponent(this.searchQuery.trim())}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(res => res.json())
                        .then(data => { this.searchResults = data; this.isLoading = false; })
                        .catch(() => { this.searchResults = []; this.isLoading = false; });
                    }
                }">
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-slate-700 mb-1">Cari Nama, Username, atau Email</label>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="search()" placeholder="Ketik kata kunci pencarian..." class="w-full text-sm border border-slate-300 rounded-xl p-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                        
                        <div x-show="searchResults.length > 0" class="mt-2 bg-white border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100 shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="u in searchResults" :key="u.id">
                                <div @click="selectedUser = u; searchResults = []" class="p-3 hover:bg-emerald-50 cursor-pointer flex justify-between items-center text-xs">
                                    <div>
                                        <div class="font-bold text-slate-800" x-text="u.name"></div>
                                        <div class="text-slate-400 text-[11px]" x-text="u.email"></div>
                                    </div>
                                    <span class="text-emerald-600 font-semibold bg-emerald-100/60 px-2.5 py-1 rounded-lg">Pilih</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <form action="{{ route('projects.invite', $currentProject->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="receiver_id" :value="selectedUser ? selectedUser.id : ''">

                        <template x-if="selectedUser">
                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs flex justify-between items-center">
                                <div>
                                    <span class="text-slate-500">Target Undangan:</span>
                                    <div class="font-bold text-emerald-900" x-text="selectedUser.name"></div>
                                </div>
                                <button type="button" @click="selectedUser = null" class="text-slate-400 hover:text-slate-600 font-bold">Batal</button>
                            </div>
                        </template>

                        <div class="flex gap-2 pt-2">
                            <button type="button" @click="showInviteModal = false" class="w-1/2 py-2.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                            <button type="submit" :disabled="!selectedUser" class="w-1/2 py-2.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 rounded-xl shadow-sm transition">Kirim Undangan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Modal QRIS Pembayaran -->
        @if(isset($currentProject) && $currentProject)
        <div x-show="showQrisModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak x-transition>
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl border border-slate-100 text-center" @click.outside="showQrisModal = false">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">QRIS Pembayaran Official</h3>
                    <button @click="showQrisModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                @if($currentProject->qris_image_path)
                    @php
                        $qrisUrl = str_starts_with($currentProject->qris_image_path, 'data:') ? $currentProject->qris_image_path : asset('storage/' . $currentProject->qris_image_path);
                    @endphp
                    <img src="{{ $qrisUrl }}" alt="QRIS {{ $currentProject->title }}" class="w-full h-auto max-h-80 object-contain rounded-xl border border-slate-200 shadow-sm mb-3">
                @else
                    <div class="p-8 bg-slate-50 border border-dashed border-slate-300 rounded-xl mb-3 text-slate-400 text-xs">
                        Gambar QRIS belum diunggah untuk project ini.
                    </div>
                @endif

                <p class="text-xs text-slate-500 mb-4">Pindai kode QRIS di atas menggunakan aplikasi m-Banking atau E-Wallet untuk melakukan setoran.</p>
                <button type="button" @click="showQrisModal = false" class="w-full py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Tutup</button>
            </div>
        </div>
        @endif

        <!-- Modal Lightbox Preview Bukti Transfer -->
        <div x-show="showLightboxModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-md p-4" x-cloak x-transition>
            <div class="relative max-w-2xl w-full bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-800" @click.outside="showLightboxModal = false">
                <div class="flex justify-between items-center px-4 py-3 border-b border-slate-800 bg-slate-900/90">
                    <h4 class="text-xs font-semibold text-slate-300">Pratinjau Bukti Transfer</h4>
                    <button @click="showLightboxModal = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-4 flex items-center justify-center max-h-[75vh] overflow-auto">
                    <img :src="activeLightboxImage" alt="Bukti Transfer" class="max-w-full max-h-[65vh] object-contain rounded-lg border border-slate-800 shadow-md">
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <div x-show="copiedToast" class="fixed bottom-5 right-5 bg-slate-900 text-white text-xs px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 border border-slate-700" x-cloak x-transition>
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Nomor rekening berhasil disalin.</span>
        </div>
    </div>

    <!-- Script Inisialisasi Chart.js -->
    @if(isset($currentProject) && $currentProject)
    <script>
        (function renderCharts() {
            function init() {
                const ctxTrend = document.getElementById('trendChart')?.getContext('2d');
                if (ctxTrend) {
                    new Chart(ctxTrend, {
                        type: 'line',
                        data: {
                            labels: @json($chartDates),
                            datasets: [{
                                label: 'Nominal Setoran (Rp)',
                                data: @json($chartAmounts),
                                borderColor: '#059669',
                                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                                fill: true,
                                tension: 0.3,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }

                const ctxPie = document.getElementById('contributionChart')?.getContext('2d');
                if (ctxPie) {
                    new Chart(ctxPie, {
                        type: 'doughnut',
                        data: {
                            labels: @json($chartUserLabels),
                            datasets: [{
                                data: @json($chartUserAmounts),
                                backgroundColor: ['#059669', '#0284c7', '#d97706', '#7c3aed', '#e11d48'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                            }
                        }
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
    @endif
</x-app-layout>