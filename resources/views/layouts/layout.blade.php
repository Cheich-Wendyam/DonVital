<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>DonVital Admin Dashboard </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('images/Icon.ico')}}">

        <!-- Lightbox css -->
        <link href="{{asset('libs/magnific-popup/magnific-popup.css')}}" rel="stylesheet" type="text/css" />

        <!-- App css -->
        <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- third party css -->
        <link href="{{ asset('libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('libs/datatables/buttons.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('libs/datatables/select.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
        <!-- third party css end -->



    </head>

    <body>
         <!-- Begin page -->
         <div id="wrapper">

        @include('partials.topbar')

        @include('partials.left-sidebar')
        @yield('content')
        @include('partials.footer')

       




</div>
<!-- END wrapper -->

@include('partials.right-sidebar')


<script src="{{asset('js/app.js')}}"></script>

</body>
</html>