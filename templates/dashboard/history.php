<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/7287626084.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/main.css?v=<?= filemtime('assets/main.css') ?>">
    <title>Trening</title>
</head>

<body class="min-vh-100">

    <div>
        <header class="nav__navbar d-flex justify-content-end align-items-center mb-3 p-2">
            <h5 class="float-end mb-0 me-3"><?= $_SESSION['name'] ?></h5>
            <button class="nav__menu-btn btn float-end" onclick="handleOpenMenu()">
                <i class="fa-solid fa-bars fs-3 text-white"></i>
            </button>
        </header>

        <nav class="nav__sidebar min-vh-100 d-none flex-column flex-shrink-0 p-3">
            <h4 class="nav__label d-flex align-items-center mb-0">WorkoutTracker</h4>
            <hr>
            <ul class="nav__nav nav flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="/dashboard" class="custom-btn btn nav-link"> Dashboard </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/trainings" class="custom-btn btn nav-link"> Treningi </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/history" class="custom-btn btn nav-link"> Historia </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/statistics" class="custom-btn btn nav-link"> Statystyki </a>
                </li>
            </ul>
            <a href="/logout" class="custom-accent-btn btn">Wyloguj</a>
        </nav>
    </div>

    <main class="history container">

        <form class="row justify-content-center align-items-end" action="/filter-trainings" method="get">
            <div class="col-6 col-md-4 col-lg-3">
                <label for="exampleFormControlInput1" class="form-label mb-0">Data od</label>
                <input type="date" class="form-control" name="startDate" id="exampleFormControlInput1" required placeholder="">
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <label for="exampleFormControlInput1" class="form-label mb-0">Data do</label>
                <input type="date" class="form-control" name="endDate" id="exampleFormControlInput1" required placeholder="">
            </div>
            <div class="col-md-4 col-lg-3 mt-3 mt-md-0">
                <button type="submit" class="custom-accent-btn btn w-100">Szukaj</button>
            </div>
        </form>

        <div class="history__main-container">

            <?php foreach ($training as $row): ?>
                <div class="container mt-3">
                    <div class="row">
                        <table class="table table-bordered mb-0 text-center">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="traning__exercise-name mb-0">
                                        <?= ucfirst($row['name']) ?>
                                    </h5>
                                </div>
                                <div class="mb-2">
                                    <?php if ($row['note']): ?>
                                        <?= $row['note'] ?>
                                    <?php endif ?>
                                </div>
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
                                    <th class="training__th col-2">E
                                        <span type="button" class="training__tool-tip-btn mx-sm-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edycja serii"><i class="bi bi-info-circle"></i></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="setsData">
                                <?php foreach ($row['sets'] as $set): ?>
                                    <tr data-set-id="<?= $set['sets'] ?>">
                                        <th><?= $set['setNum'] ?></th>
                                        <td><?= $set['weight'] == 0 ? '' : $set['weight'] ?></td>
                                        <td><?= $set['reps'] ?></td>
                                        <td><?= $set['rir'] == 0 ? '' : $set['rir'] ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="training__dropdown-menu-btn btn dropdown-set-menu-btn" data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis-vertical fs-5"></i>
                                                </button>

                                                <ul class="training__dropdown-menu dropdown-menu p-0">
                                                    <li>
                                                        <button class="dropdown-item btn custom-btn" data-bs-toggle="modal" data-bs-target="#exerciseSetEditModal" data-exercise-id="<?= $row['id'] ?>" data-set-id="<?= $set['sets'] ?>" data-id="<?= $set['id'] ?>">Edytuj</button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item btn custom-btn" id="" data-bs-toggle="modal" data-exercise-id="<?= $row['id'] ?>" data-set-id="<?= $set['sets'] ?>" data-id="<?= $set['id'] ?>">Usuń</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach ?>
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
</script>