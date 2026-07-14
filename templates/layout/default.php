<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TalentRoute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = {darkMode: 'class'};</script>
    <?= $this->fetch('css') ?>
</head>
<?php
$auth = $this->getRequest()->getSession()->read('Auth.User');
$role = strtolower((string)($auth['role'] ?? 'guest'));
$displayName = $auth['name'] ?? $auth['email'] ?? ucfirst($role);
$currentController = strtolower((string)$this->getRequest()->getParam('controller'));
$currentAction = strtolower((string)$this->getRequest()->getParam('action'));

$baseNav = 'px-3 py-2 rounded-lg transition hover:bg-purple-100 dark:hover:bg-slate-800';
$activeNav = ' bg-purple-100 text-purple-700 dark:bg-slate-800 dark:text-purple-300';
?>
<body class="bg-slate-100 dark:bg-[#0b0f19] text-slate-800 dark:text-slate-200 min-h-screen transition">

<nav class="sticky top-0 z-50 bg-white/95 dark:bg-[#0f172a]/95 border-b border-slate-200 dark:border-slate-800 px-4 py-3 shadow">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
        <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'index']) ?>"
           class="font-black text-xl text-purple-600">
            ⚡ TALENTROUTE
        </a>

        <?php if ($auth): ?>
            <div class="flex flex-wrap items-center gap-1 text-xs font-bold">
                <a class="<?= h($baseNav . ($currentController === 'dashboard' ? $activeNav : '')) ?>"
                   href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'index']) ?>">
                    🏠 Dashboard
                </a>

                <?php if ($role === 'admin'): ?>
                    <a class="<?= h($baseNav . ($currentController === 'students' ? $activeNav : '')) ?>"
                       href="<?= $this->Url->build(['controller' => 'Students', 'action' => 'index']) ?>">
                        🎓 Students
                    </a>
                    <a class="<?= h($baseNav . ($currentController === 'companies' ? $activeNav : '')) ?>"
                       href="<?= $this->Url->build(['controller' => 'Companies', 'action' => 'index']) ?>">
                        🏢 Companies
                    </a>
                <?php endif; ?>

                <?php if ($role === 'student'): ?>
                    <a class="<?= h($baseNav . (
                        $currentController === 'internships' &&
                        in_array($currentAction, ['search', 'add'], true)
                            ? $activeNav
                            : ''
                    )) ?>"
                       href="<?= $this->Url->build(['controller' => 'Internships', 'action' => 'search']) ?>">
                        🔎 Internships
                    </a>
                <?php endif; ?>

                <a class="<?= h($baseNav . ($currentController === 'applications' ? $activeNav : '')) ?>"
                   href="<?= $this->Url->build(['controller' => 'Applications', 'action' => 'index']) ?>">
                    📂 Applications
                </a>

                <a class="<?= h($baseNav . ($currentController === 'leaves' ? $activeNav : '')) ?>"
                   href="<?= $this->Url->build(['controller' => 'Leaves', 'action' => 'index']) ?>">
                    📅 Leave
                </a>

                <?php if ($role === 'student'): ?>
                    <a class="<?= h($baseNav . (
                        $currentController === 'students' &&
                        in_array($currentAction, ['view', 'edit'], true)
                            ? $activeNav
                            : ''
                    )) ?>"
                       href="<?= $this->Url->build(['controller' => 'Students', 'action' => 'view']) ?>">
                        👤 Profile
                    </a>
                <?php elseif ($role === 'company'): ?>
                    <a class="<?= h($baseNav . (
                        $currentController === 'companies' &&
                        in_array($currentAction, ['view', 'edit'], true)
                            ? $activeNav
                            : ''
                    )) ?>"
                       href="<?= $this->Url->build(['controller' => 'Companies', 'action' => 'view']) ?>">
                        🏢 Profile
                    </a>
                <?php endif; ?>
            </div>

            <div class="flex items-center gap-2">
                <span class="hidden md:inline text-xs">
                    <?= h((string)$displayName) ?> · <?= h(strtoupper($role)) ?>
                </span>
                <button id="theme" type="button"
                        class="px-3 py-2 rounded-lg bg-slate-200 dark:bg-slate-800"
                        aria-label="Toggle theme">🌓</button>
                <a class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold"
                   href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>">
                    Logout
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<main class="max-w-7xl mx-auto p-4 md:p-6">
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</main>

<footer class="text-center py-6 text-xs text-slate-500">
    © <?= date('Y') ?> TalentRoute
</footer>

<script>
const rootElement = document.documentElement;
const themeButton = document.getElementById('theme');
if (localStorage.theme === 'light') {
    rootElement.classList.remove('dark');
} else {
    rootElement.classList.add('dark');
}
themeButton?.addEventListener('click', function () {
    rootElement.classList.toggle('dark');
    localStorage.theme = rootElement.classList.contains('dark') ? 'dark' : 'light';
});
</script>

<?= $this->fetch('script') ?>
</body>
</html>
