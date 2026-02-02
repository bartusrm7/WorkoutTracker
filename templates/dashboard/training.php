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

	<main class="training container">

		<div class="training__main-container pt-5">
			<div class="training__training-container rounded-3 p-2 col-md-8 col-xl-6 m-auto">
				<div class="training__">
					<h2 class="traning__trainin-plan-label mb-0">
						<?= ucfirst($trainingName) ?>
					</h2>
					<hr>
					<div>
						<h5>Szczegóły treningu</h5>
						<div class="d-lg-flex">
							<div class="me-lg-3">Czas trwania: <span class="fw-bold">0</span></div>
							<div class="me-lg-3">Objętość: <span class="fw-bold">0</span></div>
							<div class="me-lg-3">Ilość serii: <span class="fw-bold">0</span></div>
						</div>
					</div>
				</div>
				<hr>

				<?php foreach ($training['data'] as $row): ?>
					<div class="container mt-3">
						<div class="row">
							<table class="table table-bordered mb-0 text-center">
								<h5 class="traning__exercise-name">
									<?= ucfirst($row['name']) ?>
								</h5>
								<thead>
									<tr>
										<th class="col-2">S</th>
										<th class="col-2">C</th>
										<th class="col-2">P</th>
										<th class="col-2">Z</th>
										<th class="col-2">E</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($row['sets'] as $set): ?>
										<tr>
											<th><?= $set['sets'] ?></th>
											<td><?= $set['weight'] == 0 ? '' : $set['weight'] ?></td>
											<td><?= $set['reps'] ?></td>
											<td><?= $set['rir'] == 0 ? '' : $set['rir'] ?></td>
											<td><button class="custom-btn btn" data-bs-toggle="modal" data-bs-target="#exerciseSetEditModal" data-exercise-id="<?= $row['id'] ?>"><i class="fa-regular fa-pen-to-square"></i></button></td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
					<button class="training__exercises-data-btn custom-btn btn w-100 mt-1" data-bs-toggle="modal" data-bs-target="#exercisesDataModal" data-exercise-id="<?= $row['id'] ?>">Dodaj serie</button>
				<?php endforeach ?>

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
								<input type="hidden" name="sets" value="1">
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
								<input type="hidden" name="exerciseId" id="exerciseId">
								<input type="hidden" name="sets" value="1">
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
		document.getElementById('exercisesDataModal').classList.toggle('d-none')
	}

	document.getElementById('exercisesDataModal').addEventListener('show.bs.modal', e => {
		document.getElementById('exerciseId').value = e.relatedTarget.dataset.exerciseId;
		console.log(e.relatedTarget);
	})
</script>