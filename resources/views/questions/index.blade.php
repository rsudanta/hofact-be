<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form class="form" method="get" action="{{ route('search_question') }}">
                <div class="form-group w-auto mb-3">
                    <label for="search" class="d-block mr-2">Pencarian</label>
                    <input type="text" name="search" class="form-control w-auto d-inline rounded" id="search"
                        placeholder="Masukkan keyword">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Cari</button>
                </div>
            </form>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="bg-white">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">ID</th>
                            <th class="border px-6 py-4">Judul Pertanyaan</th>
                            <th class="border px-6 py-4">Isi Pertanyaan</th>
                            <th class="border px-6 py-4">Penulis</th>
                            <th class="border px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($questions as $question )
                            <tr>
                                <td class="border px-6 py-4">{{ $question->id }}</td>
                                <td class="border px-6 py-4">{{ $question->judul_pertanyaan }}</td>
                                <td class="border px-6 py-4 " style=" max-width: 500px;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                white-space: nowrap;">
                                    {{ $question->isi_pertanyaan }} </td>
                                <td class="border px-6 py-4">{{ $question->user->name }}</td>
                                <td class="border px-6 py-4 text-center">
                                    <a href="{{ route('questions.edit', $question->id) }}"
                                        class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                        Detail</a>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                        class="inline-block">
                                        {!! method_field('delete') . csrf_field() !!}
                                        <button type="submit"
                                            class=" inline-block bg-red-500
                                            hover:bg-red-700 text-white font-bold py-2 px-4 mx-2
                                            rounded">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border text-center p-5">Data kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-5">
                {{ $questions->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
