<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Email verifikasi telah dikirim ke :email. Silakan masukkan kode 6 digit yang Anda terima.', ['email' => session('otp_email', session('email', ''))]) }}
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.verify') }}">
        @csrf

        <div>
            <x-input-label for="code" :value="__('Kode Verifikasi')" />
            <x-text-input id="code" class="block mt-1 w-full text-center text-2xl tracking-widest" type="text"
                name="code" maxlength="6" inputmode="numeric" pattern="[0-9]*" required autofocus
                autocomplete="one-time-code" placeholder="000000" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <x-primary-button>
                {{ __('Verifikasi') }}
            </x-primary-button>

            <form method="POST" action="{{ route('resend.otp') }}">
                @csrf
                <x-secondary-button type="submit" id="resendBtn">
                    {{ __('Kirim Ulang Kode') }}
                </x-secondary-button>
            </form>
        </div>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
            {{ __('Kembali ke Login') }}
        </a>
    </div>
</x-guest-layout>
