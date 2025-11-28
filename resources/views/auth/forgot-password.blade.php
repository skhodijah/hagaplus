<x-guest-layout>
    <a href="{{ route('login') }}" class="absolute top-8 left-8 text-gray-500 hover:text-[#008159] transition-colors flex items-center gap-2 group">
        <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-medium">Kembali</span>
    </a>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password</h2>
        <p class="text-gray-600">Masukkan email Anda untuk menerima tautan reset password.</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf
        <div>
            <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700 mb-1" />
            <div class="relative rounded-xl shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-regular fa-envelope text-gray-400 text-lg"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0EC774]/50 focus:border-[#0EC774] bg-gray-50 focus:bg-white" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@perusahaan.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-sm font-medium text-[#008159] hover:text-[#0EC774] transition-colors">Kembali ke Login</a>
            <x-primary-button class="ml-4">
                {{ __('Kirim Link Reset') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
