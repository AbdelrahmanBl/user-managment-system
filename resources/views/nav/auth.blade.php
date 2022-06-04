<li class="nav-item">
    <a class="nav-link" href="{{ route('users.index') }}">{{ __('users') }}</a>
</li>
@if (auth()->user()->isAdmin)
<li class="nav-item">
    <a class="nav-link" href="{{ route('users.trashed') }}">{{ __('trashed users') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('actions.index') }}">{{ __('actions') }}</a>
</li>
@endif
<li class="nav-item dropdown">
    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        {{ Auth::user()->fullname }}
    </a>

    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</li>