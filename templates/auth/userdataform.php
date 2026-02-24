<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/main.css?v=<?= filemtime('assets/main.css') ?>">
    <title>Formularz użytkownika</title>
</head>

<body class="auth min-vh-100 d-flex justify-content-center align-items-center">

    <div class="auth__container p-3 rounded-3">
        <h3 class="auth__label text-center mb-3">Uzupełnij profil użytkownika</h3>

        <form action="/user-data" method="post">
            <div id="age" class="form-floating mt-2">
                <input min="12" max="99" type="number" name="age" class="form-control" placeholder="" required>
                <label for="age">Wiek</label>
            </div>
            <div id="height" class="form-floating mt-2 d-none">
                <input min="120" max="220" type="number" name="height" class="form-control" placeholder="" required>
                <label for="height">Wzrost</label>
            </div>
            <div id="weight" class="form-floating mt-2 d-none">
                <input min="30" max="180" type="number" name="weight" class="form-control" placeholder="" required>
                <label for="weight">Waga</label>
            </div>
            <div id="goalWeight" class="form-floating mt-2 d-none">
                <input min="30" max="180" type="number" name="goalWeight" class="form-control" placeholder="" required>
                <label for="goalWeight">Waga docelowa</label>
            </div>
            <div id="goal" class="form-floating mt-2 d-none">
                <select name="goal" class="form-select py-0">
                    <option selected>Cel treningowy</option>
                    <option value="Wzrost masy mięśniowej">Wzrost masy mięśniowej</option>
                    <option value="Utrzymanie wagi">Utrzymanie wagi</option>
                    <option value="Redukcja tkanki tłuszczowej">Redukcja tkanki tłuszczowej</option>
                </select>
            </div>

            <button class="btn custom-btn mt-3 w-100 last-step-btn" type="button" onclick="handleNextStepUserFormData()">Dalej</button>
            <button class="btn custom-accent-btn mt-3 w-100 submit-step-btn d-none" type="button" onclick="sendUserDataDetails()">Zakończ</button>
        </form>
    </div>

</body>

</html>

<script>
    let userData = {
        age: null,
        height: null,
        weight: null,
        goalWeight: null,
        goal: null
    };
    const steps = ['age', 'height', 'weight', 'goalWeight', 'goal'];
    let currentStep = 0;

    const handleNextStepUserFormData = () => {
        const field = steps[currentStep];
        const inputValue = document.querySelector(`[name="${field}"]`).value;
        userData[field] = inputValue;

        document.getElementById(field).classList.add('d-none');
        currentStep++;
        if (currentStep === steps.length) {
            document.querySelector('.last-step-btn').classList.add('d-none');
            document.querySelector('.submit-step-btn').classList.remove('d-none');
        }
        document.getElementById(steps[currentStep]).classList.remove('d-none');
    }

    async function sendUserDataDetails() {
        const response = await fetch('/user-data', {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        });
        if (!response.ok) {
            throw new Error('Błąd podczas dodawania danych użytkownika', error.status);
        }
        const data = await response.json();
        if (data.success) {
            window.location.href = '/dashboard'
        }
    }
</script>