<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="2 Point Delivery Web App" />
    <meta name="author" content="Elabd Technologies" />
    <title>@yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Include CSRF token -->

    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo/favicon.png') }}">

    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"> --}}
    <link rel="stylesheet"
        href="{{ asset('fonts/poppins/css2.css?family=Poppins:wght@400;500;600;700&display=swap') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Other meta tags -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">


    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    {{-- Loading Chat JS Library --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body>


    <!-- Navbar component -->
    <x-admin.navbar />

    <main class="main-left p-3">

        {{-- Start Show content --}}
        @yield('content')
        {{-- End Show Content --}}

    </main>


    <!-- Footer button component -->
    <x-admin.footer />

    <div class="toast-container position-fixed top-0 end-0 p-3 d-none">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div id="toast-header" class="toast-header">
                <strong id="toast-title" class="me-auto">Toast Heading</strong>
                <small id="toast-time">Just Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <p id="toast-message">This is a toast message.</p>
            </div>
        </div>
    </div>

    {{-- JS Files here --}}
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
        function triggerToast(title, message) {
            const toastLiveExample = document.getElementById('liveToast');

            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
            $('#toast-title').text(title);
            $('#toast-message').text(message);
            $('#toast-header').addClass('text-white');
            $('.toast-container').removeClass('d-none');
            if (title == 'Success') {
                $('#toast-header').addClass('bg-success');
            } else if (title == 'Error') {
                $('#toast-header').addClass('bg-danger');
            } else {
                $('#toast-header').addClass('bg-primary');
            }
            toastBootstrap.show();
        }
    </script>

    @if (session('success'))
        <script>
            triggerToast('Success', '{{ session('success') }}');
        </script>
    @endif

    @if (session('error'))
        <script>
            triggerToast('Error', '{{ session('error') }}');
        </script>
    @endif

</body>

</html>
