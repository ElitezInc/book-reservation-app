@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if (Session::has('error'))
        <div class="row">
            <div class="col">
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('error') }}
                </div>
            </div>
        </div>
    @endif

    @if (Session::has('success'))
        <div class="row">
            <div class="col">
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col">
            @if(request()->has('trashed'))
                <a href="{{ route('admin.books') }}" class="btn btn-info">View All</a>
            @else
                <a href="{{ route('admin.books', ['trashed' => true]) }}" class="btn btn-primary">View Trashed</a>
            @endif
            <hr />
        </div>
    </div>

    <div class="row">
        <div class="col-12" style="overflow: auto;">
            <table class="table table-bordered books_datatable">
                <thead>
                <tr>
                    <th><input type="checkbox" name="main_checkbox"></th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Author') }}</th>
                    <th>{{ __('Code') }}</th>
                    <th>{{ __('Release Date') }}</th>
                    <th>{{ __('Reserved By') }}</th>
                    <th>{{ __('Past Reservations') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const table = $('.books_datatable').DataTable({
                processing: true,
                serverSide: true,

                @if(request()->has('trashed'))
                ajax: "{{ route('admin.books', ['trashed' => true]) }}",
                @else
                ajax: "{{ route('admin.books') }}",
                @endif

                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'author', name: 'author' },
                    { data: 'code', name: 'code' },
                    { data: 'release_date', name: 'release_date' },
                    { data: 'reserved_by', name: 'reserved_by' },
                    { data: 'past_reservations', name: 'past_reservations' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
