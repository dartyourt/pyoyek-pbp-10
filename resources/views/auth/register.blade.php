<x-guest-layout>
    <!-- Welcome Message -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create Your Account</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">Join us and start shopping.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <div class="relative">
                <x-text-input id="name" class="block mt-1 w-full pr-10" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative">
                <x-text-input id="email" class="block mt-1 w-full pr-10" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
