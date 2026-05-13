<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

// VALIDAÇÃO DE SESSÃO - Proteger o painel admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Se não estiver logado, redirecionar para login
    header("Location: login.php");
    exit();
}

// Usar as mesmas credenciais do index.php que funcionam
$host = "localhost";
$dbname = "delitc66_rigon_motor_bar";
$username_db = "delitc66_rigon_motor_bar";
$password_db = "z[Q_kcVxD1I&";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Processar ações
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                $nome = trim($_POST['nome'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');
                $preco = floatval($_POST['preco'] ?? 0);
                $categoria = trim($_POST['categoria'] ?? '');
                $nova_categoria = trim($_POST['nova_categoria'] ?? '');
                
                // Se selecionou "nova categoria", usar o valor do input
                if ($categoria === 'nova_categoria') {
                    if (empty($nova_categoria)) {
                        $error = 'Nome da nova categoria é obrigatório';
                        break;
                    }
                    $categoria = $nova_categoria;
                }
                
                // Validações de negócio
                if (empty($nome) || empty($categoria) || $preco <= 0) {
                    $error = 'Nome, categoria e preço são obrigatórios';
                } elseif (strlen($nome) < 3 || strlen($nome) > 100) {
                    $error = 'Nome deve ter entre 3 e 100 caracteres';
                } elseif (strlen($descricao) > 500) {
                    $error = 'Descrição deve ter no máximo 500 caracteres';
                } elseif ($preco > 9999.99) {
                    $error = 'Preço deve ser menor que R$ 10.000,00';
                } elseif (strlen($categoria) < 2 || strlen($categoria) > 50) {
                    $error = 'Categoria deve ter entre 2 e 50 caracteres';
                } else {
                    // Verificar se produto já existe
                    $checkStmt = $pdo->prepare("SELECT id FROM produtos WHERE nome = ? AND categoria = ? AND ativo = 1");
                    $checkStmt->execute([$nome, $categoria]);
                    
                    if ($checkStmt->fetch()) {
                        $error = 'Já existe um produto com este nome nesta categoria';
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, categoria, ativo, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
                        if ($stmt->execute([$nome, $descricao, $preco, $categoria])) {
                            $message = 'Produto criado com sucesso na categoria "' . htmlspecialchars($categoria) . '"!';
                            // Log de criação
                            error_log("Produto criado: {$nome} na categoria '{$categoria}' por " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                        } else {
                            $error = 'Erro ao criar produto';
                        }
                    }
                }
                break;
                
            case 'edit_product':
                $id = intval($_POST['id'] ?? 0);
                $nome = trim($_POST['nome'] ?? '');
                $descricao = trim($_POST['descricao'] ?? '');
                $preco = floatval($_POST['preco'] ?? 0);
                $categoria = trim($_POST['categoria'] ?? '');
                
                // Validações de negócio
                if ($id <= 0 || empty($nome) || empty($categoria) || $preco <= 0) {
                    $error = 'ID, nome, categoria e preço são obrigatórios';
                } elseif (strlen($nome) < 3 || strlen($nome) > 100) {
                    $error = 'Nome deve ter entre 3 e 100 caracteres';
                } elseif (strlen($descricao) > 500) {
                    $error = 'Descrição deve ter no máximo 500 caracteres';
                } elseif ($preco > 9999.99) {
                    $error = 'Preço deve ser menor que R$ 10.000,00';
                } else {
                    // Verificar se produto existe e está ativo
                    $checkStmt = $pdo->prepare("SELECT id FROM produtos WHERE id = ? AND ativo = 1");
                    $checkStmt->execute([$id]);
                    
                    if (!$checkStmt->fetch()) {
                        $error = 'Produto não encontrado ou inativo';
                    } else {
                        // Verificar se nome já existe em outra categoria
                        $checkStmt = $pdo->prepare("SELECT id FROM produtos WHERE nome = ? AND categoria = ? AND id != ? AND ativo = 1");
                        $checkStmt->execute([$nome, $categoria, $id]);
                        
                        if ($checkStmt->fetch()) {
                            $error = 'Já existe um produto com este nome nesta categoria';
                        } else {
                            // Usar UPDATE simples sem campos que podem não existir
                            $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, categoria = ?, updated_at = NOW() WHERE id = ?");
                            if ($stmt->execute([$nome, $descricao, $preco, $categoria, $id])) {
                                $message = 'Produto atualizado com sucesso!';
                                // Log de atualização
                                error_log("Produto atualizado: {$nome} por " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                            } else {
                                $error = 'Erro ao atualizar produto: ' . implode(', ', $stmt->errorInfo());
                            }
                        }
                    }
                }
                break;
                
            case 'delete_product':
                $id = intval($_POST['id'] ?? 0);
                if ($id > 0) {
                    // Verificar se produto existe
                    $checkStmt = $pdo->prepare("SELECT nome FROM produtos WHERE id = ?");
                    $checkStmt->execute([$id]);
                    $produto = $checkStmt->fetch();
                    
                    if ($produto) {
                        // REMOVER COMPLETAMENTE DA TABELA
                        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
                        if ($stmt->execute([$id])) {
                            $message = 'Produto removido permanentemente do banco de dados!';
                            // Log de remoção
                            error_log("Produto DELETADO permanentemente: {$produto['nome']} por " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                        } else {
                            $error = 'Erro ao remover produto do banco: ' . implode(', ', $stmt->errorInfo());
                        }
                    } else {
                        $error = 'Produto não encontrado';
                    }
                } else {
                    $error = 'ID de produto inválido';
                }
                break;
                
            case 'logout':
                // Log de logout
                error_log("Logout realizado: " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                session_destroy();
                header("Location: login.php");
                exit();
                break;
                
            case 'create_user':
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $nome = trim($_POST['nome'] ?? '');
                $role = trim($_POST['role'] ?? '');
                
                // Validações de negócio
                if (empty($username) || empty($password) || empty($nome) || empty($role)) {
                    $error = 'Todos os campos são obrigatórios';
                } elseif (strlen($username) < 3 || strlen($username) > 50) {
                    $error = 'Usuário deve ter entre 3 e 50 caracteres';
                } elseif (strlen($password) < 6) {
                    $error = 'Senha deve ter no mínimo 6 caracteres';
                } elseif (strlen($nome) < 3 || strlen($nome) > 100) {
                    $error = 'Nome deve ter entre 3 e 100 caracteres';
                } elseif (!in_array($role, ['user', 'admin'])) {
                    $error = 'Tipo de usuário inválido';
                } else {
                    // Verificar se usuário já existe
                    $checkStmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
                    $checkStmt->execute([$username]);
                    
                    if ($checkStmt->fetch()) {
                        $error = 'Já existe um usuário com este nome de usuário';
                    } else {
                        // Hash da senha
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);
                        
                        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, nome, email, role, ativo, created_at) VALUES (?, ?, ?, ?, ?, 1, NOW())");
                        if ($stmt->execute([$username, $password_hash, $nome, $username . '@rigon.com', $role])) {
                            $message = 'Usuário criado com sucesso!';
                            // Log de criação
                            error_log("Usuário criado: {$username} ({$role}) por " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema') . " - " . date('Y-m-d H:i:s'));
                        } else {
                            $error = 'Erro ao criar usuário: ' . implode(', ', $stmt->errorInfo());
                        }
                    }
                }
                break;
        }
    }
}

// Buscar produtos para edição
$editProduct = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([intval($_GET['edit'])]);
    $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar produtos
$stmt = $pdo->prepare("SELECT * FROM produtos ORDER BY categoria, nome");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar usuários para o modal
$stmt = $pdo->query("SELECT id, username, nome, role, ativo, created_at, last_login FROM usuarios ORDER BY username");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por categoria
$categorias = [];
foreach ($produtos as $produto) {
    if (!isset($categorias[$produto['categoria']])) {
        $categorias[$produto['categoria']] = [];
    }
    $categorias[$produto['categoria']][] = $produto;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin — Rigon Motor Bar</title>
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style-custom.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar" aria-label="Menu principal">
            <a class="sidebar-logo" href="../index.html">RIGON<span>MOTOR</span>BAR</a>
            <p class="sidebar-tag">Painel administrativo</p>

            <nav class="sidebar-nav">
                <a class="sidebar-link" href="#sec-resumo" data-nav><i data-feather="bar-chart-2"></i> Visão geral</a>
                <a class="sidebar-link" href="#sec-form" data-nav><i data-feather="edit-3"></i> Produto</a>
                <a class="sidebar-link" href="#sec-catalog" data-nav><i data-feather="package"></i> Catálogo</a>
            </nav>

            <div class="sidebar-label">Cardápio</div>
            <nav class="sidebar-nav">
                <a class="sidebar-link" href="upload-cardapio.php"><i data-feather="upload-cloud"></i> Atualizar PDF</a>
            </nav>

            <div class="sidebar-label">Usuários</div>
            <nav class="sidebar-nav">
                <button type="button" class="sidebar-link sidebar-link--btn" onclick="showModal('createAccountModal')">
                    <i data-feather="user-plus"></i> Nova conta
                </button>
                <button type="button" class="sidebar-link sidebar-link--btn" onclick="showModal('manageUsersModal')">
                    <i data-feather="users"></i> Gerenciar usuários
                </button>
            </nav>

            <p class="sidebar-site"><a href="../index.html">← Voltar ao site</a></p>
            <div class="sidebar-spacer"></div>
            <div class="sidebar-footer">
                <form method="POST">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="logout-btn"><i data-feather="log-out"></i> Sair</button>
                </form>
            </div>
        </aside>

        <div class="main-area">
            <header class="topbar">
                <h1><i data-feather="layout"></i> Painel <span>admin</span></h1>
                <div class="topbar-meta">
                    <span class="user-pill">
                        <i data-feather="user"></i>
                        <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuário'); ?></strong>
                        <span>· <?php echo isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'admin'; ?></span>
                    </span>
                </div>
            </header>

            <div class="main-scroll">
                <?php if ($message): ?>
                    <div class="message success"><i data-feather="check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="message error"><i data-feather="alert-circle"></i> <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <section id="sec-resumo" class="stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($produtos); ?></div>
                        <div class="stat-label">Total de produtos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($categorias); ?></div>
                        <div class="stat-label">Categorias</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count(array_filter($produtos, fn($p) => $p['categoria'] === 'Drinks')); ?></div>
                        <div class="stat-label">Drinks</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count(array_filter($produtos, fn($p) => $p['categoria'] === 'Petiscos')); ?></div>
                        <div class="stat-label">Petiscos</div>
                    </div>
                </section>

                <div class="content-grid">
                    <section id="sec-form" class="section">
                        <h2><?php echo $editProduct ? '<i data-feather="edit-3"></i> Editar produto' : '<i data-feather="plus"></i> Novo produto'; ?></h2>

                        <form method="POST">
                            <input type="hidden" name="action" value="<?php echo $editProduct ? 'edit_product' : 'add_product'; ?>">
                            <?php if ($editProduct): ?>
                                <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="nome">Nome do produto *</label>
                                <input type="text" id="nome" name="nome" value="<?php echo $editProduct ? htmlspecialchars($editProduct['nome']) : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea id="descricao" name="descricao"><?php echo $editProduct ? htmlspecialchars($editProduct['descricao']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="preco">Preço (R$) *</label>
                                <input type="number" id="preco" name="preco" step="0.01" min="0" value="<?php echo $editProduct ? $editProduct['preco'] : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="categoria">Categoria *</label>
                                <div class="categoria-input-group">
                                    <select id="categoria" name="categoria" required>
                                        <option value="">Selecione uma categoria</option>
                                        <option value="Cervejas" <?php echo ($editProduct && $editProduct['categoria'] === 'Cervejas') ? 'selected' : ''; ?>>Cervejas</option>
                                        <option value="Destilados" <?php echo ($editProduct && $editProduct['categoria'] === 'Destilados') ? 'selected' : ''; ?>>Destilados</option>
                                        <option value="Drinks" <?php echo ($editProduct && $editProduct['categoria'] === 'Drinks') ? 'selected' : ''; ?>>Drinks</option>
                                        <option value="Petiscos" <?php echo ($editProduct && $editProduct['categoria'] === 'Petiscos') ? 'selected' : ''; ?>>Petiscos</option>
                                        <option value="Refrigerantes" <?php echo ($editProduct && $editProduct['categoria'] === 'Refrigerantes') ? 'selected' : ''; ?>>Refrigerantes</option>
                                        <option value="Outros" <?php echo ($editProduct && $editProduct['categoria'] === 'Outros') ? 'selected' : ''; ?>>Outros</option>
                                        <option value="nova_categoria">+ Nova categoria</option>
                                    </select>
                                    <input type="text" id="nova_categoria" name="nova_categoria" placeholder="Digite o nome da nova categoria" style="display: none;">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <?php echo $editProduct ? '<i data-feather="save"></i> Atualizar' : '<i data-feather="plus"></i> Adicionar'; ?>
                            </button>

                            <?php if ($editProduct): ?>
                                <a href="admin-panel.php" class="btn btn-secondary"><i data-feather="x"></i> Cancelar</a>
                            <?php endif; ?>
                        </form>
                    </section>

                    <section id="sec-catalog" class="section section--scroll">
                        <h2><i data-feather="package"></i> Catálogo</h2>

                        <?php if (empty($categorias)): ?>
                            <p class="empty-state">Nenhum produto cadastrado ainda.</p>
                        <?php else: ?>
                            <?php foreach ($categorias as $categoria => $produtos_cat): ?>
                                <h3 class="category-heading">
                                    <i data-feather="folder"></i> <?php echo htmlspecialchars($categoria); ?>
                                </h3>

                                <div class="products-grid">
                                    <?php foreach ($produtos_cat as $produto): ?>
                                        <div class="product-card">
                                            <div class="product-name"><?php echo htmlspecialchars($produto['nome']); ?></div>
                                            <div class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                                            <div class="product-category"><?php echo htmlspecialchars($produto['categoria']); ?></div>

                                            <?php if ($produto['descricao']): ?>
                                                <div class="product-description"><?php echo htmlspecialchars($produto['descricao']); ?></div>
                                            <?php endif; ?>

                                            <div class="product-actions">
                                                <a href="?edit=<?php echo $produto['id']; ?>" class="btn btn-secondary btn-sm"><i data-feather="edit-3"></i> Editar</a>

                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_product">
                                                    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Tem certeza que deseja remover este produto?')">
                                                        <i data-feather="trash-2"></i> Remover
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Criar Conta -->
    <div id="createAccountModal" class="app-modal" role="dialog" aria-modal="true">
        <div class="app-modal__panel">
            <div class="app-modal__header">
                <h2><i data-feather="user-plus"></i> Nova conta</h2>
                <span class="app-modal__close" onclick="closeModal('createAccountModal')" aria-label="Fechar">&times;</span>
            </div>
            <form method="POST" id="createAccountForm">
                <div class="app-modal__body">
                    <input type="hidden" name="action" value="create_user">
                    <div class="form-group">
                        <label for="new_username">Usuário *</label>
                        <input type="text" id="new_username" name="username" required minlength="3" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="new_password">Senha *</label>
                        <input type="password" id="new_password" name="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="new_nome">Nome completo *</label>
                        <input type="text" id="new_nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="new_role">Tipo *</label>
                        <select id="new_role" name="role" required>
                            <option value="">Selecione...</option>
                            <option value="user">Usuário</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="app-modal__footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createAccountModal')"><i data-feather="x"></i> Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> Criar conta</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Gerenciar Usuários -->
    <div id="manageUsersModal" class="app-modal" role="dialog" aria-modal="true">
        <div class="app-modal__panel app-modal__panel--lg">
            <div class="app-modal__header">
                <h2><i data-feather="users"></i> Usuários</h2>
                <span class="app-modal__close" onclick="closeModal('manageUsersModal')" aria-label="Fechar">&times;</span>
            </div>
            <div class="app-modal__body" style="padding-top: 12px;">
                <div class="users-header">
                    <h3><i data-feather="users"></i> Lista</h3>
                    <p>Total: <strong><?php echo count($users); ?></strong></p>
                </div>

                <?php if (empty($users)): ?>
                    <div class="no-users">
                        <p>Nenhum usuário cadastrado ainda.</p>
                    </div>
                <?php else: ?>
                    <div class="users-list">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Criado em</th>
                                <th>Último login</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                    <?php if ($user['id'] == ($_SESSION['user_id'] ?? 0)): ?>
                                        <span class="current-user-badge"><i data-feather="user"></i> Você</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['nome']); ?></td>
                                <td>
                                    <span class="role-badge <?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $user['ativo'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $user['ativo'] ? '<i data-feather="check-circle"></i> Ativo' : '<i data-feather="x-circle"></i> Inativo'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <?php
                                    if (isset($user['last_login']) && $user['last_login']):
                                        echo date('d/m/Y H:i', strtotime($user['last_login']));
                                    else:
                                        echo '<span class="never-logged">Nunca logou</span>';
                                    endif;
                                    ?>
                                </td>
                                <td>
                                    <div class="user-actions">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="editUser(<?php echo $user['id']; ?>)" title="Editar usuário">
                                            <i data-feather="edit-3"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="resetPassword(<?php echo $user['id']; ?>)" title="Redefinir senha">
                                            <i data-feather="key"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="toggleUserStatus(<?php echo $user['id']; ?>, <?php echo $user['ativo']; ?>)" title="<?php echo $user['ativo'] ? 'Desativar usuário' : 'Ativar usuário'; ?>">
                                            <i data-feather="<?php echo $user['ativo'] ? 'user-x' : 'user-check'; ?>"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'grid';
                document.body.style.overflow = 'hidden';
                if (typeof feather !== 'undefined') feather.replace();
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        window.addEventListener('click', function (event) {
            if (event.target.classList.contains('app-modal')) {
                event.target.style.display = 'none';
                document.body.style.overflow = '';
            }
        });

        function setActiveNavFromHash() {
            const hash = window.location.hash || '#sec-resumo';
            document.querySelectorAll('.sidebar-link[data-nav]').forEach(function (el) {
                el.classList.toggle('active', el.getAttribute('href') === hash);
            });
        }

        setTimeout(function () {
            document.querySelectorAll('.message').forEach(function (msg) {
                msg.style.opacity = '0';
                msg.style.transition = 'opacity 0.5s ease';
                setTimeout(function () { msg.remove(); }, 500);
            });
        }, 5000);

        <?php if ($message && strpos($message, 'criado com sucesso') !== false): ?>
        setTimeout(function () {
            closeModal('createAccountModal');
            var f = document.getElementById('createAccountForm');
            if (f) f.reset();
        }, 2000);
        <?php endif; ?>

        function editUser(userId) {
            alert('Função de editar usuário será implementada em breve! ID: ' + userId);
        }

        function toggleUserStatus(userId, currentStatus) {
            if (confirm('Tem certeza que deseja ' + (currentStatus ? 'desativar' : 'ativar') + ' este usuário?')) {
                alert('Função de alternar status será implementada em breve! ID: ' + userId);
            }
        }

        function resetPassword(userId) {
            if (confirm('Tem certeza que deseja redefinir a senha deste usuário?\n\nA nova senha será: admin123')) {
                alert('Função de redefinir senha será implementada em breve! ID: ' + userId);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('a.sidebar-link[data-nav]').forEach(function (link) {
                link.addEventListener('click', function () {
                    setTimeout(setActiveNavFromHash, 50);
                    setTimeout(function () {
                        if (typeof feather !== 'undefined') feather.replace();
                    }, 120);
                });
            });
            window.addEventListener('hashchange', setActiveNavFromHash);
            setActiveNavFromHash();

            <?php if ($editProduct): ?>
            document.getElementById('sec-form') && document.getElementById('sec-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
            <?php endif; ?>

            var categoriaSelect = document.getElementById('categoria');
            var novaCategoriaInput = document.getElementById('nova_categoria');
            if (categoriaSelect && novaCategoriaInput) {
                categoriaSelect.addEventListener('change', function () {
                    if (this.value === 'nova_categoria') {
                        novaCategoriaInput.style.display = 'block';
                        novaCategoriaInput.required = true;
                        novaCategoriaInput.focus();
                    } else {
                        novaCategoriaInput.style.display = 'none';
                        novaCategoriaInput.required = false;
                        novaCategoriaInput.value = '';
                    }
                });
            }

            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
</body>
</html>
