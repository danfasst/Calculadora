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
      <input type="number" name="numero1" id="numero1" placeholder="Número 1">
      <select name="operacao" id="operacao" required>
        <option value="+">+</option>
        <option value="-">-</option>
        <option value="*">*</option>
        <option value="/">/</option>
        <option value="fatorial">Fatorial</option>
        <option value="potencia">Potência</option>
      </select>
      <input type="number" name="numero2" id="numero2" placeholder="Número 2">
      <div class="button-container">
        <button type="submit" name="calcular">Calcular</button>
        <button type="submit" name="salvar">Salvar na Memória</button>
        <button type="submit" name="recuperar">Recuperar da Memória</button>
        <button type="submit" name="apagar">Apagar Histórico</button>
        <button type="submit" name="consultar">Consultar Histórico</button>
      </div>
    </form>
    <div class="result">
      <?php
      session_start();

      // Funções para memória
      function salvarMemoria($numero1, $operacao, $numero2)
      {
        if (!isset($_SESSION['memoriaOperacoes'])) {
          $_SESSION['memoriaOperacoes'] = [];
        }

        $operacaoAtual = [
          "numero1" => $numero1,
          "operacao" => $operacao,
          "numero2" => $numero2
        ];
        array_push($_SESSION['memoriaOperacoes'], $operacaoAtual);
        echo "<p>Operação salva na memória!</p>";
      }

      function recuperarMemoria()
      {
        if (isset($_SESSION['memoriaOperacoes'])) {
          echo "<label for='operacoes_salvas'>Operações Salvas:</label>";
          echo "<select id='operacoes_salvas' name='operacao_salva'>";
          foreach ($_SESSION['memoriaOperacoes'] as $index => $operacao) {
            $num1 = $operacao['numero1'];
            $op = $operacao['operacao'];
            $num2 = $operacao['numero2'];
            echo "<option value='$index'>$num1 $op $num2</option>";
          }
          echo "</select>";
          echo "<button type='submit' name='recuperar_operacao'>Recuperar</button>";
        } else {
          echo "<p>Memória vazia!</p>";
        }
      }

      // Função para registrar a operação no histórico
      function registrarOperacao($numero1, $operacao, $numero2, $resultado)
      {
        if (!isset($_SESSION['historicoOperacoes'])) {
          $_SESSION['historicoOperacoes'] = [];
        }

        $operacaoAtual = [
          "numero1" => $numero1,
          "operacao" => $operacao,
          "numero2" => $numero2,
          "resultado" => $resultado
        ];


        $_SESSION['historicoOperacoes'][] = $operacaoAtual;
      }

      // Função para apagar o histórico de operações
      function apagarHistorico()
      {
        if (isset($_SESSION['historicoOperacoes'])) {
          unset($_SESSION['historicoOperacoes']);
          echo "<p>Histórico apagado!</p>";
        } else {
          echo "<p>Histórico não encontrado ou já está vazio!</p>";
        }
      }

      function consultarHistorico()
      {
        if (isset($_SESSION['historicoOperacoes'])) {
          echo "<h3>Histórico de Operações:</h3>";
          echo "<ul>";
          foreach ($_SESSION['historicoOperacoes'] as $operacao) {
            $num1 = $operacao['numero1'];
            $op = $operacao['operacao'];
            $num2 = $operacao['numero2'];
            $resultado = $operacao['resultado'];
            echo "<li>$num1 $op $num2 = $resultado</li>";
          }
          echo "</ul>";
        } else {
          echo "<p>Histórico vazio!</p>";
        }
      }

      // Processamento de dados do formulário
      if (isset($_POST['calcular'])) {
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

        // Exibir o resultado
        echo "<p>Resultado: $resultado</p>";

        // Registrar operação no histórico
        registrarOperacao($numero1, $operacao, $numero2, $resultado);
      }


      if (isset($_POST['salvar'])) {
        if (isset($_POST['numero1'], $_POST['operacao'], $_POST['numero2'])) {
          $numero1 = $_POST['numero1'];
          $operacao = $_POST['operacao'];
          $numero2 = $_POST['numero2'];
          salvarMemoria($numero1, $operacao, $numero2);
        } else {
          echo "<p>Preencha todos os campos antes de salvar na memória!</p>";
        }
      }

      if (isset($_POST['recuperar'])) {
        recuperarMemoria();
      }

      if (isset($_POST['recuperar_operacao'])) {
        $index = $_POST['operacao_salva'];
        $operacaoSelecionada = $_SESSION['memoriaOperacoes'][$index];
        $numero1 = $operacaoSelecionada['numero1'];
        $operacao = $operacaoSelecionada['operacao'];
        $numero2 = $operacaoSelecionada['numero2'];

        echo "<script>document.getElementById('numero1').value = '$numero1';</script>";
        echo "<script>document.getElementById('operacao').value = '$operacao';</script>";
        echo "<script>document.getElementById('numero2').value = '$numero2';</script>";
      }

      if (isset($_POST['consultar'])) {
        consultarHistorico();
      }

      if (isset($_POST['apagar'])) {
        apagarHistorico();
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

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const selectOperacaoSalva = document.getElementById("operacoes_salvas");
      selectOperacaoSalva.addEventListener("change", function() {
        const selectedIndex = selectOperacaoSalva.value;
        const operacaoSelecionada = <?php echo isset($_SESSION['memoriaOperacoes']) ? json_encode($_SESSION['memoriaOperacoes']) : '[]'; ?>;
        if (selectedIndex >= 0 && selectedIndex < operacaoSelecionada.length) {
          const operacao = operacaoSelecionada[selectedIndex];
          document.getElementById('numero1').value = operacao['numero1'];
          document.getElementById('operacao').value = operacao['operacao'];
          document.getElementById('numero2').value = operacao['numero2'];
        }
      });
    });
  </script>

</body>

</html>