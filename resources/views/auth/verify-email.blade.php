
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
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />

                    </svg>

                </div>

                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                    Vérifiez votre adresse email
                </h2>

                <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider mt-2">
                    Confirmation de votre compte
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
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />

                        </svg>

                        <p class="text-xs leading-5 text-gray-500">
                            Merci pour votre inscription ! Avant de commencer,
                            veuillez vérifier votre adresse email en cliquant
                            sur le lien que nous venons de vous envoyer.
                            Si vous ne l'avez pas reçu, vous pouvez demander
                            un nouvel email de vérification.
                        </p>

                    </div>

                </div>


                <!-- Message de succès -->
                @if (session('status') == 'verification-link-sent')

                    <div class="mb-6 bg-green-50 border border-green-100 rounded-lg p-4">

                        <div class="flex items-start">

                            <svg class="h-5 w-5 text-green-600 mt-0.5 mr-3 flex-shrink-0"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7" />

                            </svg>

                            <p class="text-xs leading-5 text-green-700 font-medium">
                                Un nouveau lien de vérification vient d'être
                                envoyé à l'adresse email utilisée lors de votre inscription.
                            </p>

                        </div>

                    </div>

                @endif


                <!-- Actions -->
                <div class="pt-4 border-t border-gray-100 space-y-3">

                    <!-- Renvoyer l'email -->
                    <form method="POST"
                          action="{{ route('verification.send') }}">

                        @csrf

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
                                Renvoyer l'email de vérification
                            </span>

                            <svg class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />

                            </svg>

                        </button>

                    </form>


                    <!-- Déconnexion -->
                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button
                            type="submit"
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
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />

                            </svg>

                            Se déconnecter

                        </button>

                    </form>

                </div>

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

