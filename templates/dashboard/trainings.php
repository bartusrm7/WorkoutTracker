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

        <nav class="nav__sidebar min-vh-100 d-flex flex-column flex-shrink-0 p-3">
            <h4 class="nav__label d-flex align-items-center mb-0">WorkoutTracker</h4>
            <hr>
            <ul class="nav__nav nav flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="/dashboard" class="custom-btn btn nav-link"> Dashboard </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/trainings" class="custom-btn btn nav-link"> Trening </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/statistics" class="custom-btn btn nav-link"> Statystyki </a>
                </li>
            </ul>
            <a href="/logout" class="custom-btn btn">Wyloguj</a>
        </nav>
    </div>

    <main class="trainings container">

        <button type="button" class="custom-btn btn px-4 py-2 float-end" data-bs-toggle="modal" data-bs-target="#trainingFormModal">
            <i class="trainings__create-workout-btn fa-solid fa-dumbbell me-3"></i>Utwórz nowy trening
        </button>

        <div class="modal fade" id="trainingFormModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content training-form-modal">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold">Nowy trening</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="trainings__form-container">
                            <form action="/create-training" method="post" id="trainingForm">
                                <div class="form-floating">
                                    <input class="form-control training-input" type="text" name="trainingName" id="trainingName" required placeholder="">
                                    <label for="trainingName">Nazwa treningu</label>
                                </div>
                                <button type="button" class="custom-btn btn px-5 mt-3 float-end" onclick="handleToggleFormContainer()">Dalej</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-content exercises-form-modal d-none">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold">Nowe ćwiczenie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="trainings__form-container">
                            <form action="/create-exercises" id="exercisesForm">
                                <div class="form-floating">
                                    <input class="form-control exercises-input" type="text" name="exercisesName" id="exercisesName" required placeholder="">
                                    <label for="exercisesName">Nazwa ćwiczenia</label>
                                </div>
                                <div class="float-end">
                                    <button type="button" class="custom-btn btn px-5 mt-3" onclick="saveExercises()">Dalej</button>
                                    <button type="button" class="custom-btn btn px-5 mt-3" onclick="createNewTraining()">Zakończ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="trainings__main-container pt-5">
            <h3 class="text-center">Moje treningi</h3>
            <div class="row gap-3 justify-content-center my-3 mx-1">
                <?php foreach ($trainings as $training): ?>
                    <div class="trainings__training-container card col-sm-5 col-lg-3">
                        <div class="card-header">
                            <h5 class="card-title"><?= $training['name'] ?></h5>
                        </div>
                        <div class="card-body my-2">
                            <a href="/training?id=<?= $training['id'] ?>" class="custom-btn btn py-2 px-3 w-100">Wybierz trening</a>
                        </div>
                    </div>
                <?php endforeach ?>
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
            menuBtn.classList.remove('fa-bars');
            menuBtn.classList.add('fa-bars-staggered');
        } else {
            menuBtn.classList.add('fa-bars');
            menuBtn.classList.remove('fa-bars-staggered');
        }
    };

    const handleToggleFormContainer = () => {
        document.querySelector('.training-form-modal').classList.add('d-none');
        document.querySelector('.exercises-form-modal').classList.remove('d-none');
        saveTrainingName()
    }

    let trainingData = [];
    let exercises = []

    const saveTrainingName = () => {
        const trainingName = document.querySelector('.training-input').value
        trainingData.push({
            training: trainingName
        })
    }

    const saveExercises = () => {
        const exercisesName = document.querySelector('.exercises-input').value
        exercises.push(exercisesName)
        document.querySelector('.exercises-input').value = ''
    }

    const createNewTraining = async () => {
        trainingData.push({
            exercises: exercises
        })
        const formData = new FormData();
        formData.append('trainingName', trainingData[0].training);
        formData.append('exercisesName', JSON.stringify(exercises));

        const response = await fetch('/create-training', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
    }
</script>