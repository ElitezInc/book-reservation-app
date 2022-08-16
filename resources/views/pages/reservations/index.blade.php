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
                    <a href="{{ route('admin.reservations') }}" class="btn btn-info">View All</a>
                @else
                    <a href="{{ route('admin.reservations', ['trashed' => true]) }}" class="btn btn-primary">View Trashed</a>
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
                        <th>{{ __('Reserved By') }}</th>
                        <th>{{ __('Reserved Book') }}</th>
                        <th>{{ __('Reservation Start') }}</th>
                        <th>{{ __('Expiration Date') }}</th>
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
                ajax: "{{ route('admin.reservations', ['trashed' => true]) }}",
                @else
                ajax: "{{ route('admin.reservations') }}",
                @endif

                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'reserved_by', name: 'reserved_by' },
                    { data: 'reserved_book', name: 'reserved_book' },
                    { data: 'reservation_start', name: 'reservation_start' },
                    { data: 'expiration_date', name: 'expiration_date' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
