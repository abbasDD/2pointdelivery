<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="2 Point Delivery Web App" />
    <meta name="author" content="Elabd Technologies" />
    <title>@yield('title')</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo/favicon.png') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Other meta tags -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">


    <link href="{{ asset('css/style.css') }}" rel="stylesheet">


</head>

<body>


    <!-- Navbar component -->
    <x-client.navbar />

    <main class="main-left p-3">
        @yield('content')
    </main>

    <!-- Chat button component -->
    <x-chat-button />

    <!-- Footer button component -->
    <x-client.footer />

    {{-- JS Files here --}}
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>


</body>

</html>
