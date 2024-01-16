<style>
    .nav-item:hover {
    background-color: #343a40; /* Change the background color as needed */
}

.nav-link:hover {
    color: #ffc107; /* Change the text color as needed */
}

</style>

<div class="container-fluid bg-dark text-light p-2 d-flex align-items-center justify-content-between sticky-top">
    <h3 id="greeting-admin" class="bg-dark text-white py-2 m-0">aPanel</h3>
    <a href="logout.php" class="btn btn-light btn-sm">ВИХІД 🏃🚪</a>
</div>

<div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="mt-2 text-light">ПАНЕЛЬ САЙТУ :</h4>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="tours.php">🚞 СПИСОК ТУРІВ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="facilities.php">🧑‍💻 FACILITIES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="question.php">👋🏻 СПИСОК ЗАПИТАНЬ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="settings.php">⚙️ НАЛАШТУВАННЯ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
