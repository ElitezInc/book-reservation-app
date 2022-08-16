@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Session::has('error'))
            <div class="row">
                <div class="col">
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('error') }}
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="row">
                <div class="col">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (Session::has('success'))
            <div class="row">
                <div class="col">
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                </div>
            </div>
        @endif

        @if (isset($user))
            <div class="row justify-content-center">
                <div class="col">
                    <div class="card mb-5">
                        <div class="card-header">{{ __('User data') }}</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.user_details_update', ['id' => $user->id]) }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group row mb-3">
                                    <label for="userFullName" class="col-sm-2 col-form-label">{{ __("Full Name") }}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="userFullName" name="name" value="{{ old('name', $user->name) }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="userEmail" class="col-sm-2 col-form-label">{{ __("Email") }}</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="userEmail" name="email" value="{{ old('email', $user->email) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="registeredAt" class="col-sm-2 col-form-label">{{ __("Registered At") }}</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="registeredAt" value="{{ $user->created_at }}" disabled>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
