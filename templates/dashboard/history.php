<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/7287626084.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/main.css?v=<?= filemtime('assets/main.css') ?>">
    <title>Historia</title>
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

    <main class="history container">

        <form class="row justify-content-center align-items-end" action="/filter-trainings" method="post">
            <div class="col-6 col-md-4 col-lg-3">
                <label for="startDate" class="form-label mb-0">Data od</label>
                <input type="date" class="form-control" value="<?= date('Y-m-d') ?>" name="startDate" id="startDate" required placeholder="">
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <label for="endDate" class="form-label mb-0">Data do</label>
                <input type="date" class="form-control" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" name="endDate" id="endDate" required placeholder="">
            </div>
            <div class="col-md-4 col-lg-3 mt-3 mt-md-0">
                <button type="submit" class="custom-accent-btn btn w-100">Szukaj</button>
            </div>
        </form>

        <?php if (empty($trainings['data']['trainings'])): ?>
            <h3 class="history__empty-training-list text-center">Wybierz datę potrzebną do wyświetlenia historii treningów</h3>
        <?php else: ?>
            <?php foreach ($trainings['data']['trainings'] as $training): ?>

                <div class="history__main-container mb-4 mx-1">
                    <div class="history__training-container rounded-4 mb-4 p-3 col-md-10 col-lg-8 col-xl-6 m-auto">
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="history__training-plan-label mb-0">
                                    <?= htmlspecialchars(ucfirst($training['name'])) ?>
                                </h2>
                                <div class="history__training-date">
                                    <?= date('d.m.Y', strtotime(htmlspecialchars($training['start']))) ?>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <h5>Szczegóły treningu</h5>
                                <div class="d-lg-flex">
                                    <?php $duration = $training['duration'] ?? 0 ?>
                                    <div class="me-lg-3">Czas trwania: <span class="history__details fw-bold"><?= htmlspecialchars($duration) ?></span></div>
                                    <div class="me-lg-3">Objętość: <span class="history__details fw-bold"><?= htmlspecialchars($training['weightVolume']) ?>kg</span></div>
                                    <div class="me-lg-3">Ilość serii: <span class="history__details fw-bold"><?= htmlspecialchars($training['setsVolume']) ?></span></div>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-0">

                        <div class="container">
                            <div class="row">
                                <?php foreach ($training['exercises'] as $exercise): ?>
                                    <table class="table table-bordered mb-0 text-center rounded-3">
                                        <div class="mt-3 p-0">
                                            <div class="mb-2">
                                                <h5 class="traning__exercise-name mb-0">
                                                    <?= htmlspecialchars(ucfirst($exercise['name'])) ?>
                                                </h5>
                                            </div>
                                            <?php if ($exercise['note']): ?>
                                                <div class="history__note-container mb-2 rounded-3 p-2">
                                                    <div class="history__note-field">
                                                        <?= htmlspecialchars($exercise['note']) ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="mb-2">
                                                    <?php if ($exercise['note']): ?>
                                                        <div class="history__note-field">
                                                            <?= htmlspecialchars($exercise['note']) ?>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                        <thead>
                                            <tr>
                                                <th class=" training__th col-2">S
                                                    <span type="button" class="training__tool-tip-btn mx-sm-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Numer serii"><i class="bi bi-info-circle"></i></span>
                                                </th>
                                                <th class="training__th col-2">C
                                                    <span type="button" class="training__tool-tip-btn mx-sm-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ciężar w serii"><i class="bi bi-info-circle"></i></span>
                                                </th>
                                                <th class="training__th col-2">P
                                                    <span type="button" class="training__tool-tip-btn mx-sm-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Powtórzenia w serii"><i class="bi bi-info-circle"></i></span>
                                                </th>
                                                <th class="training__th col-2">Z
                                                    <span type="button" class="training__tool-tip-btn mx-sm-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Zapas powtórzeń"><i class="bi bi-info-circle"></i></span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="setsData">
                                            <?php foreach ($exercise['sets'] as $set): ?>
                                                <tr data-set-id="<?= htmlspecialchars($set['sets']) ?>">
                                                    <th><?= htmlspecialchars($set['sets']) ?></th>
                                                    <td><?= $set['weight'] == 0 ? '' : htmlspecialchars($set['weight']) ?></td>
                                                    <td><?= htmlspecialchars($set['reps']) ?></td>
                                                    <td><?= $set['rir'] == 0 ? '' : htmlspecialchars($set['rir']) ?></td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>

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
    });

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
</script>