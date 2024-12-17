<?php
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state([
    'name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => '',
    'alamat' => '',
    'tempatLahir' => '',
    'jenisKelamin' => '',
    'tanggalLahir' => null, // Ubah ke null
    'noTelp' => '',
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
    'alamat' => ['nullable', 'string', 'max:255'],
    'tempatLahir' => ['nullable', 'string', 'max:100'],
    'jenisKelamin' => ['nullable', 'in:L,P'],
    'tanggalLahir' => ['nullable', 'date'], 
    'noTelp' => ['nullable', 'string', 'max:20'],
]);

$register = function () {
    $validated = $this->validate();

    if (empty($validated['tanggalLahir'])) {
        unset($validated['tanggalLahir']);
    }

    // Tentukan role berdasarkan email
    $validated['role'] = $validated['email'] === 'admin@admin.com' ? 'admin' : 'user';

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect(RouteServiceProvider::HOME, navigate: true);
};
?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Alamat -->
        <div class="mt-4">
            <x-input-label for="alamat" :value="__('Alamat')" />
            <x-text-input wire:model="alamat" id="alamat" class="block mt-1 w-full" type="text" name="alamat" />
            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
        </div>

        <!-- Tempat Lahir -->
        <div class="mt-4">
            <x-input-label for="tempatLahir" :value="__('Tempat Lahir')" />
            <x-text-input wire:model="tempatLahir" id="tempatLahir" class="block mt-1 w-full" type="text" name="tempatLahir" />
            <x-input-error :messages="$errors->get('tempatLahir')" class="mt-2" />
        </div>

        <!-- Jenis Kelamin -->
        <div class="mt-4">
            <x-input-label for="jenisKelamin" :value="__('Jenis Kelamin')" />
            <select wire:model="jenisKelamin" id="jenisKelamin" name="jenisKelamin" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
            <x-input-error :messages="$errors->get('jenisKelamin')" class="mt-2" />
        </div>

        <!-- Tanggal Lahir -->
        <div class="mt-4">
            <x-input-label for="tanggalLahir" :value="__('Tanggal Lahir')" />
            <x-text-input wire:model="tanggalLahir" id="tanggalLahir" class="block mt-1 w-full" type="date" name="tanggalLahir" />
            <x-input-error :messages="$errors->get('tanggalLahir')" class="mt-2" />
        </div>

        <!-- No Telepon -->
        <div class="mt-4">
            <x-input-label for="noTelp" :value="__('No Telepon')" />
            <x-text-input wire:model="noTelp" id="noTelp" class="block mt-1 w-full" type="tel" name="noTelp" />
            <x-input-error :messages="$errors->get('noTelp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>