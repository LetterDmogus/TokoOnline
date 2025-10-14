<div id="sidebar" class="collapsed sidebar d-flex flex-column p-3">

    @if (Session('role') == '3' || Session('role') == '2')
        <a href="/" class="sidebar-brand mb-3 text-decoration-none text-white fw-bold fs-4"><i class="bi bi-house"></i>
            Dashboard</a>
        <a href="/product" class="sidebar-link text-white"><i class="bi bi-box"></i> Products</a>
        <a href="/user" class="sidebar-link text-white"><i class="bi bi-people"></i> Users</a>
        <a href="/logout" class="sidebar-link text-danger fw-bold"><i class="bi bi-box-arrow-right"></i> Logout</a>
    @elseif(Session('role') == '1')
        <a href="/" class="sidebar-brand mb-3 text-decoration-none text-white fw-bold fs-4"><i class="bi bi-house"></i>
            Dashboard</a>
        <a href="/product" class="sidebar-link text-white"><i class="bi bi-box"></i> Products</a>
        <a href="/logout" class="sidebar-link text-danger fw-bold"><i class="bi bi-box-arrow-right"></i> Logout</a>
    @else
        <a href="/" class="sidebar-brand mb-3 text-decoration-none text-white fw-bold fs-4"><i class="bi bi-house"></i>
            Dashboard</a>
        <a href="/login" class="sidebar-link text-primary fw-bold"><i class="bi bi-box-arrow-right"></i> Logout</a>
    @endif
</div>