<x-app-layout>
    <x-slot name="title">
        求人一覧
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          求人一覧
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

                    <div class="grid grid-cols-8 gap-1">
                        <div class="col-start-1 col-span-2" x-data="{ modalOpen: false }" x-cloak>
                            <button class="ml-12 bg-blue-500 text-sm lg:text-md hover:bg-blue-800 font-semibold text-white py-2 px-4 rounded"
                                    @click="modalOpen = true">検索</button>
                            <!-- 検索フォーム -->
                            <div class="absolute top-0 left-0 w-full min-h-screen flex items-center justify-center" style="background-color: rgba(0,0,0,.5);" x-show="modalOpen" x-cloak>
                                <div class="text-left bg-white h-auto w-1/2 p-8 md:max-w-4x1 md:p-8 lg:p-10 shadow-xl rounded mx-2 md:mx-0" @click.away="modalOpen = false">
                                    <span class="text-2xl">検索条件</span>
                                    <button class="select-none float-right" @click="modalOpen = false"><span>&times;</span></button>
                                    <div>
                                        <form method="get" action="{{ route('reclite.list') }}">
                                            <div>
                                                <x-label for="name" value="求人名" />
                                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name')}}" />
                                            </div>

                                            <div class="mt-4">
                                                <x-label for="status" value="ステータス" />

                                                <select id="status" class="block w-full mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                                name="status">
                                                    <option value="" <?php if(!old('status')) {echo "selected"; } ?>>選択しない</option>
                                                @foreach($status_list as $key => $value)
                                                    <option value="{{ $key }}" <?php if((int)old('status') === $key) {echo "selected";} ?>>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                            </div>

                                            <div class="mt-4">
                                                <x-label for="department" value="部署" />
                                                <select id="department" class="block w-full mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                            name="department" >
                                                    <option value="">選択しない</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" <?php if(old('department') == $department->id) {echo "selected";} ?>>{{ $department->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>

                                            <div class="mt-4">
                                                <x-label for="handler" value="担当者" />
                                                <select id="handler" class="block w-full mt-1 form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                            name="handler" >
                                                    <option value="">選択しない</option>
                                                @foreach($handlers as $handler)
                                                    <option value="{{ $handler->id }}" <?php if(old('handler') == $handler->id) {echo "selected";} ?>>{{ $handler->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>

                                            <div class="mt-4 grid grid-cols-9">
                                                <div class="col-start-1 col-span-2 md:col-span-4">
                                                    <x-label for="date_from" value="更新期間" />
                                                    <x-input id="date_from" class="block mt-1 md:w-4/5" type="datetime-local" name="date_from" value="{{ old('date_from') }}" />
                                                </div>
                                                <div class="col-start-5 pt-7">
                                                    ~
                                                </div>
                                                <div class="col-end-8 col-span-2 pt-5 md:col-span-4">
                                                    <x-label for="date_to"/>
                                                    <x-input id="date_to" class="block mt-1 md:w-4/5" type="datetime-local" name="date_to" value="{{ old('date_to') }}"/>
                                                </div>
                                            </div>

                                            <div class="mt-4 float-right">
                                                <x-button class="ml-4" @click="modalOpen = false">
                                                    検索
                                                </x-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 登録ボタン（管理者のみ） -->
                        <div class="col-end-9 col-span-2">
                        @if(Auth::user()->is_admin === 0)
                            <button class="mr-11 float-right bg-blue-500 text-sm lg:text-md hover:bg-blue-800 font-semibold text-white py-2 px-4 rounded">
                                <a href="{{ route('reclite.new') }}">登録</a>
                            </button>
                        @endif
                        </div>
                    </div>
                    
                    <!-- 求人一覧 -->
                    <div class="mt-6 w-full">
                        <table class="mx-auto table-fixed border-collapse border border-blue-400">
                            <thead>
                                <tr>
                                    <th class="w-1/4 px-5 py-2 border-b border-blue-400 hover:text-blue-500">@sortablelink('name', '求人名')</th>
                                    <th class="w-1/6 px-5 py-2 border-b border-blue-400 hover:text-blue-500">@sortablelink('user_id', '担当者')</th>
                                    <th class="w-1/5 px-5 py-2 border-b border-blue-400 hover:text-blue-500">@sortablelink('department_id', '部署名')</th>
                                    <th class="w-1/5 px-5 y-2 border-b border-blue-400 hover:text-blue-500">@sortablelink('status', 'ステータス')</th>
                                    <th class="w-1/4 px-5 py-2 border-b border-blue-400 hover:text-blue-500">@sortablelink('updated_at', '更新日時')</th>
                                <tr>
                            </thead>
                            <tbody>
                            @forelse($reclites as $reclite)
                                <tr>
                                <td class="pl-10 py-2 text-left sm:text-sm md:text-base max-w-0 truncate"><a href="{{ route('reclite.info', [ 'id' => $reclite->id ]) }}" class="text-blue-500 hover:text-blue-800 hover:underline">{{ $reclite->name }}</a></td>
                                    <td class="pl-5 py-2 text-left sm:text-sm md:text-base">{{ $reclite->user->name }}</td>
                                    <td class="pl-5 py-2 text-left sm:text-sm md:text-base max-w-0 truncate">{{ $reclite->department->name }}</td>
                                    <td class="sm:pl-5 md:pl-12 py-2 text-left sm:text-sm md:text-base max-w-0 truncate">
                                        {{ \App\Enums\RecliteStatus::getDescription($reclite->status) }}
                                    </td>
                                    <td class="px-5 py-2 text-center sm:text-sm md:text-base">{{ $reclite->updated_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        該当する求人はありません
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="grid grid-cols-6">
                        <div class="my-4 ml-2 lg:ml-12 col-span-2 text-sm md:text-lg">
                            表示件数
                            @if($per === 20)
                                <span class="ml-2 text-gray-500">20</span>
                            @elseif($per !== 20)
                                <a href="{{ url()->current(). '?'.http_build_query(array_merge(request()->except(['per']), ['per' => 20, 'page' => 1])) }}" class="ml-2 hover:text-blue-500 hover:underline">20</a>
                            @endif

                            @if($per === 50)
                                <span class="ml-2  text-gray-500">50</span>
                            @elseif($per !== 50)
                                <a href="{{ url()->current(). '?'.http_build_query(array_merge(request()->except(['per']), ['per' => 50, 'page' => 1])) }}" class="ml-2 hover:text-blue-500 hover:underline">50</a>
                            @endif

                            @if($per === 100)
                                <span class="ml-2  text-gray-500">100</span>
                            @elseif($per !== 100)
                                <a href="{{ url()->current(). '?'.http_build_query(array_merge(request()->except(['per']), ['per' => 100, 'page' => 1])) }}" class="ml-2 hover:text-blue-500 hover:underline">100</a>
                            @endif
                        </div>
                        <div class="my-4 col-end-7 col-span-4 lg:col-span-3 lg:col-end-7 ml-auto">
                            {{ $reclites->appends(request()->input())->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
