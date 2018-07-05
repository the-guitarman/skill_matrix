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

        <div class="modal fade" id="ajax-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="text-center"><i class="fa fa-spinner"></i> Loading ...</div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
              </div>
            </div>
          </div>
        </div>


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