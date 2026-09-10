<x-guest-layout>
<form method="POST" action="{{ route('register') }}">@csrf
<div><x-input-label for="name" value="Name"/><x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus/><x-input-error :messages="$errors->get('name')"/></div>
<div class="mt-4"><x-input-label for="email" value="Email"/><x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required/><x-input-error :messages="$errors->get('email')"/></div>
<div class="mt-4"><x-input-label for="phone" value="Phone"/><x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')"/><x-input-error :messages="$errors->get('phone')"/></div>
<div class="mt-4"><x-input-label for="role" value="Account type"/><select id="role" name="role" required class="block mt-1 w-full rounded-md border-gray-300"><option value="customer">Customer</option><option value="provider">Service Provider</option></select><x-input-error :messages="$errors->get('role')"/></div>
<div class="mt-4"><x-input-label for="password" value="Password"/><x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required/><x-input-error :messages="$errors->get('password')"/></div>
<div class="mt-4"><x-input-label for="password_confirmation" value="Confirm Password"/><x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required/></div>
<div class="flex items-center justify-end mt-4"><a class="underline text-sm text-gray-600" href="{{ route('login') }}">Already registered?</a><x-primary-button class="ms-4">Register</x-primary-button></div>
</form></x-guest-layout>
