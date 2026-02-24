<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/7287626084.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/main.css?v=<?= filemtime('assets/main.css') ?>">
    <title>Statystyki</title>
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

    <main class="statistics">

        <div class="container">
            <div class="statistics__main-container">
                <div class="row">
                    <div class="statistics__container col-12 col-lg-8">
                        <h3>Waga</h3>
                        <canvas id="weightCharts"></canvas>
                    </div>
                    <div class="statistics__container col-12 col-lg-8">
                        <h3>Ćwiczenia</h3>
                        <select name="" id="">
                            <option value="">pull ups</option>
                            <option value="">chin ups</option>
                            <option value="">dipy</option>
                        </select>
                        <canvas id="trainingCharts"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </main>

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    const weightData = <?= json_encode($weightCharts['data']) ?>;
    const trainingData = <?= json_encode($trainingCharts['data']) ?>;

    const months = ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];

    const myChartsWeights = () => {
        new Chart(document.getElementById('weightCharts'), {
            type: 'line',
            data: {
                labels: months.map(data => data),
                datasets: [{
                    label: 'Waga',
                    data: weightData.map(weight => weight.weight),
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    myChartsWeights();

    const myChartsTrainings = () => {
        new Chart(document.getElementById('trainingCharts'), {
            type: 'line',
            data: {
                labels: months.map(data => data),
                datasets: [{
                    label: 'Treningi',
                    data: trainingData.map(training => training),
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    myChartsTrainings();
</script>