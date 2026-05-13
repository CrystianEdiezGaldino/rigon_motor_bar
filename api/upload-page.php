<?php
$cardapioUrl = '../assets/imagens/cardapio.pdf';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de cardápio — Rigon Motor Bar</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style-custom.css">
</head>
<body id="page-upload">
    <div class="app-shell">
        <aside class="sidebar" aria-label="Navegação">
            <a class="sidebar-logo" href="../index.html">RIGON<span>MOTOR</span>BAR</a>
            <p class="sidebar-tag">Ferramentas</p>

            <nav class="sidebar-nav">
                <a class="sidebar-link" href="admin-panel.php"><i data-feather="layout"></i> Painel admin</a>
                <a class="sidebar-link active" href="upload-cardapio.php"><i data-feather="upload-cloud"></i> Upload PDF</a>
            </nav>

            <p class="sidebar-site"><a href="../index.html">← Voltar ao site</a></p>
            <div class="sidebar-spacer"></div>
        </aside>

        <div class="main-area">
            <header class="topbar">
                <h1><i data-feather="upload-cloud"></i> Cardápio <span>PDF</span></h1>
            </header>

            <div class="main-scroll">
                <div class="upload-stack">
                    <section class="section">
                        <h2><i data-feather="file-text"></i> Enviar novo cardápio</h2>

                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="cardapio_pdf">Arquivo PDF</label>
                                <input type="file" id="cardapio_pdf" name="cardapio_pdf" accept=".pdf,application/pdf" class="input-file-upload" required>
                                <span class="upload-hint">Somente PDF, até 10 MB.</span>
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-primary btn-block">
                                <i data-feather="upload"></i> Enviar
                            </button>
                        </form>

                        <div id="upload-result" class="message" role="status" aria-live="polite"></div>
                    </section>

                    <section class="info-section">
                        <h3><i data-feather="info"></i> Ambiente</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">PHP</span>
                                <span class="info-value"><?php echo htmlspecialchars(PHP_VERSION, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">upload_max_filesize</span>
                                <span class="info-value"><?php echo htmlspecialchars(ini_get('upload_max_filesize') ?: '-', ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">post_max_size</span>
                                <span class="info-value"><?php echo htmlspecialchars(ini_get('post_max_size') ?: '-', ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">max_execution_time</span>
                                <span class="info-value"><?php echo htmlspecialchars((string) ini_get('max_execution_time'), ENT_QUOTES, 'UTF-8'); ?>s</span>
                            </div>
                        </div>
                    </section>

                    <section class="section cardapio-actions">
                        <h3>Cardápio publicado</h3>
                        <a href="<?php echo htmlspecialchars($cardapioUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="cardapio-link">
                            <i data-feather="external-link"></i> Abrir PDF atual
                        </a>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showUploadMessage(html, type) {
            var el = document.getElementById('upload-result');
            if (!el) return;
            el.innerHTML = html;
            el.className = 'message ' + type + ' is-visible';
        }

        function hideUploadMessage() {
            var el = document.getElementById('upload-result');
            if (!el) return;
            el.className = 'message';
            el.innerHTML = '';
        }

        document.getElementById('uploadForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            var fileInput = document.getElementById('cardapio_pdf');
            var submitBtn = document.getElementById('submitBtn');
            if (!fileInput.files[0]) {
                showUploadMessage('Selecione um arquivo PDF.', 'error');
                return;
            }
            var file = fileInput.files[0];
            if (file.type !== 'application/pdf') {
                showUploadMessage('Apenas arquivos PDF são aceitos.', 'error');
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                showUploadMessage('Arquivo muito grande. Máximo: 10 MB.', 'error');
                return;
            }
            var formData = new FormData();
            formData.append('cardapio_pdf', file);
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading-spinner"></span>Enviando…';
            showUploadMessage('Enviando arquivo…', 'info');
            if (typeof feather !== 'undefined') feather.replace();

            try {
                var response = await fetch(window.location.href, { method: 'POST', body: formData });
                var data = await response.json();
                if (data.success) {
                    var kb = (data.size / 1024).toFixed(2);
                    var link = '';
                    if (data.url) {
                        var u = String(data.url).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
                        link = '<br><a class="primary-link" href="' + u + '" target="_blank" rel="noopener">Abrir novo cardápio</a>';
                    }
                    showUploadMessage(
                        '<strong>Upload concluído.</strong><br>Arquivo: ' + (data.filename || 'cardapio.pdf') + ' · ' + kb + ' KB' + link,
                        'success'
                    );
                    fileInput.value = '';
                } else {
                    showUploadMessage('<strong>Erro:</strong> ' + (data.error || 'Falha no upload'), 'error');
                }
            } catch (err) {
                showUploadMessage('<strong>Erro de conexão:</strong> ' + err.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i data-feather="upload"></i> Enviar';
                if (typeof feather !== 'undefined') feather.replace();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof feather !== 'undefined') feather.replace();
        });
    </script>
</body>
</html>
