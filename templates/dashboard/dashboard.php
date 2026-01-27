<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/7287626084.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/main.css?v=<?= filemtime('assets/main.css') ?>">
    <title>Dashboard</title>
</head>

<body class="dashboard min-vh-100">

    <header class="dashboard__navbar d-flex justify-content-end align-items-center mb-3">
        <h5 class="float-end mb-0 me-3">
            <?= $_SESSION['name'] ?>
        </h5>
        <button class="dashboard__menu-btn btn float-end" onclick="handleOpenMenu()">
            <i class="bi bi-list fs-2 text-white"></i>
        </button>
    </header>

    <nav class="dashboard__sidebar min-vh-100 d-flex flex-column flex-shrink-0 p-3">
        <h4 class="dashboard__label d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">WorkoutTracker</h4>
        <span class="my-3"></span>
        <ul class="dashboard__nav nav flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="/dashboard" class="custom-btn btn nav-link"> Dashboard </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/training" class="custom-btn btn nav-link"> Trening </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/statistics" class="custom-btn btn nav-link"> Statystyki </a>
            </li>
        </ul>
        <a href="/logout" class="custom-btn btn">Wyloguj</a>
    </nav>
</body>

</html>

<script>
    const handleOpenMenu = () => {
        const sideBar = document.querySelector('.dashboard__sidebar').classList.toggle('d-none')
    }
</script>