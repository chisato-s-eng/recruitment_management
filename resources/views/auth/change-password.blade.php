<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <div class="text-center">
                <i class="fas fa-user-tie fa-4x"></i>
                <h1>パスワード変更</h1>
            </div>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        @if (session('success')) 
            <div class="bg-green-500 rounded-lg m-2" x-data="{ open: true }">
                <p class="text-white text-xl p-1" x-show="open">{{ session('success') }} <button class="select-none float-right" @click="open = false"><span>&times;</span></button></p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}">
            @csrf

            <!-- Current Password -->
            <div>
                <x-label for="current-password" value="現在のパスワード" />

                <x-input id="current-password" class="block mt-1 w-full" type="password" name="current_password" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" value="新しいパスワード" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="new_password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" value="新しいパスワード（確認）" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="new_password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 mr-4" href="{{ route('top') }}">
                    戻る
                </a>

                <x-button>
                    パスワード変更
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
