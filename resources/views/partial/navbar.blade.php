<nav class="navbar navbar-expand-lg navbar-glass fixed-top">
    <div class="container-fluid">
        <button class="btn btn-outline-light me-2" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>

        <a class="navbar-brand text-white" href="#">
            <img src="{{ asset('storage/SYNKRONE.svg') }}" width="40px" alt="Logo">
        </a>
        @if (Session('role'))
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger fw-bold" href="/logout">Logout</a>
                </li>
            </ul>
        @else
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-primary" href="/login">Log In</a>
                </li>
            </ul>
        @endif
    </div>
</nav>
