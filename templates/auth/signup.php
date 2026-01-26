<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/main.css?v=999">
    <title>Rejestracja</title>
</head>

<body class="auth min-vh-100 d-flex justify-content-center align-items-center">

    <div class="auth__container p-3 rounded-3">
        <h3 class="auth__label text-center mb-3">Rejestracja</h3>

        <form action="/signup" method="post">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
            <div class="form-floating mt-2">
                <input id="name" type="text" name="name" class="form-control" required placeholder="">
                <label for="name">Nazwa użytkownika</label>
            </div>
            <div class="form-floating mt-2">
                <input id="email" type="email" name="email" class="form-control" required placeholder="">
                <label for="email">Email</label>
            </div>
            <div class="form-floating mt-2">
                <input id="password" type="password" name="password" class="form-control" required placeholder="">
                <label for="password">Hasło</label>
            </div>
            <?php if (!empty($errors)): ?>
                <ul class="alert alert-danger my-2">
                    <?php foreach ($errors as $error): ?>
                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
            <button class="btn custom-btn w-100 mt-3" type="submit">Utwórz konto</button>
        </form>

        <div class="mt-5">
            <a class="btn custom-btn w-100" href="/signin-form">Zaloguj się</a>
        </div>
    </div>

</body>

</html>