<x-app-layout>
    <x-slot name="title">
        トップページ
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          トップページ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-lg font-medium">直近の応募者情報変更履歴</p>
                    <div class="mt-6">
                        <table class="mx-auto table-fixed border-collapse border border-blue-400">
                        @forelse($records as $key => $values)
                            <tr>
                                <td class="w-1/4 p-2 text-left border border-blue-400 bg-blue-200" colspan="2">{{ $key }}</td>
                            </tr>
                            @foreach($values as $record)
                            <tr>
                                <td class="p-1">{{ $record->created_at->format('H:i:s') }}</td>
                                <td class="p-1">
                                @if($record->type === 0)
                                    {{ $record->user->name }}さんが、{{ $record->applicant_name }}を登録しました。
                                @elseif($record->type === 1)
                                    {{ $record->user->name }}さんが、{{ $record->applicant_name }}のステータスを更新しました。
                                    {{ \App\Enums\ApplicantStatus::getDescription($record->before_status) }}
                                    ->
                                    {{ \App\Enums\ApplicantStatus::getDescription($record->after_status) }}
                                @elseif($record->type === 2)
                                    {{ $record->user->name }}さんが、{{ $record->applicant_name }}を更新しました。
                                @elseif($record->type === 3)
                                    {{ $record->user->name }}さんが、{{ $record->applicant_name }}を削除しました。
                                @elseif($record->type === 4)
                                    {{ $record->user->name }}さんが、{{$record->applicant_name}}の「{{ $record->filename }}」を削除しました。
                                @endif
                                </td>
                            </tr>
                            @endforeach
                        @empty
                            レコードが存在しません
                        @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
