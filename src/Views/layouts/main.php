<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Youth Score - <?php echo htmlspecialchars($title ?? 'Sistema de Pontuação'); ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Base styles */
        body {
            background-color: #f8fafc;
            color: #334155;
            padding-bottom: 70px; /* Space for mobile nav */
        }

        @media (min-width: 640px) {
            body { padding-bottom: 0; }
        }

        /* Modern card shadows and borders */
        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
        }

        /* Touch friendly targets */
        button, a, input, select, textarea, label.cursor-pointer {
            touch-action: manipulation;
        }

        /* Form elements styling */
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], input[type="datetime-local"], select, textarea {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px; /* Prevents iOS zoom */
            transition: all 0.2s;
            width: 100%;
        }

        input:focus, select:focus, textarea:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        /* Big modern buttons */
        .btn-primary {
            background-color: #2563eb;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
            transition: all 0.2s;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:active {
            transform: translateY(1px);
            background-color: #1d4ed8;
        }

        /* Checkbox styling */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: all 0.2s;
        }

        .checkbox-wrapper:active {
            background: #e2e8f0;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            accent-color: #2563eb;
        }

        /* Responsive tables to cards on mobile */
        @media (max-width: 639px) {
            .mobile-cards table { display: block; width: 100%; }
            .mobile-cards thead { display: none; }
            .mobile-cards tbody { display: block; width: 100%; }
            .mobile-cards tr {
                display: flex;
                flex-direction: column;
                background: #fff;
                margin-bottom: 1rem;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                padding: 1rem;
            }
            .mobile-cards td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid #f1f5f9;
            }
            .mobile-cards td:last-child { border-bottom: none; }
            .mobile-cards td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
                font-size: 0.875rem;
            }
        }

        /* Floating action button */
        .fab {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 56px;
            height: 56px;
            border-radius: 28px;
            background-color: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
            z-index: 50;
        }

        @media (min-width: 640px) {
            .fab { bottom: 30px; }
        }

        /* Toast notifications */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            min-width: 300px;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: start;
            gap: 12px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease-out forwards;
            background: white;
            border-left: 4px solid transparent;
        }

        .toast-success { border-color: #10b981; }
        .toast-success .icon { color: #10b981; }

        .toast-error { border-color: #ef4444; }
        .toast-error .icon { color: #ef4444; }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>
</head>
<body>
    <!-- Top Navigation (Desktop & Mobile Header) -->
    <nav class="bg-blue-600 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="/" class="font-bold text-xl mr-2 flex items-center gap-2">
                        <i class="fas fa-church text-blue-200"></i> Youth Score
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden sm:flex items-center gap-2">
                    <a href="/" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                        <i class="fas fa-trophy mr-1"></i> Ranking
                    </a>
                    <a href="/historico" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                        <i class="fas fa-history mr-1"></i> Histórico
                    </a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['user_tipo'] === 'admin'): ?>
                            <a href="/admin" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 bg-blue-800 transition">Admin</a>
                        <?php else: ?>
                            <a href="/supervisor" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 bg-blue-800 transition">Meu Painel</a>
                        <?php endif; ?>
                        <a href="/logout" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition text-blue-200">
                            <i class="fas fa-sign-out-alt"></i> Sair
                        </a>
                    <?php else: ?>
                        <a href="/login" class="px-3 py-2 rounded-md text-sm font-medium bg-white text-blue-600 hover:bg-gray-100 transition shadow-sm ml-2">Login</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Header Right -->
                <div class="sm:hidden flex items-center">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="/login" class="text-sm font-medium bg-white text-blue-600 px-3 py-1.5 rounded hover:bg-gray-100">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 relative min-h-screen">
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="sm:hidden fixed bottom-0 w-full bg-white border-t border-gray-200 flex justify-around items-center pb-safe z-40 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <a href="/" class="flex flex-col items-center py-3 px-2 flex-1 text-gray-500 hover:text-blue-600 <?php echo ($_SERVER['REQUEST_URI'] == '/') ? 'text-blue-600' : ''; ?>">
            <i class="fas fa-trophy text-xl mb-1"></i>
            <span class="text-[10px] font-medium uppercase">Ranking</span>
        </a>
        <a href="/historico" class="flex flex-col items-center py-3 px-2 flex-1 text-gray-500 hover:text-blue-600 <?php echo (strpos($_SERVER['REQUEST_URI'], '/historico') !== false) ? 'text-blue-600' : ''; ?>">
            <i class="fas fa-history text-xl mb-1"></i>
            <span class="text-[10px] font-medium uppercase">Histórico</span>
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php $dashUrl = $_SESSION['user_tipo'] === 'admin' ? '/admin' : '/supervisor'; ?>
            <a href="<?php echo $dashUrl; ?>" class="flex flex-col items-center py-3 px-2 flex-1 text-gray-500 hover:text-blue-600 <?php echo (strpos($_SERVER['REQUEST_URI'], $dashUrl) !== false) ? 'text-blue-600' : ''; ?>">
                <i class="fas fa-th-large text-xl mb-1"></i>
                <span class="text-[10px] font-medium uppercase">Painel</span>
            </a>
            <a href="/logout" class="flex flex-col items-center py-3 px-2 flex-1 text-gray-500 hover:text-blue-600">
                <i class="fas fa-sign-out-alt text-xl mb-1"></i>
                <span class="text-[10px] font-medium uppercase">Sair</span>
            </a>
        <?php endif; ?>
    </nav>

    <!-- Toast Notifications Container -->
    <div id="toast-container"></div>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast("<?php echo addslashes($_SESSION['flash_message']); ?>", "<?php echo $_SESSION['flash_type'] ?? 'info'; ?>");
            });
        </script>
        <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
    <?php endif; ?>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const iconClass = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
            const toastClass = type === 'error' ? 'toast-error' : 'toast-success';

            toast.className = `toast ${toastClass}`;
            toast.innerHTML = `
                <i class="fas ${iconClass} icon text-xl mt-0.5"></i>
                <div>
                    <h4 class="font-bold text-sm text-gray-800">${type === 'error' ? 'Erro' : 'Sucesso'}</h4>
                    <p class="text-sm text-gray-600">${message}</p>
                </div>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease-out forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Add loading state to forms on submit
        document.addEventListener('submit', function(e) {
            const btn = e.target.querySelector('button[type="submit"]');
            if(btn && !btn.hasAttribute('data-no-loading')) {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Aguarde...';
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                // Create a hidden input to preserve button value if needed
                if(btn.name && btn.value) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = btn.name;
                    hidden.value = btn.value;
                    e.target.appendChild(hidden);
                }
            }
        });
    </script>
    <?php echo $scripts ?? ''; ?>
</body>
</html>
