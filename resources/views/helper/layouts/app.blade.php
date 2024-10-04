<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="2 Point Delivery Web App" />
    <meta name="author" content="Elabd Technologies" />
    <title>@yield('title')</title>

    <link rel="icon" type="image/x-icon"
        href="{{ config('website_favicon') ? asset('images/logo/' . config('website_favicon')) : asset('images/logo/icon.png') }}">

    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"> --}}
    <link rel="stylesheet"
        href="{{ asset('fonts/poppins/css2.css?family=Poppins:wght@400;500;600;700&display=swap') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Other meta tags -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.14.0/themes/smoothness/jquery-ui.css">


    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    {{-- - JS Files here  --}}
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.14.0/jquery-ui.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- jQuery Timepicker Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">

    <script>
        // Convert PHP date format to jQuery UI datepicker format
        function convertDateFormat(phpFormat) {
            const formatMapping = {
                'Y': 'yy', // 4-digit year
                'm': 'mm', // 2-digit month
                'd': 'dd', // 2-digit day
                'j': 'd', // Day of the month without leading zeros
                'n': 'm', // Month without leading zeros
                'M': 'M', // Short textual representation of a month
                'D': 'D' // Day of the week short textual representation
            };
            return phpFormat.replace(/Y|m|d|j|n|M|D/g, function(match) {
                return formatMapping[match];
            });
        }
    </script>

</head>

<body>

    {{-- Loading Screen --}}
    <div id="loading-screen"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 10000; display: flex; justify-content: center; align-items: center;">
        <div class="loading-content">
            <img src="{{ asset('images/loading.gif') }}" alt="Loading..." style="width: 100px;">
            <!-- Adjust the width as necessary -->
        </div>
    </div>

    <!-- Navbar component -->
    {{-- <x-helper.navbar /> --}}
    @include('components.helper.navbar')

    <main class="main-left p-3">
        @yield('content')
    </main>

    {{-- Include Footer --}}
    @include('components.helper.footer')


    <div class="toast-container position-fixed top-5 end-0 p-3 d-none">
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


    {{-- Close Modal GLobal JS --}}
    <script>
        function closeModal(modalId) {
            // Hide modal with id
            $('#' + modalId).modal('hide');
        }
    </script>


    {{-- Page Loading JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('loading-screen').style.display = 'flex';
        });

        window.addEventListener('load', function() {
            document.getElementById('loading-screen').style.display = 'none';
        });
    </script>

</body>

</html>
