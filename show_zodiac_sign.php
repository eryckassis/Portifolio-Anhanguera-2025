<?php include('layouts/header.php'); ?>
<body class="container mt-5">

<?php
$data_nascimento = $_POST['data_nascimento'] ?? '';
$arquivoSignos = "signos.xml";

if (!file_exists($arquivoSignos)) {
    echo "<p>Erro: O arquivo de dados dos signos (signos.xml) não foi encontrado.</p>";
    exit;
}

$signos = simplexml_load_file($arquivoSignos);

if (!$signos) {
    echo "<p>Erro: Não foi possível carregar os dados dos signos. Verifique o arquivo signos.xml.</p>";
    exit;
}

// Função para formatar datas no formato 'd/m' para um objeto DateTime
function formatarData($diaMes, $ano) {
    $partes = explode("/", trim($diaMes)); // Divide a string no formato 'd/m'
    if (count($partes) !== 2) {
        return false; // Retorna false se o formato estiver incorreto
    }
    return DateTime::createFromFormat('d/m/Y', "{$partes[0]}/{$partes[1]}/{$ano}");
}

if ($data_nascimento) {
    try {
        // Converte a data de nascimento do formato 'YYYY-MM-DD' para 'DD/MM/YYYY'
        $data = DateTime::createFromFormat('Y-m-d', $data_nascimento);
        if (!$data) {
            throw new Exception("Formato de data inválido.");
        }

        // Converte para o formato dia/mês/ano e extrai o ano
        $dataFormatada = $data->format('d/m/Y'); // Conversão para 'DD/MM/YYYY'
        $ano = $data->format('Y'); // Ano da data de nascimento

        $signoEncontrado = false; // Variável para verificar se encontramos o signo

        foreach ($signos->signo as $signo) {
            // Formata as datas de início e fim do signo com o ano atual
            $inicio = formatarData($signo->dataInicio, $ano);
            $fim = formatarData($signo->dataFim, $ano);

            if (!$inicio || !$fim) {
                echo "<p>Erro ao processar as datas do signo '{$signo->signoNome}'. Verifique o arquivo signos.xml.</p>";
                continue; // Ignora este signo se houver erro na formatação
            }

            // Lógica para signos que atravessam o ano (Ex: Capricórnio)
            if ($fim < $inicio) {
                $fim->modify('+1 year'); // Adiciona 1 ano ao fim do intervalo
                if ($data < $inicio) {
                    $data->modify('+1 year'); // Ajusta a data de nascimento para o mesmo intervalo
                }
            }

            // Verifica se a data está no intervalo do signo
            if ($data >= $inicio && $data <= $fim) {
                $signoEncontrado = true;
                echo "<h2>Seu signo é: {$signo->signoNome}</h2>";
                echo "<p>{$signo->descricao}</p>";
                break; // Sai do loop após encontrar o signo
            }
        }

        // Caso nenhum signo tenha sido encontrado
        if (!$signoEncontrado) {
            echo "<p>Erro: Não foi possível determinar o signo. Verifique os dados fornecidos.</p>";
        }
    } catch (Exception $e) {
        // Captura erros gerais no processamento da data
        echo "<p>Erro ao processar a data de nascimento: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Data inválida. Por favor, volte e tente novamente.</p>";
}
?>

<a href="index.php" class="btn btn-secondary mt-4">Voltar</a>
</body>
</html>