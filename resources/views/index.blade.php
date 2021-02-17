@php ($nav = 'main')
@extends('layout.app')

@section('content')
    <div class="container">
        <div class="card list-group mb-2">
        @foreach($projects as $project)
            <div class="card-body list-group-item" id="project-{{ $project->id }}">
                <div class="d-flex flex-row justify-content-between">
                    <h5 class="card-title font-weight-bold mb-1">{{ $project->title }}</h5>
                    <p class="card-text small">{{ $project->status->name }}</p>
                </div>
                <p class="card-text mb-4">{{ $project->owner->name }} ({{ $project->owner->email }})</p>
                <a href="/edit/{{ $project->id }}" class="btn btn-primary">{{ __('app.edit') }}</a>
                <a href="#" onclick="deleteConfirm('{{ $project->id }}')" class="btn btn-danger">{{ __('app.delete') }}</a>
            </div>
        @endforeach
        </div>
        {{ $projects->links() }}
    </div>
@endsection

@section('script')
<script>
function deleteConfirm(projectId) {
	  Swal.fire({
        icon: 'warning',
        title: '{{ __('app.delete_confirm') }}',
        showCancelButton: true,
        confirmButtonText: '{{ __('app.delete') }}',
        confirmButtonColor: '#e3342f',
        cancelButtonText: '{{ __('app.cancel') }}',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/delete/' + projectId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function() {
                    $('#project-' + projectId).remove();
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('app.delete_success') }}',
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('app.delete_error') }}',
                    });
                }
            });
        }
    });
}
</script>
@endsection
