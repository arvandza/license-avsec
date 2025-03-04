<!DOCTYPE html>
<!--
Template Name: NobleUI - HTML Bootstrap 5 Admin Dashboard Template
Author: NobleUI
Website: https://www.nobleui.com
Portfolio: https://themeforest.net/user/nobleui/portfolio
Contact: nobleui123@gmail.com
Purchase: https://1.envato.market/nobleui_admin
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="NobleUI">
    <meta name="keywords"
        content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>@yield('title')</title>

    <!-- Fonts -->
    @include('partials.head')
</head>

<body>
    <div class="main-wrapper">

        <!-- partial:../../partials/_sidebar.html -->
        @include('partials.sidebar')
        <!-- partial -->

        <div class="page-wrapper">

            <!-- partial:../../partials/_navbar.html -->
            @include('partials.navbar')
            <!-- partial -->

            <div class="page-content"
                style="background-image: url('{{ asset('assets/images/backgroundimage.jpeg') }}'); background-size: cover;">

                <nav class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">@yield('current_menu')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@yield('current_page')</li>
                    </ol>
                </nav>

                @yield('content')

            </div>

            <!-- partial:../../partials/_footer.html -->
            @include('partials.footer')
            <!-- partial -->

        </div>
    </div>

    <!-- core:js -->
    @yield('scripts')
    <!-- End custom js for this page -->

</body>

</html>
