<?php
require_once __DIR__ . '/../infra/conexao.php';

function listarProdutos($pdo) {
    $stmt = $pdo->prepare("SELECT id, nome, categoria, descricao, preco, quantidade_estoque, data_validade FROM produtos ORDER BY nome");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProdutoPorId($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function validarDadosProduto($dados) {
    $erros = [];
    foreach (['nome' => 100, 'categoria' => 50] as $campo => $limite) {
        $valor = $dados[$campo] ?? null;
        if (!is_string($valor) || trim($valor) === '') {
            $erros[] = ucfirst($campo) . ' é obrigatório.';
        } elseif (function_exists('mb_strlen') ? mb_strlen(trim($valor), 'UTF-8') > $limite : strlen(trim($valor)) > $limite) {
            $erros[] = ucfirst($campo) . " deve ter no máximo {$limite} caracteres.";
        }
    }

    $descricao = $dados['descricao'] ?? '';
    if (!is_string($descricao) || trim($descricao) === '') {
        $erros[] = 'A descrição é obrigatória.';
    }

    $preco = $dados['preco'] ?? null;
    if (!is_scalar($preco) || !preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', (string) $preco)) {
        $erros[] = 'Preço inválido.';
    }

    $quantidade = $dados['quantidade_estoque'] ?? null;
    if (!is_scalar($quantidade) || filter_var($quantidade, FILTER_VALIDATE_INT) === false || (int) $quantidade < 0 || (int) $quantidade > 2147483647) {
        $erros[] = 'Quantidade em estoque deve ser um número inteiro maior ou igual a zero.';
    }

    $validade = $dados['data_validade'] ?? null;
    $data = is_string($validade) ? DateTime::createFromFormat('!Y-m-d', $validade) : false;
    $errosData = DateTime::getLastErrors();
    if (!$data || ($errosData !== false && ($errosData['warning_count'] > 0 || $errosData['error_count'] > 0)) || $data->format('Y-m-d') !== $validade) {
        $erros[] = 'Data de validade inválida.';
    }
    return $erros;
}

function cadastrarProduto($pdo, $dados) {
    $sql = "INSERT INTO produtos (nome, categoria, descricao, preco, quantidade_estoque, data_validade) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        trim($dados['nome']),
        trim($dados['categoria']),
        trim($dados['descricao'] ?? ''),
        $dados['preco'],
        $dados['quantidade_estoque'],
        $dados['data_validade'],
    ]);
}

function editarProduto($pdo, $id, $dados) {
    $sql = "UPDATE produtos SET nome = ?, categoria = ?, descricao = ?, preco = ?, quantidade_estoque = ?, data_validade = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        trim($dados['nome']),
        trim($dados['categoria']),
        trim($dados['descricao'] ?? ''),
        $dados['preco'],
        $dados['quantidade_estoque'],
        $dados['data_validade'],
        $id,
    ]);
}

function excluirProduto($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    return $stmt->execute([$id]);
}