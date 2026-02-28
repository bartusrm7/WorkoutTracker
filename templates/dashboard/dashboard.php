<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/7287626084.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/main.css?v=<?= filemtime('assets/main.css') ?>">
    <title>Dashboard</title>
</head>

<body class="min-vh-100">

    <div>
        <header class="nav__navbar d-flex justify-content-end align-items-center mb-3 p-2">
            <h5 class="float-end mb-0 me-3"><?= htmlspecialchars($_SESSION['name']) ?></h5>
            <button class="nav__menu-btn btn float-end" onclick="handleOpenMenu()">
                <i class="fa-solid fa-bars fs-3 text-white"></i>
            </button>
        </header>

        <nav class="nav__sidebar min-vh-100 d-none flex-column flex-shrink-0 p-3">
            <h4 class="nav__label d-flex align-items-center mb-0">WorkoutTracker</h4>
            <hr>
            <ul class="nav__nav nav flex-column mb-auto">
                <li class="nav-item mb-3">
                    <a href="/dashboard" class="custom-btn btn nav-link"> Dashboard </a>
                </li>
                <li class="nav-item mb-3">
                    <a href="/trainings" class="custom-btn btn nav-link"> Treningi </a>
                </li>
                <li class="nav-item mb-3">
                    <a href="/history" class="custom-btn btn nav-link"> Historia </a>
                </li>
                <li class="nav-item mb-3">
                    <a href="/statistics" class="custom-btn btn nav-link"> Statystyki </a>
                </li>
                <li class="nav-item mb-3">
                    <a href="/profile" class="custom-btn btn nav-link"> Profil </a>
                </li>
            </ul>
            <a href="/logout" class="custom-accent-btn btn">Wyloguj</a>
        </nav>
    </div>

    <main class="dashboard">

        <div class="container">
            <div class="row mt-4 mb-3 justify-content-center g-4">
                <div class="col-md-10 col-lg-6 col-xl-5">
                    <div class="dashboard__container rounded-4 p-4">
                        <h3>Ostatni trening</h3>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center fs-4">
                            <div><?= htmlspecialchars(ucfirst($lastTraining['data']['name'] ?? '')) ?></div>
                            <div class="dashboard__last-training-date">
                                <?php $date = new DateTime($lastTraining['data']['end'] ?? '');
                                echo $date->format('d-m-Y') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-lg-6 col-xl-5">
                    <div class="dashboard__container rounded-4 p-4">
                        <h3>Treningi w tym tygodniu</h3>
                        <hr>
                        <div class="fs-4"><?= htmlspecialchars(count($trainingsThisWeek['data'])) ?></div>
                    </div>
                </div>
                <div class="col-md-10 col-lg-6 col-xl-5">
                    <div class="dashboard__container rounded-4 p-4">
                        <h3>Łączna objętość ostatnich 7 dni</h3>
                        <hr>
                        <div class="fs-4"><?= htmlspecialchars($last7TrainingsVolume['data']['volume']) ?>kg</div>
                    </div>
                </div>
                <div class="col-md-10 col-lg-6 col-xl-5">
                    <div class="dashboard__container rounded-4 p-4">
                        <h3>Czas treningów w tym tygodniu</h3>
                        <hr>
                        <div class="fs-4"><?= htmlspecialchars($sumTrainingDuration['data'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</body>

</html>

<script>
    const handleOpenMenu = () => {
        const sideBar = document.querySelector(".nav__sidebar");
        const menuBtn = document.querySelector('.nav__menu-btn i');

        const isHidden = sideBar.classList.toggle('d-none');
        if (isHidden) {
            sideBar.classList.remove('d-flex');
            menuBtn.classList.add('fa-bars');
            menuBtn.classList.remove('fa-bars-staggered');
        } else {
            sideBar.classList.add('d-flex');
            menuBtn.classList.remove('fa-bars');
            menuBtn.classList.add('fa-bars-staggered');
        }
    };
    document.addEventListener('click', (e) => {
        const sideBar = document.querySelector('.nav__sidebar');
        const menuBtn = document.querySelector('.nav__menu-btn');

        if (sideBar.classList.contains('d-none')) return;
        if (sideBar.contains(e.target) || menuBtn.contains(e.target)) return;

        sideBar.classList.add('d-none');
        sideBar.classList.remove('d-flex');

        const icon = document.querySelector('i');
        icon.classList.add('fa-bars');
        icon.classList.remove('fa-bars-staggered');
    })
</script>