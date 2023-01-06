<x-app-layout>
    <x-slot name="title">
        求人情報追加
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          求人情報追加
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
                    <div>
                        <form method="post" action="{{ route('reclite.create') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-2">
                                <div class="mt-4">
                                    <label for="name" class="block font-medium text-sm text-gray-700">
                                        求人名<span class="text-red-500 text-xs">（必須）</span>
                                    </label>
                                    <x-input id="name" class="block mt-1 w-9/12" type="text" name="name" required/>
                                </div>

                                <div class="mt-4">
                                    <label for="name" class="block font-medium text-sm text-gray-700">
                                        部署名<span class="text-red-500 text-xs">（必須）</span>
                                    </label>
                                    <select id="department" class="block w-9/12 mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                name="department" required>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2">
                                <div class="mt-4">
                                    <label for="handler" class="block font-medium text-sm text-gray-700">
                                        担当者<span class="text-red-500 text-xs">（必須）</span>
                                    </label>
                                    <select id="handler" class="block w-9/12 mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                name="handler" >
                                    @foreach($handlers as $handler)
                                        <option value="{{ $handler->id }}">{{ $handler->name }}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <label for="name" class="block font-medium text-sm text-gray-700">
                                        ステータス<span class="text-red-500 text-xs">（必須）</span>
                                    </label>
                                    <select id="status" class="block w-9/12 mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    name="status"  required>
                                    @foreach($status_list as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-label for="memo" value="備考" />
                                <textarea id="memo" class="block w-full mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="memo"></textarea>
                            </div>
                            
                            <div class="mt-4 text-right">
                                <x-button class="bg-blue-400">
                                    登録
                                </x-button>
                                <x-button class="bg-gray-300">
                                    <a href="{{ route('reclite.list') }}">キャンセル</a>
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
