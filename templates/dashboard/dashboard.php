<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/main.css">
    <title>Dashboard</title>
</head>

<body class="dashboard min-vh-100">

    <div class="dashboard__sidebar min-vh-100 d-flex flex-column flex-shrink-0 p-3">
        <a href="/" class="dashboard__label d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none"> <span class="fs-4">WorkoutTracker</span> </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"> <a href="" class="nav-link active" aria-current="page">
                    Dashboard
                </a> </li>
            <li> <a href="" class="nav-link link-body-emphasis">
                    Trening
                </a> </li>
            <li> <a href="" class="nav-link link-body-emphasis">
                    Statystyki
                </a> </li>
            <li> <a href="" class="nav-link link-body-emphasis">
                    Customers
                </a> </li>
        </ul>
        <a href="/logout">Wyloguj</a>
    </div>

</body>

</html>