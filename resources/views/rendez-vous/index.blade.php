<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes rendez-vous') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Historique de vos rendez-vous</h3>
                        <a href="{{ route('creneaux.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nouveau rendez-vous
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($rendezVousList as $rv)
                            <div class="bg-white rounded-lg border border-gray-200 shadow-md p-6">
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">
                                    Le {{ $rv->creneau->date->format('d/m/Y') }}
                                </h5>
                                <p class="mb-3 font-normal text-gray-700">
                                    Heure : {{ $rv->creneau->heure_debut }} <br>
                                    Durée : {{ $rv->creneau->duree }} min
                                </p>
                                
                                <div class="mb-4">
                                    @if($rv->statut === 'en_attente')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">En attente</span>
                                    @elseif($rv->statut === 'confirme')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Confirmé</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Annulé</span>
                                    @endif
                                </div>

                                @if($rv->statut !== 'annule')
                                    <form action="{{ route('rendez-vous.destroy', $rv) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                                            Annuler le rendez-vous
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8 text-gray-500">
                                Vous n'avez aucun rendez-vous pour le moment.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
