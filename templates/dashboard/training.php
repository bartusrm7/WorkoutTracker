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
			<a href="/logout" class="custom-accent-btn btn">Wyloguj</a>
		</nav>
	</div>

	<main class="training container">

		<a href="/trainings" class="custom-accent-btn btn px-5 mt-3 float-end">Wróć</a>

		<div class="training__main-container">
			<div class="training__training-container rounded-3 p-2 col-md-8 col-xl-6 m-auto">
				<div>
					<div class="d-flex justify-content-between">
						<h2 class="traning__trainin-plan-label mb-0">
							<?= ucfirst($trainingName) ?>
						</h2>
						<button class="custom-accent-btn btn px-3">Rozpocznij trening</button>
					</div>
					<hr>
					<div>
						<h5>Szczegóły treningu</h5>
						<div class="d-lg-flex">
							<div class="me-lg-3">Czas trwania: <span class="fw-bold">0</span></div>
							<div class="me-lg-3">Objętość: <span class="fw-bold"><?= $setsVolumeWeight ?>kg</span></div>
							<div class="me-lg-3">Ilość serii: <span class="fw-bold"><?= $setsVolumeAmount ?></span></div>
						</div>
					</div>
				</div>
				<hr>

				<?php foreach ($training['data'] as $row): ?>
					<div class="container mt-3">
						<div class="row">
							<table class="table table-bordered mb-0 text-center">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<h5 class="traning__exercise-name mb-0">
										<?= ucfirst($row['name']) ?>
									</h5>
									<div class="dropdown">
										<button class="training__dropdown-menu-btn btn" data-bs-toggle="dropdown">
											<i class="fa-solid fa-ellipsis-vertical fs-4"></i>
										</button>

										<ul class="training__dropdown-menu dropdown-menu p-0">
											<li>
												<button class="dropdown-item btn custom-btn" id="editExerciseBtn" data-exercise-id="<?= $row['id'] ?>" data-exercise-name="<?= $row['name'] ?>" data-bs-toggle="modal" data-bs-target="#editExerciseFormModal">Edytuj</button>
											</li>
											<li>
												<button class="dropdown-item btn custom-btn" id="removeExerciseBtn" data-exercise-id="<?= $row['id'] ?>" onclick="removeExercise.call(this)">Usuń</button>
											</li>
										</ul>
									</div>
								</div>
								<thead>
									<tr>
										<th class="training__th col-2">S
											<span type="button" class="training__tool-tip-btn mx-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Numer serii"><i class="bi bi-info-circle"></i></span>
										</th>
										<th class="training__th col-2">C
											<span type="button" class="training__tool-tip-btn mx-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ciężar w serii"><i class="bi bi-info-circle"></i></span>
										</th>
										<th class="training__th col-2">P
											<span type="button" class="training__tool-tip-btn mx-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Powtórzenia w serii"><i class="bi bi-info-circle"></i></span>
										</th>
										<th class="training__th col-2">Z
											<span type="button" class="training__tool-tip-btn mx-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Zapas powtórzeń"><i class="bi bi-info-circle"></i></span>
										</th>
										<th class="training__th col-2">E
											<span type="button" class="training__tool-tip-btn mx-1 my-2 p-0 px-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edycja serii"><i class="bi bi-info-circle"></i></span>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($row['sets'] as $set): ?>
										<tr>
											<th><?= $set['setNum'] ?></th>
											<td><?= $set['weight'] == 0 ? '' : $set['weight'] ?></td>
											<td><?= $set['reps'] ?></td>
											<td><?= $set['rir'] == 0 ? '' : $set['rir'] ?></td>
											<td><button class="custom-btn btn" data-bs-toggle="modal" data-bs-target="#exerciseSetEditModal" data-exercise-id="<?= $row['id'] ?>" data-set-id="<?= $set['sets'] ?>" data-id="<?= $set['id'] ?>"><i class="fa-regular fa-pen-to-square"></i></button></td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
					<button class="training__exercises-data-btn custom-btn btn w-100 mt-1" data-bs-toggle="modal" data-bs-target="#exercisesDataModal" data-exercise-id="<?= $row['id'] ?>">Dodaj serie</button>
				<?php endforeach ?>

				<button class="training__exercises-data-btn custom-accent-btn btn w-100 mt-5" data-bs-toggle="modal" data-bs-target="#trainingFormModal" data-exercise-id="<?= $row['id'] ?>">Dodaj nowe ćwiczenie</button>
			</div>
		</div>

		<div class="modal fade" id="editExerciseFormModal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5 fw-bold">Edytuj nazwę treningu</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="training__form-container">
							<form action="/edit-training" method="post">
								<div class="form-floating">
									<input class="form-control" type="text" name="name" id="editExerciseName" required placeholder="">
									<label for="editExerciseName">Nazwa treningu</label>
								</div>
								<button type="button" class="custom-btn btn px-5 mt-3 float-end" onclick="editExercise()">Edytuj</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="exercisesDataModal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5 fw-bold">Uzupełnij serie</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="training__form-container">
							<form action="/add-exercise-set" method="post" id="exerciseSet">
								<input type="hidden" name="exerciseId" id="exerciseId">
								<input type="hidden" name="sets" id="sets">
								<div class="d-flex gap-3">
									<div class="form-floating">
										<input class="form-control" type="number" min="0" name="weight" id="weight" placeholder="">
										<label for="weight">Ciężar (kg)</label>
									</div>
									<div class="form-floating">
										<input class="form-control" type="number" min="1" name="reps" id="reps" required placeholder="">
										<label for="reps">Powtórzeń</label>
									</div>
									<div class="form-floating">
										<input class="form-control" type="number" min="0" name="rir" id="rir" placeholder="">
										<label for="rir">Zapas powtórzeń</label>
									</div>
								</div>
								<button type="submit" class="custom-btn btn px-5 mt-3 float-end">Dalej</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="exerciseSetEditModal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5 fw-bold">Edytuj serie</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="training__form-container">
							<form action="/edit-exercise-set" method="post" id="editExerciseSet">
								<input type="hidden" name="id" id="editId">
								<input type="hidden" name="exerciseId" id="editExerciseId">
								<input type="hidden" name="sets" id="setId">
								<div class="d-flex gap-3">
									<div class="form-floating">
										<input class="form-control" type="number" min="0" name="weight" id="weightSet" placeholder="">
										<label for="weightSet">Ciężar (kg)</label>
									</div>
									<div class="form-floating">
										<input class="form-control" type="number" min="1" name="reps" id="repsSet" required placeholder="">
										<label for="repsSet">Powtórzeń</label>
									</div>
									<div class="form-floating">
										<input class="form-control" type="number" min="0" name="rir" id="rirSet" placeholder="">
										<label for="rirSet">Zapas powtórzeń</label>
									</div>
								</div>
								<button type="submit" class="custom-btn btn px-5 mt-3 float-end">Dalej</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="trainingFormModal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content exercises-form-modal">
					<div class="modal-header">
						<h1 class="modal-title fs-5 fw-bold">Nowe ćwiczenie</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="training__form-container">
							<form action="/new-exercise" id="exercisesForm">
								<div class="form-floating">
									<input class="form-control exercises-input" type="text" name="name" id="exercisesName" required placeholder="">
									<label for="exercisesName">Nazwa ćwiczenia</label>
								</div>
								<button type="button" class="custom-btn btn float-end px-5 mt-3" onclick="addNewExercise()">Dodaj</button>
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
		const sideBar = document.querySelector(" .nav__sidebar");
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

	const handleAddSetExercisesData = () => {
		document.getElementById('exercisesDataModal').classList.toggle('d-none');
	}

	document.getElementById('exercisesDataModal').addEventListener('show.bs.modal', e => {
		document.getElementById('exerciseId').value = e.relatedTarget.dataset.exerciseId;
		document.getElementById('sets').value = e.relatedTarget.dataset.sets;
	})

	document.getElementById('exerciseSetEditModal').addEventListener('show.bs.modal', async (e) => {
		const id = e.relatedTarget.dataset.id;
		const response = await fetch(`/set-id?id=${id}`);
		const data = await response.json();

		document.getElementById('editExerciseId').value = e.relatedTarget.dataset.exerciseId;
		document.getElementById('setId').value = e.relatedTarget.dataset.setId;
		document.getElementById('editId').value = e.relatedTarget.dataset.id;

		document.getElementById('weightSet').value = data.data.weight;
		document.getElementById('repsSet').value = data.data.reps;
		document.getElementById('rirSet').value = data.data.rir;
	})

	const addNewExercise = async () => {
		const exercisesName = document.getElementById('exercisesName').value;
		const response = await fetch('new-exercise', {
			method: "POST",
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				exercisesName
			})
		});
		if (!response.ok) {
			throw new Error('Błąd podczas dodawania ćwiczenia', error.status);
		}
		const data = await response.json();
		if (!data.success === false) {
			window.location.reload();
		}
	}

	let exerciseId;
	let exerciseName;
	document.getElementById('editExerciseBtn').addEventListener('click', e => {
		exerciseId = e.target.dataset.exerciseId;
		exerciseName = e.target.dataset.exerciseName;
		document.getElementById('editExerciseName').value = exerciseName;
	})

	async function editExercise() {
		const id = exerciseId;
		const name = document.getElementById('editExerciseName').value;

		const response = await fetch('/edit-exercise', {
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
			throw new Error('Błąd podczas edytowania ćwiczenia', error.status);
		}
		const data = await response.json();
		if (!data.success === false) {
			window.location.reload();
		}
	}

	async function removeExercise() {
		const id = this.dataset.exerciseId;
		const response = await fetch('/delete-exercise', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				id: id
			})
		});
		if (!response.ok) {
			throw new Error('Błąd podczas usuwania ćwiczenia', error.status);
		}
		const data = await response.json();
		if (!data.success === false) {
			window.location.reload();
		}
	}

	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
</script>