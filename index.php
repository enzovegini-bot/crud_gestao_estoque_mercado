<?php
require_once __DIR__ . '/public/funcoes.php';

try {
    $produtos = listarProdutos($pdo);
    $erroBanco = false;
} catch (PDOException $e) {
    error_log($e->getMessage());
    $produtos = [];
    $erroBanco = true;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Estoque - Mercado</title>
</head>
<body>
    <h1>Produtos em Estoque</h1>
    <?php if ($erroBanco): ?>
        <p role="alert">Não foi possível carregar os produtos. Tente novamente mais tarde.</p>
    <?php endif; ?>
    <p><a href="public/cadastrar.php">+ Novo produto</a></p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nome</th><th>Categoria</th><th>Descrição</th><th>Preço</th>
                <th>Estoque</th><th>Validade</th><th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($produtos)): ?>
            <tr><td colspan="7">Nenhum produto cadastrado.</td></tr>
        <?php endif; ?>
        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($p['categoria'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($p['descricao'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>R$ <?= number_format((float) $p['preco'], 2, ',', '.') ?></td>
                <td><?= (int) $p['quantidade_estoque'] ?></td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($p['data_validade'])), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="public/editar.php?id=<?= (int) $p['id'] ?>">Editar</a> |
                    <a href="public/excluir.php?id=<?= (int) $p['id'] ?>" onclick="return confirm('Excluir este produto?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>