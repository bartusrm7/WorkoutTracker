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

    <main class="trainings container">

        <button type="button" class="custom-accent-btn btn px-4 mt-3 float-end" data-bs-toggle="modal" data-bs-target="#trainingFormModal">
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
                                <button type="button" class="trainings__add-training-btn custom-btn btn px-5 mt-3 float-end" onclick="handleToggleFormContainer()">Dalej</button>
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
                                <div class="trainings__save-training-container float-sm-end">
                                    <button type="button" class="custom-btn btn px-5 mt-3" onclick="saveExercises()">Dalej</button>
                                    <button type="button" class="custom-accent-btn btn px-5 mt-3" onclick="createNewTraining()">Zakończ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="trainings__alert alert alert-primary fs-5 d-none px-5 text-center" role="alert">
                Nazwa treningu została dodana!
            </div>
        </div>

        <div class="trainings__main-container">
            <h3 class="text-center">Moje treningi</h3>
            <div class="row gap-3 justify-content-center my-3 mx-1 ">
                <?php if (!empty($trainings)): ?>
                    <?php foreach ($trainings as $training): ?>
                        <div class="trainings__training-container card col-sm-5 col-xl-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title my-2"><?= ucfirst($training['name']) ?></h5>
                                <div class="dropdown">
                                    <button class="trainings__dropdown-menu-btn btn" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical fs-5"></i>
                                    </button>

                                    <ul class="trainings__dropdown-menu dropdown-menu p-0">
                                        <li>
                                            <button class="dropdown-item btn custom-btn edit-training-btn" id="editTrainingBtn" data-training-id="<?= $training['id'] ?>" data-training-name="<?= $training['name'] ?>" data-bs-toggle="modal" data-bs-target="#editTrainingFormModal">Edytuj</button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item btn custom-btn" id="removeTrainingBtn" data-training-id="<?= $training['id'] ?>" onclick="removeTraining.call(this)">Usuń</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body my-1">
                                <a href="/training?id=<?= $training['id'] ?>" class="custom-btn btn py-2 px-3 w-100">Wybierz trening</a>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <h3 class="trainings__empty-training-list text-center">Brak utworzonych treningów, dodaj nowy trening</h3>
                <?php endif ?>
            </div>
        </div>

        <div class="modal fade" id="editTrainingFormModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold">Edytuj nazwę treningu</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="trainings__form-container">
                            <form action="/edit-training" method="post">
                                <div class="form-floating">
                                    <input class="form-control" type="text" name="name" id="editTrainingName" required placeholder="">
                                    <label for="editTrainingName">Nazwa treningu</label>
                                </div>
                                <button type="button" class="custom-btn btn px-5 mt-3 float-end" onclick="editTraining()">Edytuj</button>
                            </form>
                        </div>
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

    const handleToggleFormContainer = () => {
        document.querySelector('.training-form-modal').classList.add('d-none');
        document.querySelector('.exercises-form-modal').classList.remove('d-none');
        saveTrainingName()
    }

    let trainingData = [];
    let exercises = []

    const saveTrainingName = () => {
        const trainingName = document.querySelector('.training-input').value;
        trainingData.push({
            training: trainingName
        });
        const alertTraining = document.querySelector('.trainings__alert');
        alertTraining.classList.remove('d-none');
        setTimeout(() => {
            alertTraining.classList.add('d-none');
        }, 3000);
    }

    const saveExercises = () => {
        const exercisesName = document.querySelector('.exercises-input').value;
        exercises.push(exercisesName);
        document.querySelector('.exercises-input').value = '';

        const alertTraining = document.querySelector('.trainings__alert');
        alertTraining.textContent = `Ćwiczenie ${exercisesName} zostało dodane`;
        alertTraining.classList.remove('d-none');
        setTimeout(() => {
            alertTraining.classList.add('d-none');
        }, 3000);
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
        if (!response.ok) {
            throw new Error('Błąd podczas tworzenia treningu', error.status);
        }
        const data = await response.json();
        const alertTraining = document.querySelector('.trainings__alert');
        if (!data.success === false) {
            window.location.reload();
            alertTraining.textContent = 'Trening został zapisany';
            alertTraining.classList.remove('d-none');
            setTimeout(() => {
                alertTraining.classList.add('d-none');
            }, 3000);
        }
    }

    let trainingId;
    let trainingName;
    document.querySelectorAll('.edit-training-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            trainingId = e.target.dataset.trainingId;
            trainingName = e.target.dataset.trainingName;
            document.getElementById('editTrainingName').value = trainingName;
        });
    })

    async function editTraining() {
        const id = trainingId;
        const name = document.getElementById('editTrainingName').value;

        const response = await fetch('/edit-training', {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id,
                name: name
            })
        });
        if (!response.ok) {
            throw new Error('Błąd podczas edycji nazwy treningu', error.status);
        }
        const data = await response.json();
        if (!data.success === false) {
            window.location.reload();
        }
    }

    async function removeTraining() {
        const id = this.dataset.trainingId;
        const response = await fetch('/delete-training', {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id
            })
        })
        if (!response.ok) {
            throw new Error('Błąd podczas usuwania treningu', error.status);
        }
        const data = await response.json();
        if (!data.success === false) {
            window.location.reload();
        }
    }

    const alertList = document.querySelectorAll('.alert')
    const alerts = [...alertList].map(element => new bootstrap.Alert(element))
</script>