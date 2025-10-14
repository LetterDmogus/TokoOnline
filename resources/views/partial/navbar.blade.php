<nav class="navbar navbar-expand-lg navbar-glass fixed-top">
    <div class="container-fluid">
        <button class="btn btn-outline-light me-2" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>

        <a class="navbar-brand text-white" href="#">
            <img src="{{ asset('storage/SYNKRONE.png') }}" width="120px" alt="Logo">
        </a>

        @if (Session('role') == '3' || Session('role') == '2')
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">Data
                        Master</a>
                    <ul class="dropdown-product">
                        <li><a class="dropdown-item" href="/product">product</a></li>
                        <li><a class="dropdown-item" href="/user">User</a></li>
                        <li><a class="dropdown-item" href="#">Log</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger fw-bold" href="/logout">Logout</a>
                </li>
            </ul>
        @elseif(Session('role') == '1')
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/cart">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger fw-bold" href="/logout">Logout</a>
                </li>
            </ul>
        @else
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/login">Log In</a>
                </li>
            </ul>
        @endif
    </div>
</nav>