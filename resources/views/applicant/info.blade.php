<x-app-layout>
    <x-slot name="title">
        応募者情報詳細
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          応募者詳細
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
                    <div>
                        <label for="name" class="block font-medium text-sm text-gray-700">
                            氏名
                        </label>
                        <p class="ml-4">{{ $applicant->name }}<p>
                    </div>
                    <div class="mt-4">
                        <x-label for="address" value="住所" />
                        <p class="ml-4">{{ $applicant->address }}</p>
                    </div>
                    <div class="mt-4">
                        <x-label for="email" value="メールアドレス" />
                        <p class="ml-4">{{ $applicant->email }}</p>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">
                                電話番号(携帯)
                            </label>
                            <p class="ml-4">{{ $applicant->mobile_phone }}</p>
                        </div>
                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">
                                電話番号(自宅)
                            </label>
                            <p class="ml-4">{{ $applicant->home_phone }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2">
                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">
                                選考部署
                            </label>
                            <p class="ml-4">{{ $applicant->department->name }}</p>
                        </div>

                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">
                                募集ポジション
                            </label>
                            <p class="ml-4">{{ $applicant->reclite->name }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2">
                        <div class="mt-4">
                            <label for="handler" class="block font-medium text-sm text-gray-700">
                                担当者
                            </label>
                            <p class="ml-4">{{ $applicant->user->name }}</p>
                        </div>

                        <div class="mt-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">
                                ステータス
                            </label>
                            <p class="ml-4">
                                {{ \App\Enums\ApplicantStatus::getDescription($applicant->status) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-label for="file" value="添付ファイル" />
                        @forelse($files as $file)
                        <div class="mt-2 ml-2 grid grid-cols-12">
                            <span class="p-1 col-span-4">{{ $file->filename }}.pdf</span>
                            <button class="p-1 bg-green-300 hover:bg-green-500 text-white text-xs rounded select-none">
                                <a href="{{ route('applicant.file.download', ['id' => $file->applicant_id, 'file_id' => $file->id, 'filename' => $file->filename]) }}" target="_blank">Show</a>
                            </button><br>

                            @if(Auth::user()->is_admin === 0)
                            <div x-data="{fileDel_{{$file->id}} : false}">
                                <button class="bg-red-500 hover:bg-red-800 font-semibold text-white text-xs p-2 rounded select-none"
                                @click="fileDel_{{$file->id}} = true">削除</button>
                                
                                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center" style="background-color: rgba(0,0,0,.5);" x-show="fileDel_{{$file->id}}" x-cloak>
                                    <div class="text-center bg-white h-auto w-1/2 lg:w-1/4 p-8 md:max-w-2x1 md:p-8 lg:p-10 shadow-xl rounded mx-2 md:mx-0" @click.away="fileDel_{{$file->id}} = false">
                                        <p>以下のファイルを本当に削除してもよろしいですか？</p>
                                        <p>{{ $file->filename }}</p>
                                        <div class="grid grid-col-2">
                                            <form method="post" action="{{ route('applicant.filedelete', ['id'=> $applicant->id, 'file_id' => $file->id]) }}" class="col-start-1">
                                                @csrf
                                                <button type="submit" class="mt-4 bg-red-500 hover:bg-red-800 font-semibold text-white py-2 px-4 rounded select-none" @click="fileDel_{{$file->id}} = false">
                                                    削除
                                                </button>
                                            </form>
                                            <button class="col-end-3 w-1/2 mt-4 ml-12 bg-blue-500 hover:bg-blue-800 font-semibold text-white text-xs lg:text-md py-2 px-4 rounded select-none" @click="fileDel_{{$file->id}} = false">
                                                キャンセル
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @empty
                            特になし
                        @endforelse
                    </div>

                    <div class="mt-4">
                        <x-label for="memo" value="備考" />
                        <p class="ml-4">{{ $applicant->memo }}</p>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-11">
                        @if(Auth::user()->is_admin === 0)
                            <div class="col-start-1" x-data="{delVerify: false}">
                                <button class="bg-red-500 hover:bg-red-800 font-semibold text-white text-sm py-2 px-4 rounded select-none"
                                @click="delVerify = true">削除</button>
                                
                                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center" style="background-color: rgba(0,0,0,.5);" x-show="delVerify" x-cloak>
                                    <div class="text-center bg-white h-auto w-1/2 lg:w-1/4 p-8 md:max-w-2x1 md:p-8 lg:p-10 shadow-xl rounded mx-2 md:mx-0" @click.away="delVerify = false">
                                        <p>本当に削除してもよろしいですか？</p>
                                        <div class="grid grid-col-2">
                                            <form method="post" action="{{ route('applicant.delete', ['id'=> $applicant->id]) }}" class="col-start-1">
                                                @csrf
                                                <button type="submit" class="mt-4 bg-red-500 hover:bg-red-800 font-semibold text-white py-2 px-4 rounded select-none" @click="delVerify = false">
                                                    削除
                                                </button>
                                            </form>
                                            <button class="col-end-3 w-1/2 mt-4 ml-12 bg-blue-500 hover:bg-blue-800 font-semibold text-white text-xs lg:text-md py-2 px-4 rounded select-none" @click="delVerify = false">
                                                キャンセル
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-end-11 text-right">
                                <x-button class="bg-blue-400">
                                    <a href="{{ route('applicant.edit', ['id' => $applicant->id ]) }}">編集</a>
                                </x-button>
                            </div>
                        @endif
                        <div class="col-end-12 text-right">
                            <x-button class="bg-gray-300">
                                <a href="{{ route('applicant.list') }}">戻る</a>
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
