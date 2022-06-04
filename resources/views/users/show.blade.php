@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a class="text-decoration-none" href="{{ route('users.index') }}">{{ __('Users') }}</a> >
                    <a class="text-decoration-none" href="#">{{ $user->fullname }}</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        <table class="table">
                            <tr>
                                <th>{{ __('Role') }}</th>
                                <td>{{ $user->RoleName }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Full Name') }}</th>
                                <td>{{ $user->fullname }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('User Name') }}</th>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Email') }}</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Salary') }}</th>
                                <td>{{ $user->detail->salary ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Address') }}</th>
                                <td>{{ $user->detail->address ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Mobile') }}</th>
                                <td>{{ $user->detail->mobile ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Tele') }}</th>
                                <td>{{ $user->detail->tele ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Skills') }}</th>
                                <td>
                                    @foreach ($user->skills()->get() as $skill)
                                        - {{ $skill->name }}
                                        <br>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
