<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Staf - SIREKOWIS Kota Banjarbaru</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-kota-banjarbaru-transparan.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center w-16 h-16 rounded-2xl shadow-xl shadow-emerald-500/20 mb-4 hover:scale-105 transition-transform overflow-hidden">
                <img src="{{ asset('images/logo-kota-banjarbaru-transparan.png') }}" alt="Lambang Kota Banjarbaru" class="w-12 h-12 object-contain">
            </a>
            <h2 class="text-2xl font-black text-white tracking-tight">SIREKOWIS BANJARBARU</h2>
            <p class="text-xs text-slate-400 mt-1">Portal Autentikasi Pegawai & Operator DISPORABUDPAR</p>
        </div>

        <!-- Login Container -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-slate-200/80">
            {{ $slot }}
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-emerald-400 transition-colors flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Publik
            </a>
        </div>
    </div>
</body>
</html>
