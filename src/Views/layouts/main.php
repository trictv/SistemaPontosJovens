<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youth Score - <?php echo htmlspecialchars($title ?? 'Sistema de Pontuação'); ?></title>
    <!-- Tailwind CSS for rapid styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .chart-container {
            position: relative;
            height: 40vh;
            width: 100%;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-blue-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="font-bold text-xl">Youth Score</a>
                </div>
                <div class="flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['user_tipo'] === 'admin'): ?>
                            <a href="/admin" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Admin</a>
                        <?php else: ?>
                            <a href="/supervisor" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Painel</a>
                        <?php endif; ?>
                        <a href="/logout" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Sair</a>
                    <?php else: ?>
                        <a href="/login" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="p-4 mb-4 text-sm text-<?php echo $_SESSION['flash_type'] === 'error' ? 'red' : 'green'; ?>-700 bg-<?php echo $_SESSION['flash_type'] === 'error' ? 'red' : 'green'; ?>-100 rounded-lg" role="alert">
                <?php echo htmlspecialchars($_SESSION['flash_message']); ?>
                <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
            </div>
        <?php endif; ?>

        <?php echo $content ?? ''; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php echo $scripts ?? ''; ?>
</body>
</html>
