<x-guest-layout>
    <style>
        .blood-type.selected {
    background-color: #DD0B0B; /* Couleur de fond pour l'élément sélectionné */
    border-color: #E7E6EE; /* Couleur de la bordure pour l'élément sélectionné */
    color: white; /* Couleur du texte pour l'élément sélectionné */
}

    </style>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        {{-- Étape 1 --}}
        <div id="step-1" class="step">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            

            <div class="flex items-center justify-end mt-4">
                <x-primary-button type="button" class="next">
                    {{ __('Next') }}
                </x-primary-button>
            </div>
        </div>

        {{-- Étape 2 --}}
        <div id="step-2" class="step hidden">
            <div>
                <x-input-label for="pays" :value="__('Country')" />
                <select id="pays" name="pays" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="Burkina">Burkina Faso</option>
                    <option value="Cote-dIvoire">Cote d'Ivoire</option>
                </select>
                <x-input-error :messages="$errors->get('pays')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="ville" :value="__('City')" />
                <select id="ville" name="ville" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="Ouagadougou">Ouagadougou</option>
                    <option value="Bobo-Dioulasso">Bobo-Dioulasso</option>
                    <option value="Koumassi">Koumassi</option>
                    <option value="Abidjan">Abidjan</option>
                    <option value="San-Pedro">San-Pedro</option>
                    <option value="Daloa">Daloa</option>
                </select>
                <x-input-error :messages="$errors->get('ville')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="telephone" :value="__('Telephone')" />
                <x-text-input id="telephone" class="block mt-1 w-full" type="number" name="telephone" :value="old('telephone')" required autocomplete="telephone" />
                <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <x-primary-button type="button" class="previous">
                    {{ __('Previous') }}
                </x-primary-button>
                <x-primary-button type="button" class="next">
                    {{ __('Next') }}
                </x-primary-button>
            </div>
        </div>

        {{-- Étape 3 --}}
        <div id="step-3" class="step hidden">
        <div class="mt-4">
                <x-input-label for="age" :value="__('Age')" />
                <x-text-input id="age" class="block mt-1 w-full" type="number" name="age" :value="old('age')" required autocomplete="age" />
                <x-input-error :messages="$errors->get('age')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="sexe" :value="__('Gender')" />
                <select id="sexe" name="sexe" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                </select>
                <x-input-error :messages="$errors->get('sexe')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="blood_group" :value="__('Blood Group')" />
                <div class="mt-1 flex flex-wrap gap-4">
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bloodType)
                        <label class="flex items-center">
                            <input type="radio" name="blood_group" value="{{ $bloodType }}" class="sr-only" required>
                            <div class="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-md cursor-pointer hover:bg-indigo-100 focus:outline-none blood-type" data-value="{{ $bloodType }}">
                                {{ $bloodType }}
                            </div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('blood_group')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <x-primary-button type="button" class="previous">
                    {{ __('Previous') }}
                </x-primary-button>
                <x-primary-button type="button" class="next">
                    {{ __('Next') }}
                </x-primary-button>
            </div>
        </div>

        {{-- Étape 4 --}}
        <div id="step-4" class="step hidden">
            <div>
                <x-input-label for="image" :value="__('Image')" />
                <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" :value="old('image')"  autocomplete="image" />
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

           

            <div class="flex items-center justify-between mt-4">
                <x-primary-button type="button" class="previous">
                    {{ __('Previous') }}
                </x-primary-button>

                <x-primary-button type="submit" class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </div>

    </form>

    <script>
       document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.step');
    let currentStep = 0;

    steps[currentStep].classList.remove('hidden');

    document.querySelectorAll('.next').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep < steps.length - 1) {
                steps[currentStep].classList.add('hidden');
                currentStep++;
                steps[currentStep].classList.remove('hidden');
            }
        });
    });

    document.querySelectorAll('.previous').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep > 0) {
                steps[currentStep].classList.add('hidden');
                currentStep--;
                steps[currentStep].classList.remove('hidden');
            }
        });
    });

    // Manage blood group selection
    document.querySelectorAll('.blood-type').forEach(button => {
        button.addEventListener('click', (e) => {
            document.querySelectorAll('.blood-type').forEach(item => item.classList.remove('selected'));
            button.classList.add('selected');
            button.querySelector('input').checked = true;
        });
    });
});

    </script>
</x-guest-layout>
