<x-app-layout>
    <x-slot name="title">
        求人情報編集
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          求人情報編集
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
                        <form method="post" action="{{ route('reclite.update', ['id' => $reclite->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-2">
                                <div class="mt-4">
                                    <label for="name" class="block font-medium text-sm text-gray-700">
                                        氏名<span class="text-red-500 text-xs">（必須）</span>
                                    </label>
                                    <x-input id="name" class="block mt-1 w-9/12" type="text" name="name" value="{{ $reclite->name }}" required/>
                                </div>

                                <div class="mt-4">
                                    <label for="name" class="block font-medium text-sm text-gray-700">
                                        選考部署<span class="text-red-500 text-xs">（必須）</span>
                                    </label>
                                    <select id="department" class="block w-9/12 mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                name="department" required>
                                    @foreach($departments as $department)
                                        @if ($department->id === $reclite->department_id)
                                            <option value="{{ $department->id }}" selected>{{ $department->name }}</option>
                                        @else
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endif
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
                                        @if ($handler->id === $reclite->reclite_id)
                                            <option value="{{ $handler->id }}">{{ $handler->name }}</option>
                                        @else
                                            <option value="{{ $handler->id }}">{{ $handler->name }}</option>
                                        @endif
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
                                        <option value="{{ $key }}" <?php if($reclite->status === $key) {echo "selected";} ?>>{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-label for="memo" value="備考" />
                                <textarea id="memo" class="block w-full mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                        name="memo">{{ $reclite->memo }}</textarea>
                            </div> 
                            <div class="grid grid-cols-7">
                                <div class="mt-4 col-end-8 lg:col-span-1 col-span-2 text-right lg:col-end-8">
                                    <x-button class="bg-blue-400">
                                        更新
                                    </x-button>
                                    <a href="{{ route('reclite.info', ['id' => $reclite->id]) }}" class="bg-gray-600 inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        キャンセル
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
