<nav>

    <div class="logo">
        <div class="logo-image">
            <!-- Display the company profile image fetched from the database -->
            <?php
        echo '<img src="../assets/img/Logo.jpg" alt="Company Logo" class="logoimage">';
    ?>
        </div>

        <div class="logo-name">
            <h3>Auto-Todo</h3>
        </div>
    </div>

    <div class="menu-items">
        <ul class="navLinks">
            <li class="navList">
                <a href="../views/Dashboard.php" class="sidebar-link">
                    <ion-icon name="stats-chart"></ion-icon>
                    <span class="links">Dashboard</span>
                </a>
            </li>
            <li class="navList">
                <a href="../views/Inventory.php" class="sidebar-link">
                    <ion-icon name="file-tray-full"></ion-icon>
                    <!-- Change to the desired icon name, e.g., "apps-outline" for inventory -->
                    <span class="links">Inventario</span>
                </a>
            </li>
            <li class="navList">
                <a href="../controllers/Product.php" class="sidebar-link">
                    <ion-icon name="add-circle"></ion-icon>
                    <!-- Change to the desired icon name, e.g., "bag-outline" for product -->
                    <span class="links">Añadir producto</span>
                </a>
            </li>

            <li class="navList">
                <a href="../views/Category.php" class="sidebar-link">
                    <ion-icon name="grid"></ion-icon>
                    <!-- Change to the desired icon name, e.g., "person-outline" for account -->
                    <span class="links">Categorias</span>
                </a>
            </li>

            <li class="navList">
                <a href="../views/History.php" class="sidebar-link">
                    <ion-icon name="reader"></ion-icon>
                    <!-- Change to the desired icon name, e.g., "person-outline" for account -->
                    <span class="links">Historial de movimientos</span>
                </a>
            </li>

            <li class="navList">
                <a href="../views/Suppliers.php" class="sidebar-link">
                    <ion-icon ion-icon name="cube"></ion-icon>
                    <!-- Change to the desired icon name, e.g., "person-outline" for account -->
                    <span class="links">Proveedores</span>
                </a>
            </li>

            <li class="navList">
                <a href="../views/CreateBackup.php" class="sidebar-link">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <!-- Change to the desired icon name, e.g., "person-outline" for account -->
                    <span class="links">Backups</span>
                </a>
            </li>

        </ul>
        <ul class="bottom-link">
            <li class="navList">
                <a href="../controllers/Logout.php" class="sidebar-link" onclick="return confirm('¿Seguro de que quiere cerrar sesión?');">
                    <ion-icon name="log-out"></ion-icon>
                    <span class="links">Logout</span>
                </a>
            </li>
            <li class="navList">
                <a href="../views/Users.php" class="sidebar-link">
                    <ion-icon name="people-outline"></ion-icon>
                    <span class="links">Usuarios</span>
                </a>
            </li>
        </ul>

    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const links = document.querySelectorAll(".sidebar-link");
        const currentPage = window.location.pathname.split("/").pop(); // e.g., Category.php

        links.forEach(link => {
            const linkPage = link.getAttribute("href").split("/").pop(); // e.g., Category.php
            const parentLi = link.closest("li");

            if (linkPage === currentPage && parentLi) {
                parentLi.classList.add("active");
            } else if (parentLi) {
                parentLi.classList.remove("active");
            }
        });
    });
    </script>

</nav>