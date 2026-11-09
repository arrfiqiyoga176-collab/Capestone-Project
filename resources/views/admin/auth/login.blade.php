<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Otentikasi Administrator | PESISIR-EDU Kota Bengkulu</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Gymove Palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'gymove-canvas': '#F4F7F9',
                        'gymove-blue': '#132985',
                        'gymove-blue-hover': '#0d1e63',
                        'gymove-orange': '#FF8A2B',
                        'gymove-orange-hover': '#f07818',
                        'gymove-soft-blue': '#EEF2FF',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    borderRadius: {
                        'card': '28px',
                    },
                    boxShadow: {
                        'soft-card': '0 20px 50px rgba(19, 41, 133, 0.08)',
                        'blue-glow': '0 10px 25px rgba(19, 41, 133, 0.3)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
</head>
<body class="bg-gymove-canvas font-sans text-slate-700 antialiased min-h-screen flex items-center justify-center p-4 selection:bg-gymove-blue selection:text-white relative overflow-hidden">

    <!-- Ambient Decorative Elements -->
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-indigo-200/40 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-amber-200/30 blur-3xl pointer-events-none"></div>

    <div x-data="{ showPass: false, fillDemo(email, pass) { $refs.emailInput.value = email; $refs.passInput.value = pass; } }" class="w-full max-w-md relative z-10">

        <!-- Top Brand Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo EDU PESISIR" class="w-20 h-20 rounded-3xl object-contain p-2 bg-white shadow-xl ring-4 ring-white/80">
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900">
                EDU PESISIR
            </h1>
            <div class="flex items-center justify-center gap-2 mt-1">
                <span class="text-[11px] font-black uppercase tracking-wider text-gymove-blue bg-gymove-soft-blue px-2.5 py-0.5 rounded-full border border-indigo-100">
                    Otentikasi Administrator
                </span>
                <span class="text-[11px] font-bold text-slate-400">Kota Bengkulu</span>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-card shadow-soft-card border border-slate-100 p-8">
            
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">Masuk ke Panel Pengelola</h2>
                <p class="text-xs text-slate-400 mt-1">Gunakan akun terverifikasi untuk mengelola CRUD master data lokasi pesisir, kurikulum mata pelajaran, dan bobot kriteria SPK SAW.</p>
            </div>

            <!-- Validation Errors Alert -->
            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="font-medium">
                    @foreach($errors->all() as $err)
                    <p>{{ $err }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Session Alert -->
            @if(session('info'))
            <div class="mb-5 p-3.5 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-xs flex items-center gap-2.5">
                <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Field -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email Administrator
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input type="email" 
                               name="email" 
                               x-ref="emailInput"
                               value="{{ old('email', 'admin@pesisir-edu.bengkulu.go.id') }}" 
                               required 
                               placeholder="admin@pesisir-edu.bengkulu.go.id"
                               class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gymove-blue transition-all">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi Sistem
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input :type="showPass ? 'text' : 'password'" 
                               name="password" 
                               x-ref="passInput"
                               value="admin123"
                               required 
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gymove-blue transition-all">
                        <button type="button" 
                                @click="showPass = !showPass" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <span class="text-xs" x-text="showPass ? '🙈' : '👁️'"></span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Protection Info -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600 select-none">
                        <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-gymove-blue focus:ring-gymove-blue">
                        <span>Ingat sesi di peramban</span>
                    </label>
                    <span class="text-[11px] text-emerald-600 font-bold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        SSL/CSRF Protected
                    </span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3 rounded-2xl bg-gymove-blue hover:bg-gymove-blue-hover text-white text-xs font-bold tracking-wide transition-all shadow-blue-glow hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2 mt-2">
                    <svg class="w-4 h-4 text-gymove-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Masuk ke Panel Administrator</span>
                </button>
            </form>

            <!-- Quick Preset Credentials Box for Demo & Review -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-slate-700 flex items-center gap-1.5">
                            <span class="text-amber-500">🔑</span> Kredensial Pengujian Administrator:
                        </span>
                        <button type="button" 
                                @click="fillDemo('admin@pesisir-edu.bengkulu.go.id', 'admin123')"
                                class="text-[10px] font-bold text-gymove-blue hover:underline bg-white px-2 py-0.5 rounded-md border border-slate-200">
                            Auto-Fill Form
                        </button>
                    </div>
                    <div class="space-y-1 font-mono text-[11px] text-slate-600">
                        <div>Email: <strong class="text-slate-800">admin@pesisir-edu.bengkulu.go.id</strong></div>
                        <div>Password: <strong class="text-slate-800">admin123</strong></div>
                    </div>
                </div>
            </div>

            @auth
            <!-- Notice if already logged in -->
            <div class="mt-4 p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-center">
                <p class="text-xs text-emerald-800 font-medium">Sesi aktif sebagai: <strong>{{ auth()->user()->name }}</strong></p>
                <div class="mt-2 flex items-center justify-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-3.5 py-1.5 rounded-full transition-all">
                        Buka Dashboard Admin →
                    </a>
                </div>
            </div>
            @endauth

            <!-- Return to Frontend Link -->
            <div class="mt-5 text-center">
                <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-gymove-blue transition-colors inline-flex items-center gap-1.5">
                    <span>← Kembali ke Portal Siswa</span>
                </a>
            </div>

        </div>

        <p class="text-center text-[11px] text-slate-400 mt-6">
            EDU PESISIR Kota Bengkulu • Sistem Rekomendasi Tempat Belajar Edukatif<br>
            Panel Administrator Pengelola Sistem
        </p>

    </div>

</body>
</html>
