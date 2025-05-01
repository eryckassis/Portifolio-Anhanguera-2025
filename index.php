<?php include('layouts/header.php'); ?>
<body class="container mt-5">
    <h1 class="mb-4">Descubra seu Signo</h1>

    <form id="signo-form" method="POST" action="show_zodiac_sign.php">
        <div class="mb-3">
            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
            <input 
                type="date" 
                class="form-control" 
                id="data_nascimento" 
                name="data_nascimento" 
                aria-label="Insira sua data de nascimento"
                required>
        </div>
        <button type="submit" class="btn btn-primary">Ver meu signo</button>
    </form>
</body>
</html>