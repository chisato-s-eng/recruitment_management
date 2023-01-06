<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <div class="text-center">
                <i class="fas fa-user-tie fa-4x"></i>
                <p>新規ユーザー登録</p>
            </div>
        </x-slot>

        @if (session('success')) 
            <div class="bg-green-500 rounded-lg m-2" x-data="{ open: true }">
                <p class="text-white text-xl p-1" x-show="open">{{ session('success') }} <button class="select-none float-right" @click="open = false"><span>&times;</span></button></p>
            </div>
        @endif

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <div x-data="selectNo()">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-label for="name" value="ユーザー名" />

                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-label for="email" value="メールアドレス" />

                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-label for="password" value="パスワード" />

                    <x-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-label for="password_confirmation" value="パスワード（確認）" />

                    <x-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required />
                </div>

                <!-- Set is_admin -->
                <div>
                    <div class="mt-4">
                        <x-label for="is_admin" value="ユーザー種類"/>

                        <select id="is_admin" class="block w-full mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="is_admin" x-on:click="open()" required>
                            <option value="0">管理者</option>
                            <option value="1">一般利用者(閲覧のみ)</option>
                        </select>
                    </div>

                    <div class="mt-4" x-show="isShow">
                        <x-label for="permission" value="権限" />

                        <select id="permission" class="block w-full mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="permission[]" multiple>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                        </select>
                    </div>   
                </div>      

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        戻る
                    </a>

                    <x-button class="ml-4">
                        登録
                    </x-button>
                </div>
            </form>
        </div>
    </x-auth-card>

    <x-slot name="script">
        <script>
            function selectNo() {
                return {
                    isShow: false,
                    open() {
                        if (document.getElementById("is_admin").value == 1) {
                            this.isShow = true;
                        } else {
                            this.isShow = false;
                        }
                    },
                }
            }
        </script>
    </x-slot>
</x-guest-layout>
