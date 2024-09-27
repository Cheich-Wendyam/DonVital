<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>Connexion</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Un thème d'administration complet qui peut être utilisé pour construire un CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('images/Icon.ico') }}">

        <!-- App css -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />

    </head>

    <body class="authentication-bg authentication-bg-pattern">

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">

                            <div class="card-body p-4">

                                <div class="text-center w-75 m-auto">
                                    <a href="{{ url('/') }}">
                                        <span><img src="{{ asset('images/Icon.ico') }}" alt="" height="80"></span>
                                    </a>
                                    <p class="text-muted mb-4 mt-3">Entrez votre adresse e-mail et mot de passe pour accéder au panneau d'administration.</p>
                                </div>

                                <h5 class="auth-title">Se connecter</h5>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Adresse e-mail -->
                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Adresse e-mail</label>
                                        <input class="form-control" type="email" name="email" id="emailaddress" required placeholder="Entrez votre adresse e-mail" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Mot de passe -->
                                    <div class="form-group mb-3">
                                        <label for="password">Mot de passe</label>
                                        <input class="form-control" type="password" name="password" id="password" required placeholder="Entrez votre mot de passe">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Se souvenir de moi -->
                                    <div class="form-group mb-3">
                                        <div class="custom-control custom-checkbox checkbox-info">
                                            <input type="checkbox" name="remember" class="custom-control-input" id="checkbox-signin">
                                            <label class="custom-control-label" for="checkbox-signin">Se souvenir de moi</label>
                                        </div>
                                    </div>

                                    <!-- Bouton de connexion -->
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-danger btn-block" type="submit"> Connexion </button>
                                    </div>
                                </form>



                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->


                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <footer class="footer footer-alt">
            2024 &copy; DonVital <a href="#" class="text-muted">AdminPanel</a>
        </footer>

        <!-- Vendor js -->
        <script src="{{ asset('js/vendor.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('js/app.min.js') }}"></script>

    </body>
</html>
