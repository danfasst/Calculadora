<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="calculator">
        <h2>Calculadora PHP</h2>
        <form method="post">
            <input type="number" name="numero1" placeholder="Número 1" required>
            <select name="operacao" required>
                <option value="+">+</option>
                <option value="-">-</option>
                <option value="*">*</option>
                <option value="/">/</option>
                <option value="fatorial">Fatorial</option>
                <option value="potencia">Potência</option>
            </select>
            <input type="number" name="numero2" placeholder="Número 2" required>
            <button type="submit">Calcular</button>
            <button type="submit" name="salvar">Salvar na Memória</button>
            <button type="submit" name="recuperar">Recuperar da Memória</button>
        </form>
        <div class="result">
            <?php
            if (isset($_POST['numero1']) && isset($_POST['numero2']) && isset($_POST['operacao'])) {
                $numero1 = $_POST['numero1'];
                $numero2 = $_POST['numero2'];
                $operacao = $_POST['operacao'];
                switch ($operacao) {
                    case '+':
                        $resultado = $numero1 + $numero2;
                        break;
                    case '-':
                        $resultado = $numero1 - $numero2;
                        break;
                    case '*':
                        $resultado = $numero1 * $numero2;
                        break;
                    case '/':
                        if ($numero2 != 0) {
                            $resultado = $numero1 / $numero2;
                        } else {
                            $resultado = "Divisão por zero!";
                        }
                        break;
                    case 'fatorial':
                        $resultado = fatorial($numero1);
                        break;
                    case 'potencia':
                        $resultado = pow($numero1, $numero2);
                        break;
                    default:
                        $resultado = "Operação inválida!";
                        break;
                }
                echo "<p>$resultado</p>";
            }

            function fatorial($numero)
            {
                if ($numero <= 1) {
                    return 1;
                } else {
                    return $numero * fatorial($numero - 1);
                }
            }
            ?>
        </div>
    </div>

</body>

</html>