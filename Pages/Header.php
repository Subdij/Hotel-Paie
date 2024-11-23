<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<header>
    <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex shrink-0 items-center">
                        <img class="h-8 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4">
                            <a href="/Hotel-Paie/index.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">accueil</a>
                            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Team</a>
                            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Projects</a>
                            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Calendar</a>
                        </div>
                    </div>
                </div>
                <div class="relative ml-3">
                    <div>
                        <?php if (isset($_SESSION['user'])): ?>
                            <?php if (isset($_SESSION['user']['ID_client'])) {
                                $id_client = $_SESSION['user']['ID_client'];
                            } else {
                                $id_client = null;
                            } ?>
                            <?php if ($id_client == 1): ?>
                                <a href="/Hotel-Paie/Pages/Dashboard.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Admin Dashboard</a>
                            <?php endif; ?>
                            <span class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 font-bold">Bonjour, <?php echo $_SESSION['user']['Prenom'] . ' ' . $_SESSION['user']['Nom']; ?></span>
                            <a href="/Hotel-Paie/Pages/logout.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Déconnexion</a>
                        <?php else: ?>
                            <a href="/Hotel-Paie/Pages/Connexion.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Connexion</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>