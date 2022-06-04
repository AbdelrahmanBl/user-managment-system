@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a class="text-decoration-none" href="#">{{ __('Actions') }}</a>
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

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('User Name') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Message') }}</th>
                                <th>{{ __('Time') }}</th>
                                <th>{{ __('Methods') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = $actions->toArray()['from'];
                            @endphp
                            @foreach ($actions as $action)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $action->user->fullname }}</td>
                                    <td>{{ __($action->type) }}</td>
                                    <td>{{ "{$action->user->fullname}" }} {{ Str::lower($action->type) }} {{ __("his") }} {{ __(Str::lower($action->name)) }} {{ __("data") }}.</td>
                                    <td>{{ $action->created_at }}</td>
                                    <td class="d-flex justify-content-between">
                                        <a href="{{ route(Str::lower($action->name) . 's' . '.show' ,$action->user_id) }}" class="btn btn-secondary btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            
                            <div>
                                @if ($actions->toArray()['total'] > $actions->toArray()['per_page'])
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="{{ $actions->toArray()['prev_page_url'] }}" class="btn"><</a>
                                        <a href="{{ $actions->toArray()['first_page_url'] }}" class="btn">1</a>
                                        ...........
                                        <a href="{{ $actions->toArray()['last_page_url'] }}" class="btn">{{ $actions->toArray()['last_page'] }}</a>
                                        <a href="{{ $actions->toArray()['next_page_url'] }}" class="btn">></a>
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
