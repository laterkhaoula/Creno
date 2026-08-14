<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créneaux disponibles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if ($creneaux->isEmpty())

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Aucun créneau disponible pour le moment.
                    </div>
                </div>

            @else

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($creneaux as $creneau)

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                            <div class="p-6">

                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Créneau disponible
                                </h3>

                                <p class="text-gray-600 mb-2">
                                    <strong>Date :</strong>
                                    {{ $creneau->date }}
                                </p>

                                <p class="text-gray-600 mb-4">
                                    <strong>Horaire :</strong>
                                    {{ $creneau->heure_debut }}
                                    -
                                    {{ $creneau->heure_fin }}
                                </p>

                                <form method="POST"
                                      action="{{ route('rendez-vous.store') }}">

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="creneau_id"
                                        value="{{ $creneau->id }}"
                                    >

                                    <button
                                        type="submit"
                                        class="w-full px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                                    >
                                        Réserver
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>
    </div>

</x-app-layout>