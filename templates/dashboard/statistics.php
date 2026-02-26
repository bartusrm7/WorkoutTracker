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
            <div class="statistics__main-container my-5">
                <div class="row gap-3 justify-content-center">
                    <div class="statistics__container text-center col-12 col-md-10 col-lg-8">
                        <h3>Treningi</h3>
                        <canvas id="trainingCharts"></canvas>
                    </div>
                    <div class="statistics__container text-center col-12 col-md-10 col-lg-8">
                        <h3>Waga</h3>
                        <canvas id="weightCharts"></canvas>
                    </div>
                    <div class="statistics__container text-center col-12 col-md-10 col-lg-8">
                        <h3>Ćwiczenia</h3>

                        <form class="row justify-content-center align-items-end" action="/filter-exercise-statistics" method="post">
                            <div class="col-6 col-md-4">
                                <label for="start" class="form-label mb-0">Data od</label>
                                <input type="date" class="form-control" value="<?= date('Y-m-d') ?>" name="start" id="start" required placeholder="">
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="end" class="form-label mb-0">Data do</label>
                                <input type="date" class="form-control" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" name="end" id="end" required placeholder="">
                            </div>
                            <div class="form-floating col-md-4 mt-3 mt-md-0">
                                <select name="exercise" id="exercise" class="statistics__search-exercise-select form-select py-0">
                                    <option value="">Wybierz ćwiczenie</option>
                                    <?php foreach ($exercises['data'] as $exercise): ?>
                                        <option value="<?= htmlspecialchars($exercise['name']) ?>"><?= htmlspecialchars(ucfirst($exercise['name'])) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-3 w-100">
                                <button type="button" class="custom-accent-btn btn w-100 mb-3" onclick="filterExercisesStatisticsByDate()">Szukaj</button>
                            </div>
                        </form>

                        <canvas id="exerciseCharts" class="mb-3"></canvas>
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

    const chartAreaBackground = {
        id: 'chartAreaBackground',
        beforeDraw(chart) {
            const {
                ctx,
                chartArea
            } = chart;
            ctx.save();
            ctx.fillStyle = '#111';
            ctx.fillRect(chartArea.left, chartArea.top, chartArea.width, chartArea.height);
            ctx.restore();
        }
    };

    const myChartsTrainings = () => {
        new Chart(document.getElementById('trainingCharts'), {
            type: 'line',
            data: {
                labels: trainingData.map(data => {
                    const d = new Date(data.start);
                    return d.toLocaleDateString("pl-PL", {
                        day: "2-digit",
                        month: "2-digit"
                    });
                }),
                datasets: [{
                    label: 'Czas treningu',
                    data: trainingData.map(training => training.duration),
                    borderWidth: 2,
                    borderColor: '#4CAF50',
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#4CAF50',
                    pointBorderColor: '#222',
                    fill: true,
                    backgroundColor: 'rgba(76, 175, 80, 0.15)',
                }]
            },
            options: {
                interaction: {
                    intersect: false
                },
                responsive: true,
                plugins: {
                    tooltip: {
                        backgroundColor: '#222',
                        titleColor: '#fff',
                        bodyColor: '#ccc',
                        borderColor: '#444',
                        borderWidth: 2,
                        padding: 10,
                        callbacks: {
                            label: (ctx) => {
                                const v = ctx.raw;
                                const h = Math.floor(v / 3600);
                                const m = Math.floor((v % 3600) / 60);
                                const s = v % 60;

                                if (h > 0) {
                                    return `${h} h ${m} min ${s} s`;
                                }
                                if (m > 0) {
                                    return `${m} min ${s} s`;
                                }
                                return `${s} s`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#424242',
                        }
                    },
                    y: {
                        grid: {
                            color: '#424242',
                        }
                    }
                }
            },
            plugins: [chartAreaBackground]
        });
    }
    myChartsTrainings();

    const myChartsWeights = () => {
        new Chart(document.getElementById('weightCharts'), {
            type: 'line',
            data: {
                labels: weightData.map(data => {
                    const d = new Date(data.updated_date);
                    return d.toLocaleDateString("pl-PL", {
                        day: "2-digit",
                        month: "2-digit"
                    });
                }),
                datasets: [{
                    label: 'Waga',
                    data: weightData.map(data => data.weight),
                    borderWidth: 2,
                    borderColor: '#ff90f6',
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#ff90f6',
                    pointBorderColor: '#70236a',
                    fill: true,
                    backgroundColor: 'rgba(252, 76, 252, 0.15)',
                }]
            },
            options: {
                interaction: {
                    intersect: false
                },
                responsive: true,
                plugins: {
                    tooltip: {
                        backgroundColor: '#222',
                        titleColor: '#fff',
                        bodyColor: '#ccc',
                        borderColor: '#444',
                        borderWidth: 2,
                        padding: 10,
                        callbacks: {
                            label: (ctx) => ctx.raw + " kg"
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#424242'
                        }
                    },
                    y: {
                        grid: {
                            color: '#424242'
                        }
                    }
                }
            },
            plugins: [chartAreaBackground]
        });
    }
    myChartsWeights();

    let chartExercises = null;
    let exerciseChartData = [];

    const myChartsExercises = () => {
        if (chartExercises) {
            chartExercises.destroy();
        }
        chartExercises = new Chart(document.getElementById('exerciseCharts'), {
            type: 'bar',
            data: {
                labels: exerciseChartData.map(data => {
                    const d = new Date(data.createdDate);
                    return d.toLocaleDateString("pl-PL", {
                        day: "2-digit",
                        month: "2-digit"
                    });
                }),
                datasets: [{
                    label: 'Ciężar',
                    data: exerciseChartData.map(data => data.weight),
                    borderWidth: 2,
                    borderColor: '#cccccc',
                    fill: true,
                    backgroundColor: '#fa6c5a',
                }, {
                    label: 'Powtórzenia',
                    data: exerciseChartData.map(data => data.reps),
                    borderWidth: 2,
                    borderColor: '#646464',
                    fill: true,
                    backgroundColor: '#ff4f18',
                }],
            },
            options: {
                interaction: {
                    intersect: false
                },
                scales: {
                    x: {
                        grid: {
                            color: '#424242'
                        }
                    },
                    y: {
                        grid: {
                            color: '#424242'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                if (ctx.dataset.label === "Ciężar") {
                                    return ctx.raw + " kg";
                                }
                            }
                        }
                    }
                }

            },
            plugins: [chartAreaBackground]
        });
    }

    async function filterExercisesStatisticsByDate() {
        const start = document.getElementById('start').value;
        const end = document.getElementById('end').value;
        const exercise = document.getElementById('exercise').value;

        const response = await fetch('/filter-exercise-statistics', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                start: start,
                end: end,
                exercise: exercise
            })
        });
        if (!response.ok) {
            throw new Error('Błąd podczas wyszukiwania statystyk ćwiczenia', error.status);
        }
        const data = await response.json();
        exerciseChartData = data.data.map(row => ({
            createdDate: row.created_at,
            weight: row.weight,
            reps: row.reps
        }))
        myChartsExercises();
    }
</script>