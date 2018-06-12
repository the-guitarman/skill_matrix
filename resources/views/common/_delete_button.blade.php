<form action="{{ route($route, $id) }}" method="post" class="css-delete-button">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <button type="submit" class="btn btn-danger" data-delete="confirm">{!! $text !!}</button>
</form>