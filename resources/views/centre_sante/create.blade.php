<x-guest-layout>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <h2>Ajouter un Centre de Santé</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('centre_sante.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <x-input-label for="nom" :value="__('Nom du Centre de Santé')" />
            <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom')" required autofocus autocomplete="nom" />
            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="localisation" :value="__('Localisation')" />
            <x-text-input id="localisation" class="block mt-1 w-full" type="text" name="localisation" :value="old('localisation')" required autocomplete="localisation" />
            <x-input-error :messages="$errors->get('localisation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="image" :value="__('Image')" />
            <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" accept="image/*" />
            <x-input-error :messages="$errors->get('image')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-primary-button>
                {{ __('Ajouter') }}
            </x-primary-button>
        </div>
    </form>
</div>

</x-guest-layout>
