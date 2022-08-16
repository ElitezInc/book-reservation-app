<li>
    <a href="{{ route('admin.home') }}" class="nav-link px-3 py-3 active">
        <i class="fa-solid fa-house me-2 text-white" style="font-size: 20px;"></i>
        <span class="h5">{{ __('Home Page') }}</span>
    </a>
</li>

<li>
    <hr class="dropdown-divider bg-light" style="height: 1px;"/>
</li>

<li>
    <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" data-bs-target="#usersCollapse" href="#">
        <span class="h5">{{ __('Users') }}</span>
        <span class="ms-auto">
            <span class="right-icon">
                <i class="fa-solid fa-chevron-down"></i>
            </span>
        </span>
    </a>
    <div class="collapse" id="usersCollapse">
        <ul class="navbar-nav ps-3">
            <li>
                <a href="{{ route('admin.users') }}" class="nav-link px-3">
                      <span class="me-3">
                          <i class="fa-solid fa-user"></i>
                      </span>
                    <span class="h5">{{ __('Users List') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users', ['trashed' => true]) }}" class="nav-link px-3">
                      <span class="me-3">
                          <i class="fa-solid fa-trash"></i>
                      </span>
                    <span class="h5">{{ __('Deleted Users') }}</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link px-3">
                      <span class="me-2">
                          <i class="fa-solid fa-user-plus"></i>
                      </span>
                    <span class="h5">{{ __('Create User') }}</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<li>
    <hr class="dropdown-divider bg-light" style="height: 1px;"/>
</li>

<li>
    <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" data-bs-target="#booksCollapse" href="#users">
        <span class="h5">{{ __('Books') }}</span>
        <span class="ms-auto">
            <span class="right-icon">
                <i class="fa-solid fa-chevron-down"></i>
            </span>
        </span>
    </a>
    <div class="collapse" id="booksCollapse">
        <ul class="navbar-nav ps-3">
            <li>
                <a href="{{ route('admin.books') }}" class="nav-link px-3">
                      <span class="me-3">
                          <i class="fa-solid fa-book"></i>
                      </span>
                    <span class="h5">{{ __('Books List') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.books', ['trashed' => true]) }}" class="nav-link px-3">
                      <span class="me-3">
                          <i class="fa-solid fa-trash"></i>
                      </span>
                    <span class="h5">{{ __('Deleted Books') }}</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link px-3">
                      <span class="me-2">
                          <i class="fa-solid fa-plus"></i>
                      </span>
                    <span class="h5">{{ __('Create Book') }}</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<li>
    <hr class="dropdown-divider bg-light" style="height: 1px;"/>
</li>

<li>
    <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" data-bs-target="#reservationsCollapse" href="#users">
        <span class="h5">{{ __('Reservations') }}</span>
        <span class="ms-auto">
            <span class="right-icon">
                <i class="fa-solid fa-chevron-down"></i>
            </span>
        </span>
    </a>
    <div class="collapse" id="reservationsCollapse">
        <ul class="navbar-nav ps-3">
            <li>
                <a href="{{ route('admin.reservations') }}" class="nav-link px-3">
                      <span class="me-2">
                          <i class="fa-solid fa-calendar-day"></i>
                      </span>
                    <span style="font-size: 20px;">{{ __('Active Reservations') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reservations_histories') }}" class="nav-link px-3">
                      <span class="me-2">
                          <i class="fa-solid fa-clock-rotate-left"></i>
                      </span>
                    <span style="font-size: 19px;">{{ __('Reservations History') }}</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link px-3">
                      <span class="me-2">
                          <i class="fa-solid fa-plus"></i>
                      </span>
                    <span class="h5">{{ __('Create Reservation') }}</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<li>
    <hr class="dropdown-divider bg-light" style="height: 1px;"/>
</li>

<li>
    <a href="#" class="nav-link px-3 py-3">
        <i class="fa-solid fa-file-image me-2 text-white" style="font-size: 20px;"></i>
        <span class="h5">{{ __('Media Gallery') }}</span>
    </a>
</li>

<li>
    <a href="#" class="nav-link px-3 py-3">
        <i class="fa-solid fa-wrench me-2 text-white" style="font-size: 20px;"></i>
        <span class="h5">{{ __('Settings') }}</span>
    </a>
</li>
