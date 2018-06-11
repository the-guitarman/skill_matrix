@if(Session::has('flash_error'))
    <div class="alert alert-danger" role="alert">{{ Session::get('flash_error') }}</div>
@endif
@if(Session::has('flash_notice'))
    <div class="alert alert-success" role="alert">{{ Session::get('flash_notice') }}</div>
@endif