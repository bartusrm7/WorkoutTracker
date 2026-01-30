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

    <main class="dashboard"></main>
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
</script>