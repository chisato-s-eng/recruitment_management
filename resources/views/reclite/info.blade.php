<x-app-layout>
    <x-slot name="title">
        求人情報詳細
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          求人情報詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                @if ($errors->any())
                    <div class="bg-red-500 rounded-lg m-2">
                        <div x-data="{ open: true }">
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li class="text-white text-xl p-1" x-show="open"><button class="select-none float-right" @click="open = false"><span>&times;</span></button> {{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if (session('success')) 
                    <div class="bg-green-500 rounded-lg m-2" x-data="{ open: true }">
                        <p class="text-white text-xl p-1" x-show="open">{{ session('success') }} <button class="select-none float-right" @click="open = false"><span>&times;</span></button></p>
                    </div>
                @endif
                    <div class="grid grid-cols-2">
                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">
                                氏名
                            </label>
                            <p class="ml-4">{{ $reclite->name }}<p>
                        </div>

                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">
                                選考部署
                            </label>
                            <p class="ml-4">{{ $reclite->department->name }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2">
                        <div class="mt-4">
                            <label for="handler" class="block font-medium text-sm text-gray-700">
                                担当者
                            </label>
                            <p class="ml-4">{{ $reclite->user->name }}</p>
                        </div>

                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">
                                ステータス
                            </label>
                            <p class="ml-4">
                                {{ \App\Enums\RecliteStatus::getDescription($reclite->status) }}
                            </p>
                        </div>
                    </div>


                    <div class="mt-4">
                        <x-label for="memo" value="備考" />
                        <p class="ml-4">{{ $reclite->memo }}</p>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-11">
                        @if(Auth::user()->is_admin === 0)
                            <div class="col-start-1" x-data="{delVerify: false}">
                                <button class="bg-red-500 hover:bg-red-800 font-semibold text-white text-sm py-2 px-4 rounded select-none"
                                @click="delVerify = true">削除</button>
                                
                                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center" style="background-color: rgba(0,0,0,.5);" x-show="delVerify" x-cloak>
                                    <div class="text-center bg-white h-auto lg:w-1/4 w-1/2 p-8 md:max-w-2x1 md:p-8 lg:p-10 shadow-xl rounded mx-2 md:mx-0" @click.away="delVerify = false">
                                        <p>本当に削除してもよろしいですか？</p>
                                        <div class="grid grid-col-2">
                                            <form method="post" action="{{ route('reclite.delete', ['id'=> $reclite->id]) }}" class="col-start-1">
                                                @csrf
                                                <button type="submit" class="mt-4 bg-red-500 hover:bg-red-800 font-semibold text-white py-2 px-4 rounded select-none" @click="delVerify = false">
                                                    削除
                                                </button>
                                            </form>
                                            <button class="col-end-3 w-1/2 mt-4 ml-12 bg-blue-500 hover:bg-blue-800 font-semibold text-white text-sm lg:text-base py-2 px-4 rounded select-none" @click="delVerify = false">
                                                キャンセル
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-end-11 text-right">
                                <x-button class="bg-blue-400">
                                    <a href="{{ route('reclite.edit', ['id' => $reclite->id ]) }}">編集</a>
                                </x-button>
                            </div>
                        @endif
                        <div class="col-end-12 text-right">
                            <x-button class="bg-gray-300">
                                <a href="{{ route('reclite.list') }}">戻る</a>
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
