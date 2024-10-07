@extends('layouts.layout')

@section('content')

<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DonVital</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Mon Compte</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-4 col-xl-4">
                    <div class="card-box text-center">
                        <!-- Afficher l'image de profil -->
                        @if (auth()->user()->image == null)

                            <img src="{{ asset('assets/images/default-profile.jpg') }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                         @else

                         <img src="{{ asset('storage/' . auth()->user()->image) ?? 'assets/images/default-profile.jpg' }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                        @endif

                        <h4 class="mb-0">{{ auth()->user()->name }}</h4>
                        <p class="text-muted">{{ '@' . auth()->user()->email }}</p>



                        <div class="text-left mt-3">
                            <h4 class="font-13 text-uppercase">About Me :</h4>
                            <p class="text-muted font-13 mb-3">
                                {{ auth()->user()->bio ?? 'Pas de Bio' }}
                            </p>
                            <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ml-2">{{ auth()->user()->name }}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Telephone :</strong><span class="ml-2">{{ auth()->user()->telephone ?? 'N/A' }}</span></p>
                            <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2">{{ auth()->user()->email }}</span></p>
                            <p class="text-muted mb-1 font-13"><strong>Ville :</strong> <span class="ml-2">{{ auth()->user()->ville ?? 'N/A' }}</span></p>
                        </div>

                        <ul class="social-list list-inline mt-3 mb-0">
                            <!-- Optional social links for the user -->
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="mdi mdi-twitter"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github-circle"></i></a>
                            </li>
                        </ul>
                    </div> <!-- end card-box -->
                </div> <!-- end col-->

                <div class="col-lg-8 col-xl-8">
                    <div class="card-box">
                        <h4 class="header-title mb-3">Edit Profile</h4>

                        <!-- User Information Form -->
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Nom</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}">
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}">
                            </div>

                            <div class="form-group">
                                <label for="mobile">Téléphone</label>
                                <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('telephone', auth()->user()->telephone) }}">
                            </div>

                            <div class="form-group">
                                <label for="location">Ville</label>
                                <input type="text" id="location" name="location" class="form-control" value="{{ old('ville', auth()->user()->ville) }}">
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div> <!-- end card-box -->
                </div> <!-- end col -->
            </div>
            <!-- end row-->
        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- Vendor js -->
<script src="{{asset('js/vendor.min.js')}}"></script>



@endsection
