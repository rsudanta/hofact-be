<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('questions.index') }}">
                {{ __('Post') }}
            </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <h1 class="font-bold text-xl text-center border">Pertanyaan</h1>
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">ID</th>
                            <th class="border px-6 py-4">Judul Pertanyaan</th>
                            <th class="border px-6 py-4">Isi Pertanyaan</th>
                            <th class="border px-6 py-4">Penulis</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td class="border px-6 py-4">{{ $question->id }}</td>
                            <td class="border px-6 py-4">{{ $question->judul_pertanyaan }}</td>
                            <td class="border px-6 py-4">{{ $question->isi_pertanyaan }}</td>
                            <td class="border px-6 py-4">{{ $question->user->name }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="bg-white mt-5">
                <h1 class="font-bold text-xl text-center border">Jawaban</h1>
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">Penulis</th>
                            <th class="border px-6 py-4">Isi Jawaban</th>
                            <th class="border px-6 py-4">Status</th>
                            <th class="border px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($answers as $answer )
                            <tr>
                                <td class="border px-6 py-4">{{ $answer->user->name }}</td>
                                <td class="border px-6 py-4">{{ $answer->isi_jawaban }}</td>
                                <td class="border px-6 py-4">
                                    {{ $answer->is_terverifikasi == 0 ? 'Belum terverifikasi' : 'Terverifikasi' }}
                                </td>
                                <td class="border px-6 py-4 text-center">
                                    @if ($answer->is_terverifikasi == '0')
                                        <form action="{{ route('verified', $answer->id) }}" method="POST"
                                            class="inline-block mb-5">
                                            {!! method_field('post') . csrf_field() !!}
                                            <button type="submit"
                                                class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                                Terverivikasi</button>
                                        </form>
                                    @endif


                                    @if ($answer->is_terverifikasi == '1')
                                        <form action="{{ route('notVerified', $answer->id) }}" method="POST"
                                            class="inline-block mb-5">
                                            {!! method_field('post') . csrf_field() !!}
                                            <button type="submit"
                                                class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                                Batal verifikasi</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('answers.destroy', $answer->id) }}" method="POST"
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
        </div>
    </div>
</x-app-layout>
