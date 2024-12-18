<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Please enter the OTP code that was sent to your email.') }}
        <p class="mt-2">{{ __('Email: ') }} {{ session('auth_email') }}</p>
    </div>

    <form method="POST" action="{{ route('login.otp.verify') }}">
        @csrf
        <div>
            <x-input-label for="otp" :value="__('OTP Code')" />
            <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" required autofocus
                pattern="[0-9]*" inputmode="numeric" minlength="6" maxlength="6" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                {{ __('Back to login') }}
            </a>
            <x-primary-button class="ml-3">
                {{ __('Verify & Login') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
