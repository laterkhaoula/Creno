<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Créneaux') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Liste des créneaux</h3>
                        <a href="{{ route('admin.creneaux.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ajouter un créneau
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

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Heure début</th>
                                    <th scope="col" class="px-6 py-3">Durée</th>
                                    <th scope="col" class="px-6 py-3">Rendez-vous liés</th>
                                    <th scope="col" class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($creneaux as $creneau)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4">{{ $creneau->date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $creneau->heure_debut }}</td>
                                        <td class="px-6 py-4">{{ $creneau->duree }} min</td>
                                        <td class="px-6 py-4">
                                            {{ $creneau->rendezVous()->where('statut', '!=', 'annule')->count() }}
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                                            <a href="{{ route('admin.creneaux.edit', $creneau) }}" class="font-medium text-blue-600 hover:underline">Modifier</a>
                                            <form action="{{ route('admin.creneaux.destroy', $creneau) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce créneau ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">Aucun créneau trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $creneaux->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
