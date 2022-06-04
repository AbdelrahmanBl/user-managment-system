@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a class="text-decoration-none" href="#">{{ __('Users') }}</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @error('photo')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror

                    @if (auth()->user()->HasPermission('create'))
                        <a class="btn btn-success" href="{{ route('users.create') }}">{{ __('Create') }}</a>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Full Name') }}</th>
                                <th>{{ __('User Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Avatar') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Methods') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = $users->toArray()['from'];
                            @endphp
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('users.upload' ,$user->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="file" class="d-none" name="photo" onchange="this.parentNode.submit()" id="choose-{{ $user->id }}">
                                        </form>
                                        <img src="{{ $user->avatar }}" onclick="document.getElementById('choose-{{ $user->id }}').click()" height="50" width="50" alt="avatar">
                                    </td>
                                    <td>{{ $user->RoleName }}</td>
                                    <td class="d-flex justify-content-between">
                                        <a href="{{ route('users.show' ,$user->id) }}" class="btn btn-secondary btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if (auth()->user()->HasPermission('update'))
                                            <a href="{{ route('users.edit' ,$user->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        @if (auth()->user()->HasPermission('destroy'))
                                            <form method="POST" action="{{ route('users.destroy' ,$user->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">
                                                    <i class="fa fa-remove"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            
                            <div>
                                @if ($users->toArray()['total'] > $users->toArray()['per_page'])
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="{{ $users->toArray()['prev_page_url'] }}" class="btn"><</a>
                                        <a href="{{ $users->toArray()['first_page_url'] }}" class="btn">1</a>
                                        ...........
                                        <a href="{{ $users->toArray()['last_page_url'] }}" class="btn">{{ $users->toArray()['last_page'] }}</a>
                                        <a href="{{ $users->toArray()['next_page_url'] }}" class="btn">></a>
                                    </div>
                                @endif
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
