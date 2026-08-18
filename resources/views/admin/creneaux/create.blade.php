<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un Créneau') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.creneaux.store') }}">
                        @csrf

                        <!-- Date -->
                        <div class="mb-4">
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date')" required autofocus />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <!-- Heure de début -->
                        <div class="mb-4">
                            <x-input-label for="heure_debut" :value="__('Heure de début (ex: 10:00)')" />
                            <x-text-input id="heure_debut" class="block mt-1 w-full" type="time" name="heure_debut" :value="old('heure_debut')" required />
                            <x-input-error :messages="$errors->get('heure_debut')" class="mt-2" />
                        </div>

                        <!-- Durée -->
                        <div class="mb-4">
                            <x-input-label for="duree" :value="__('Durée (en minutes)')" />
                            <x-text-input id="duree" class="block mt-1 w-full" type="number" name="duree" :value="old('duree', 30)" required />
                            <x-input-error :messages="$errors->get('duree')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('admin.creneaux.index') }}">
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button>
                                {{ __('Créer le créneau') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
