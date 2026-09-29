@extends('layouts.app')

@section('content')
<div class="pt-24 md:pt-28 w-full bg-gray-50 min-h-screen flex flex-col justify-center pb-12 px-4 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">

        <!-- Carte principale -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10">

            <!-- En-tête -->
            <div class="bg-white border-b border-gray-100 p-6 sm:p-8 text-center">

                <!-- Icône -->
                <div class="mx-auto bg-green-100 p-3 rounded-full w-16 h-16 flex items-center justify-center mb-4 border border-green-200">

                    <svg class="h-8 w-8 text-green-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />

                    </svg>

                </div>

                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                    Confirmation requise
                </h2>

                <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider mt-2">
                    Vérification de sécurité
                </p>

            </div>


            <!-- Contenu -->
            <div class="p-6 sm:p-8">

                <!-- Message explicatif -->
                <div class="mb-6 bg-gray-50 border border-gray-100 rounded-lg p-4">

                    <div class="flex items-start">

                        <svg class="h-5 w-5 text-green-600 mt-0.5 mr-3 flex-shrink-0"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />

                        </svg>

                        <p class="text-xs leading-5 text-gray-500">
                            Cette section est sécurisée. Pour continuer,
                            veuillez confirmer votre mot de passe.
                        </p>

                    </div>

                </div>


                <!-- Formulaire -->
                <form method="POST"
                      action="{{ route('password.confirm') }}"
                      class="space-y-6">

                    @csrf


                    <!-- Mot de passe -->
                    <div>

                        <label for="password"
                               class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">
                            Mot de passe
                        </label>

                        <div class="relative rounded-md shadow-sm">

                            <!-- Icône cadenas -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                <svg class="h-5 w-5 text-gray-400"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 002-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />

                                </svg>

                            </div>


                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"

                                class="block w-full pl-10 pr-3 py-3
                                       border border-gray-200
                                       rounded-lg
                                       focus:ring-2
                                       focus:ring-green-500
                                       focus:border-green-500
                                       text-gray-900
                                       text-sm
                                       transition-colors
                                       bg-gray-50
                                       focus:bg-white
                                       outline-none"

                                placeholder="••••••••"
                            >

                        </div>


                        <!-- Erreur -->
                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2"
                        />

                    </div>


                    <!-- Boutons -->
                    <div class="pt-4 border-t border-gray-100 space-y-3">

                        <!-- Confirmer -->
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center space-x-2
                                   bg-green-600
                                   hover:bg-green-700
                                   text-white
                                   font-bold
                                   py-4
                                   px-4
                                   rounded-lg
                                   shadow-md
                                   transition-all
                                   transform
                                   hover:-translate-y-1
                                   active:scale-95
                                   uppercase
                                   text-xs
                                   tracking-widest">

                            <span>
                                Confirmer le mot de passe
                            </span>

                            <svg class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7" />

                            </svg>

                        </button>


                        <!-- Retour -->
                        <a
                            href="{{ route('login') }}"
                            class="w-full flex justify-center items-center
                                   py-3 px-4
                                   rounded-lg
                                   border border-gray-200
                                   bg-white
                                   hover:bg-gray-50
                                   text-gray-600
                                   font-bold
                                   text-xs
                                   uppercase
                                   tracking-widest
                                   transition-colors">

                            <svg class="h-4 w-4 mr-2"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />

                            </svg>

                            Retour à la connexion

                        </a>

                    </div>

                </form>

            </div>

        </div>


        <!-- Footer -->
        <div class="mt-8 text-center">

            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">
                Accès réservé au personnel autorisé
            </p>

        </div>

    </div>

</div>
@endsection

