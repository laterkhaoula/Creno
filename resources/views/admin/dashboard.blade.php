<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-6">Tous les rendez-vous</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Client</th>
                                    <th scope="col" class="px-6 py-3">Créneau</th>
                                    <th scope="col" class="px-6 py-3">Statut actuel</th>
                                    <th scope="col" class="px-6 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rendezVousList as $rv)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $rv->user->name }}<br>
                                            <span class="text-xs text-gray-500">{{ $rv->user->email }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            Le {{ $rv->creneau->date->format('d/m/Y') }} à {{ $rv->creneau->heure_debut }}<br>
                                            <span class="text-xs text-gray-500">Durée: {{ $rv->creneau->duree }} min</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($rv->statut === 'en_attente')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">En attente</span>
                                            @elseif($rv->statut === 'confirme')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Confirmé</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Annulé</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('admin.rendez-vous.update-status', $rv) }}" method="POST" class="flex justify-end gap-2 items-center">
                                                @csrf
                                                @method('PATCH')
                                                <select name="statut" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                                                    <option value="en_attente" {{ $rv->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                    <option value="confirme" {{ $rv->statut === 'confirme' ? 'selected' : '' }}>Confirmé</option>
                                                    <option value="annule" {{ $rv->statut === 'annule' ? 'selected' : '' }}>Annulé</option>
                                                </select>
                                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">OK</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center">Aucun rendez-vous trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $rendezVousList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
