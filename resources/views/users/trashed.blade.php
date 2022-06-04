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
                                        <img src="{{ $user->avatar }}" height="25" width="25" alt="avatar">
                                    </td>
                                    <td>{{ $user->RoleName }}</td>
                                    <td class="d-flex justify-content-between">
                                        <form method="POST" action="{{ route('users.restore' ,$user->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-primary btn-sm">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('users.delete' ,$user->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fa fa-remove"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            
                            <div>
                                @if ($users->toArray()['total'] > $users->toArray()['per_page'])
                                    <div class="d-flex justify-content-center">
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
