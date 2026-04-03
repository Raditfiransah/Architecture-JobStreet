<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">{{ __('Pilih Role') }}</option>
                <option value="arsitek" {{ old('role') == 'arsitek' ? 'selected' : '' }}>{{ __('Arsitek') }}</option>
                <option value="perusahaan" {{ old('role') == 'perusahaan' ? 'selected' : '' }}>{{ __('Perusahaan') }}</option>
                <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>{{ __('Client') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div id="companyFields" class="hidden">
            <div class="mt-4">
                <x-input-label for="company_name" :value="__('Company Name')" />
                <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" autocomplete="organization" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="company_website" :value="__('Company Website')" />
                <x-text-input id="company_website" class="block mt-1 w-full" type="url" name="company_website" :value="old('company_website')" placeholder="https://example.com" autocomplete="url" />
                <x-input-error :messages="$errors->get('company_website')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <label for="agree_to_terms" class="flex items-center">
                <input id="agree_to_terms" name="agree_to_terms" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" required>
                <span class="ms-2 text-sm text-gray-600">{{ __('I agree to the Terms of Service') }}</span>
            </label>
            <x-input-error :messages="$errors->get('agree_to_terms')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        const roleSelect = document.getElementById('role');
        const companyFields = document.getElementById('companyFields');

        function toggleCompanyFields() {
            if (roleSelect.value === 'perusahaan') {
                companyFields.classList.remove('hidden');
            } else {
                companyFields.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleCompanyFields);
        toggleCompanyFields();
    </script>
</x-guest-layout>
