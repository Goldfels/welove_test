@if(count($errors) > 0)
<div class="alert alert-danger alert-dismissible show" role="alert">
    @foreach($errors->all() as $error)
        {{ $error }}
    @endforeach
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
