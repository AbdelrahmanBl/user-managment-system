@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a class="text-decoration-none" href="{{ route('users.index') }}">{{ __('Users') }}</a> >
                    <a class="text-decoration-none" href="#">{{ __('Edit') }}</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update' ,$user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            @if (auth()->user()->isAdmin)
                            <div class="row mb-3">
                                <label for="role_id" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>
    
                                <div class="col-md-6">
                                    <select class="form-control @error('role_id') is-invalid @enderror" required name="role_id" id="role_id" autofocus>
                                        <option value="">{{ __('select a role') }}</option>
                                        @foreach ($roles as $role)
                                            <option @if($user->role_id == $role->id) selected @endif value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            <div class="row mb-3">
                                <label for="photo" class="col-md-4 col-form-label text-md-end">{{ __('Photo') }}</label>
    
                                <div class="col-md-6">
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" id="photo">
                                    @error('photo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="firstname" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>
    
                                <div class="col-md-6">
                                    <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ $user->firstname }}" required autocomplete="firstname" autofocus>
    
                                    @error('firstname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="lastname" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>
    
                                <div class="col-md-6">
                                    <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ $user->lastname }}" required autocomplete="lastname" autofocus>
    
                                    @error('lastname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('User Name') }}</label>
    
                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $user->username }}" required autocomplete="username" autofocus>
    
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
    
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email">
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h4 class="col-md-4  text-md-end">{{ __('Additional Details') }}</h4>
                            
                            <div class="row mb-3">
                                <label for="salary" class="col-md-4 col-form-label text-md-end">{{ __('Salary') }}</label>
    
                                <div class="col-md-6">
                                    <input id="salary" type="number" value="{{ $user->detail->salary ?? '' }}" class="form-control @error('salary') is-invalid @enderror" name="salary" autocomplete="new-salary">
    
                                    @error('salary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>
    
                                <div class="col-md-6">
                                    <input id="address" type="text" value="{{ $user->detail->address ?? '' }}" class="form-control @error('address') is-invalid @enderror" name="address" autocomplete="new-address">
    
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mobile" class="col-md-4 col-form-label text-md-end">{{ __('Mobile') }}</label>
    
                                <div class="col-md-6">
                                    <input id="mobile" type="text" value="{{ $user->detail->mobile ?? '' }}" class="form-control @error('mobile') is-invalid @enderror" name="mobile" autocomplete="new-mobile">
    
                                    @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tele" class="col-md-4 col-form-label text-md-end">{{ __('Tele') }}</label>
    
                                <div class="col-md-6">
                                    <input id="tele" type="text" value="{{ $user->detail->tele ?? '' }}" class="form-control @error('tele') is-invalid @enderror" name="tele" autocomplete="new-tele">
    
                                    @error('tele')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="skills" class="col-md-4 col-form-label text-md-end">{{ __('Skills') }}</label>
    
                                <div class="col-md-6">
                                    <select name="skills[]" id="skills" class="form-control @error('skills') is-invalid @enderror" multiple>
                                        <option value="">{{ __('Choose Skill') }}</option>
                                        @foreach ($skills as $skill)
                                            <option @if(in_array($skill->id ,$user->skills()->pluck('skill_id')->toArray() )) selected @endif value="{{ $skill->id }}">{{ $skill->name }}</option>
                                        @endforeach
                                    </select>
    
                                    @error('skills')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Store') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
