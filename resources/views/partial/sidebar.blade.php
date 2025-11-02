<div id="sidebar" class="collapsed sidebar d-flex flex-column p-3">
    @if (Session('role'))
        <a href="/" class="sidebar-brand mb-3 text-decoration-none text-white fw-bold fs-4"><i
                class="bi bi-house"></i>Dashboard</a>
        <a href="#" class="sidebar-link text-white"><i class="bi bi-bell-fill"></i> Notification</a>
    @endif
    @if (Session('role') == '3')
        <a class="sidebar-link text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i
                class="bi bi-box"></i> Data Master</a>
        <ul class="dropdown-product">
            <li><a href="/product" class="sidebar-link text-white"><i class="bi bi-box"></i> Products</a></li>
            <li><a href="/order" class="sidebar-link text-white"><i class="bi bi-cart4"></i> Orders</a></li>
            <li><a href="/restock" class="sidebar-link text-white"><i class="bi bi-arrow-up-circle"></i>
                    Restock</a></li>
        </ul>
        <a class="sidebar-link text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i
                class="bi bi-people"></i> Data User</a>
        <ul class="dropdown-product">
            <li><a href="/user" class="sidebar-link text-white"><i class="bi bi-people"></i> Users</a></li>
            <li><a href="/customer" class="sidebar-link text-white"><i class="bi bi-person-lines-fill"></i>
                    Customers</a></li>
            <li><a href="/seller" class="sidebar-link text-white"><i class="bi bi-person-lines-fill"></i>
                    Sellers</a>
            </li>
        </ul>
    @elseif (Session('role') == '2')

    @elseif (Session('role') == '4')
        <a href="/order" class="sidebar-link text-white"><i class="bi bi-cart4"></i> Orders</a>
    @elseif(Session('role') == '1')
        <a href="/product" class="sidebar-link text-white"><i class="bi bi-box"></i> Products</a>
    @endif
    @if (Session('role'))
        <a href="#" class="sidebar-link text-white"><i class="bi bi-gear-fill"></i> Setting</a>
        <a href="/logout" class="sidebar-link text-danger fw-bold"><i class="bi bi-box-arrow-right"></i> Logout</a>
    @else
        <a href="/login" class="sidebar-link text-primary fw-bold"><i class="bi bi-box-arrow-right"></i> Login</a>
    @endif
</div>
