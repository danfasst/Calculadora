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
            <button type="submit" name="calcular">Calcular</button>
            <button type="submit" name="salvar">Salvar na Memória</button>
            <button type="submit" name="recuperar">Recuperar da Memória</button>
            <button type="submit" name="apagar">Apagar Histórico</button>
            <button type="submit" name="consultar">Consultar Histórico</button>
        </form>
        <div class="result">
            <?php
            session_start();

            // Funções para memória
            function salvarMemoria($numero1, $operacao, $numero2, $resultado) {
                $_SESSION['memoria'][] = array("numero1" => $numero1, "operacao" => $operacao, "numero2" => $numero2, "resultado" => $resultado);
                echo "<p>Valor salvo na memória!</p>";
            }

            function recuperarMemoria() {
                if (isset($_SESSION['memoria']) && count($_SESSION['memoria']) > 0) {
                    echo "<p>Escolha um cálculo para recuperar:</p>";
                    echo "<form method='post'>";
                    echo "<select name='memoria_index'>";
                    foreach ($_SESSION['memoria'] as $index => $calc) {
                        echo "<option value='$index'>{$calc['numero1']} {$calc['operacao']} {$calc['numero2']} = {$calc['resultado']}</option>";
                    }
                    echo "</select>";
                    echo "<button type='submit' name='recuperar_memoria'>Recuperar</button>";
                    echo "</form>";
                } else {
                    echo "<p>Memória vazia!</p>";
                }
            }

            // Funções para histórico
            function registrarOperacao($numero1, $operacao, $numero2, $resultado) {
                if (!isset($_SESSION['historicoOperacoes'])) {
                    $_SESSION['historicoOperacoes'] = [];
                }

                $operacaoAtual = [
                    "numero1" => $numero1,
                    "operacao" => $operacao,
                    "numero2" => $numero2,
                    "resultado" => $resultado
                ];
                array_push($_SESSION['historicoOperacoes'], $operacaoAtual);
            }

            function exibirHistorico() {
                if (empty($_SESSION['historicoOperacoes'])) {
                    echo "<p>Histórico vazio!</p>";
                } else {
                    echo "<p><b>Histórico de Operações:</b></p>";
                    echo "<table>";
                    echo "<tr><th>Número 1</th><th>Operação</th><th>Número 2</th><th>Resultado</th></tr>";
                    foreach ($_SESSION['historicoOperacoes'] as $operacao) {
                        echo "<tr>";
                        echo "<td>{$operacao['numero1']}</td>";
                        echo "<td>{$operacao['operacao']}</td>";
                        echo "<td>{$operacao['numero2']}</td>";
                        echo "<td>{$operacao['resultado']}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }

            function apagarHistorico() {
                unset($_SESSION['historicoOperacoes']);
                echo "<p>Histórico apagado!</p>";
            }

            // Processamento de dados do formulário para os botões
            if (isset($_POST['calcular'])) {
                $numero1 = $_POST['numero1'];
                $numero2 = $_POST['numero2'];
                $operacao = $_POST['operacao'];

                // Verificar se há valores na memória para usar
                if (isset($_SESSION['memoria_numero1'], $_SESSION['memoria_operacao'], $_SESSION['memoria_numero2'])) {
                    $numero1 = $_SESSION['memoria_numero1'];
                    $operacao = $_SESSION['memoria_operacao'];
                    $numero2 = $_SESSION['memoria_numero2'];
                    unset($_SESSION['memoria_numero1'], $_SESSION['memoria_operacao'], $_SESSION['memoria_numero2']);
                }

                // Executar operação
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

                // Registrar operação no histórico
                registrarOperacao($numero1, $operacao, $numero2, $resultado);
                echo "<p>$resultado</p>";
            }

            // Processamento de dados do formulário para os botões
            if (isset($_POST['salvar'])) {
                if (isset($_POST['numero1'], $_POST['operacao'], $_POST['numero2'])) {
                    $numero1 = $_POST['numero1'];
                    $operacao = $_POST['operacao'];
                    $numero2 = $_POST['numero2'];
                    $resultado = calcularResultado($numero1, $operacao, $numero2);
                    salvarMemoria($numero1, $operacao, $numero2, $resultado);
                } else {
                    echo "<p>Preencha todos os campos antes de salvar na memória!</p>";
                }
            }

            if (isset($_POST['recuperar_memoria'])) {
                $index = $_POST['memoria_index'];
                $calc = $_SESSION['memoria'][$index];
                $numero1 = $calc['numero1'];
                $operacao = $calc['operacao'];
                $numero2 = $calc['numero2'];
                echo "<script>document.getElementsByName('numero1')[0].value = '$numero1';</script>";
                echo "<script>document.getElementsByName('operacao')[0].value = '$operacao';</script>";
                echo "<script>document.getElementsByName('numero2')[0].value = '$numero2';</script>";
            }

            if (isset($_POST['apagar'])) {
                apagarHistorico();
            }

            if (isset($_POST['consultar'])) {
                exibirHistorico();
            }

            function fatorial($numero)
            {
                if ($numero <= 1) {
                    return 1;
                } else {
                    return $numero * fatorial($numero - 1);
                }
            }

            function calcularResultado($numero1, $operacao, $numero2) {
                switch ($operacao) {
                    case '+':
                        return $numero1 + $numero2;
                    case '-':
                        return $numero1 - $numero2;
                    case '*':
                        return $numero1 * $numero2;
                    case '/':
                        if ($numero2 != 0) {
                            return $numero1 / $numero2;
                        } else {
                            return "Divisão por zero!";
                        }
                    case 'fatorial':
                        return fatorial($numero1);
                    case 'potencia':
                        return pow($numero1, $numero2);
                    default:
                        return "Operação inválida!";
                }
            }
            ?>
        </div>
    </div>

</body>

</html>
