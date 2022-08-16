@extends('layouts.app')

@section('content')
    <div class="container">
        @if (isset($error))
            <div class="row">
                <div class="col">
                    <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                </div>
            </div>
        @endif

        @if(isset($user))
            <div class="row">
                <div class="col">
                    <a class="btn btn-primary p-2" href="{{ route('admin.user_edit', $user->id) }}" role="button">{{ __('Edit User') }}</a>

                    <form class="d-inline" method="POST" action="{{ route('admin.user_delete', $user->id) }}">
                        @csrf
                        <input name="_method" type="hidden" value="DELETE">
                        <button type="submit" class="btn btn-danger delete" title='Delete'>{{ __('Delete User') }}</button>
                    </form>

                    <hr />
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col">
                    <div class="card mb-5">
                        <div class="card-header">{{ __('User data') }}</div>
                        <div class="card-body">
                            <form>
                                <div class="form-group row mb-3">
                                    <label for="userFullName" class="col-sm-2 col-form-label">{{ __("Full Name") }}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="userFullName" value="{{ $user->name }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="userEmail" class="col-sm-2 col-form-label">{{ __("Email") }}</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="userEmail" value="{{ $user->email }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="registeredAt" class="col-sm-2 col-form-label">{{ __("Registered At") }}</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="registeredAt" value="{{ $user->created_at }}" disabled>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-5">
                        <div class="card-header">{{ __('Active Reservations') }}</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Author</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Reserved At</th>
                                        <th scope="col">Expiration At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->reservations as $reservation)
                                        <tr>
                                            <td>{{ $reservation->book->author }}</td>
                                            <td>{{ $reservation->book->title }}</td>
                                            <td>{{ $reservation->reservation_start->toDateString() }}</td>
                                            <td>{{ $reservation->expiration_date->toDateString() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">{{ __('Past Book Reservations') }}</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Author</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Reserved At</th>
                                    <th scope="col">Expiration At</th>
                                    <th scope="col">Returned At</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->pastReservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->book->author }}</td>
                                        <td>{{ $reservation->book->title }}</td>
                                        <td>{{ $reservation->reservation_start->toDateString() }}</td>
                                        <td>{{ $reservation->expiration_date->toDateString() }}</td>
                                        <td>{{ $reservation->return_date->toDateString() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
