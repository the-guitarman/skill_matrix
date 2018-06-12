<!DOCTYPE html>
<html data-whatinput="keyboard" data-whatintent="keyboard" class=" whatinput-types-initial whatinput-types-keyboard">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $app_name }}</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
        <!--<link rel="stylesheet" href="{{ asset('css/foundation.css') }}">-->
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <!--
        <link rel="stylesheet" href="{{ asset('pickadate/lib/themes/default.css') }}">
        <link rel="stylesheet" href="{{ asset('pickadate/lib/themes/default.date.css') }}">
        <meta class="foundation-mq">
        -->
    </head>
    <body>
        <header>
            @include('layouts/navbar_top')
        </header>

        <div class="container-fluid clearfix">
            @include('layouts/flash_messages')

            <div class="row justify-content-center">
                <div class="col-12 col-sm-6">
                    @yield('content')
                </div>
            </div>
        </div>

        <footer>
            @include('layouts/navbar_bottom')
        </footer>


        <script src="{{ mix('/js/app.js') }}"></script>

<!--
        <script src="{{-- mix('/js/popper.min.js') --}}"></script>
        <script src="{{-- mix('/js/manifest.js') --}}"></script>
        <script src="{{-- mix('/js/vendor.js') --}}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
-->

<!--
        <script src="{{ asset('js/vendor/jquery.js') }}"></script>
        <script src="{{ asset('js/vendor/what-input.js') }}"></script>
        <script src="{{ asset('js/vendor/foundation.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('pickadate/lib/picker.js') }}"></script>
        <script src="{{ asset('pickadate/lib/picker.date.js') }}"></script>
        <script>
          $(document).ready(function() {
            $(document).foundation();
            $('.datepicker').pickadate(
              {
                format: 'yyyy-mm-dd',
                formatSubmit: 'yyyy-mm-dd'
              }
            );
          })
        </script>
-->

    </body>
</html>