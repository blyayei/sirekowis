<x-guest-layout>
    <!-- Demo Credentials Notice -->
    <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-900 text-xs">
        <p class="font-bold flex items-center gap-1.5 mb-1 text-emerald-800">
            <i class="fa-solid fa-key text-emerald-600"></i> Akun Demo DISPORABUDPAR:
        </p>
        <p class="text-[11px] text-slate-600">Email: <code class="font-bold text-slate-800">admin@sirekowis.id</code></p>
        <p class="text-[11px] text-slate-600">Password: <code class="font-bold text-slate-800">password</code></p>
        <button type="button" onclick="document.getElementById('email').value='admin@sirekowis.id'; document.getElementById('password').value='password';" class="mt-2 text-[10px] font-bold text-emerald-700 hover:underline">
            &rarr; Klik untuk mengisi otomatis
        </button>
    </div>

    @if ($errors->any())
        <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold space-y-1">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-sm"></i>
                <span>Gagal Masuk:</span>
            </div>
            <ul class="list-disc pl-5 font-normal text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Staf *</label>
            <input id="email" type="email" name="email" value="{{ old('email', 'admin@sirekowis.id') }}" required autofocus autocomplete="username" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi *</label>
            <input id="password" type="password" name="password" value="password" required autocomplete="current-password" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300" name="remember" checked>
                <span class="ms-2 text-xs text-slate-600">Ingat sesi saya</span>
            </label>
        </div>

        <div class="pt-3">
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-lg shadow-emerald-600/25 transition-all">
                Masuk ke Panel Admin
            </button>
        </div>
    </form>
</x-guest-layout>
