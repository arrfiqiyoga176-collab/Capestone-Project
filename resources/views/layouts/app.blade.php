<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PESISIR-EDU | WebGIS & SPK Lokasi Pembelajaran Pesisir Bengkulu')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Tailwind CSS (CDN with Custom Gymove Theme) -->
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
                        'gymove-soft-blue-hover': '#E0E7FF',
                        'gymove-pill': '#F4F5F9',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    borderRadius: {
                        'card': '28px',
                    },
                    boxShadow: {
                        'soft-card': '0 10px 40px rgba(19, 41, 133, 0.05)',
                        'soft-hover': '0 14px 45px rgba(19, 41, 133, 0.08)',
                        'blue-glow': '0 10px 25px rgba(19, 41, 133, 0.28)',
                        'orange-glow': '0 8px 20px rgba(255, 138, 43, 0.35)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Leaflet.js -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F4F7F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        /* Leaflet custom marker animations */
        .leaflet-popup-content-wrapper {
            border-radius: 18px !important;
            box-shadow: 0 10px 30px rgba(19, 41, 133, 0.15) !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            padding: 4px;
        }
        .leaflet-popup-tip {
            box-shadow: 0 10px 30px rgba(19, 41, 133, 0.15) !important;
        }
        .pulse-marker {
            animation: marker-pulse 2s infinite ease-in-out;
        }
        @keyframes marker-pulse {
            0% { transform: scale(0.95); opacity: 0.9; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.9; }
        }
    </style>
</head>
<body class="bg-gymove-canvas font-sans text-slate-700 antialiased min-h-screen selection:bg-gymove-blue selection:text-white">
    @yield('content')

    @stack('scripts')
</body>
</html>
