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
                    <a href="{{ route('admin.users') }}" class="btn btn-info">View All</a>
                @else
                    <a href="{{ route('admin.users', ['trashed' => true]) }}" class="btn btn-primary">View Trashed</a>
                @endif
                <hr />
            </div>
        </div>

        <div class="row">
            <div class="col-12" style="overflow: auto;">
                <table class="table table-bordered users_datatable">
                    <thead>
                    <tr>
                        <th><input type="checkbox" name="main_checkbox"></th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Registered At') }}</th>
                        <th>{{ __('Reserved Books') }}</th>
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
            const table = $('.users_datatable').DataTable({
                processing: true,
                serverSide: true,

                @if(request()->has('trashed'))
                    ajax: "{{ route('admin.users', ['trashed' => true]) }}",
                @else
                    ajax: "{{ route('admin.users') }}",
                @endif

                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'registered_at', name: 'registered_at' },
                    { data: 'reserved_books', name: 'reserved_books' },
                    { data: 'past_reservations', name: 'past_reservations' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
