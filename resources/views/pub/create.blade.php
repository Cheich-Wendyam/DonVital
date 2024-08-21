<x-guest-layout>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <h2>Ajouter une Publicité</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('pub.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <x-input-label for="Libelle" :value="__('Libelle de la publicité')" />
            <x-text-input id="Libelle" class="block mt-1 w-full" type="text" name="libelle" :value="old('libelle')" required autofocus autocomplete="libelle" />
            <x-input-error :messages="$errors->get('libelle')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="contenu" :value="__('Contenu')" />
            <textarea name="contenu" id="contenu" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
            <x-input-error :messages="$errors->get('contenu')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="image" :value="__('Image')" />
            <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" accept="image/*" />
            <x-input-error :messages="$errors->get('image')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-primary-button>
                {{ __('Ajouter') }}
            </x-primary-button>
        </div>
    </form>
</div>

</x-guest-layout>
