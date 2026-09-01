<x-guest-layout>
    <x-auth-session-status class="mb-6" :status="session('status')" />
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200">
        <div class="text-center mb-7">
            <h2 class="text-2xl font-bold tracking-tight">Selamat Datang Kembali</h2>
            <p class="text-slate-500 text-sm mt-1">Masuk untuk mengakses dashboard</p>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <div class="relative mt-1">
                    <i class="ti ti-mail absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <x-text-input id="email" class="block w-full pl-10 pr-4 py-2.5" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mt-5">
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative mt-1">
                    <i class="ti ti-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <x-text-input id="password" class="block w-full pl-10 pr-4 py-2.5" type="password" name="password" required autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="flex items-center justify-between mt-5">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent-soft">
                    <span class="ml-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-accent hover:underline" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                @endif
            </div>
            <x-primary-button class="w-full mt-6 py-2.5 justify-center" type="submit">{{ __('Log in') }}</x-primary-button>
        </form>
        <div class="mt-8 pt-6 border-t border-slate-200">
            <p class="text-xs text-slate-500 text-center mb-3">Akun Demo (dari Seeder)</p>
            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between bg-slate-50 rounded-lg px-3 py-1.5"><span>Admin</span><code class="font-mono">admin@sekolah.test</code></div>
                <div class="flex justify-between bg-slate-50 rounded-lg px-3 py-1.5"><span>Guru</span><code class="font-mono">budi@sekolah.test</code></div>
                <div class="flex justify-between bg-slate-50 rounded-lg px-3 py-1.5"><span>Siswa</span><code class="font-mono">siswa1001@sekolah.test</code></div>
                <p class="text-center text-slate-400 text-xs mt-2">Password: <code class="font-mono">password</code></p>
            </div>
        </div>
    </div>
</x-guest-layout>
