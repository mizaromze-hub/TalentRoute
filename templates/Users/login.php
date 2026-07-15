<!DOCTYPE html>
<html lang="ms" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk - TalentRoute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-slate-800 border border-slate-700 p-8 rounded-2xl shadow-xl">
        <!-- Logo & Tajuk -->
        <div class="text-center mb-8">
            <div class="inline-flex bg-gradient-to-r from-purple-600 to-blue-500 p-3 rounded-xl text-white shadow-md mb-3">
                <i class="fa-solid fa-rocket text-2xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold tracking-wider bg-gradient-to-r from-purple-400 via-pink-400 to-blue-400 bg-clip-text text-transparent uppercase">
                TalentRoute
            </h2>
            <p class="text-slate-400 text-sm mt-1">Sistem Pengurusan Internship & Cuti Pelajar</p>
        </div>
<?= $this->Flash->render() ?>
        <!-- Form Login -->
        <?= $this->Form->create(null, ['class' => 'space-y-5']) ?>
            <!-- Input Emel -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-2">Alamat Emel</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" required placeholder="name@example.com" class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition">
                </div>
            </div>

            <!-- Input Password -->
            <div>
                <div class="flex justify-between mb-2">
                    <label class="text-xs font-bold uppercase tracking-wide text-slate-400">Kata Laluan</label>
                    <a href="#" class="text-xs font-semibold text-purple-400 hover:underline">Lupa?</a>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition">
                </div>
            </div>

            <!-- Button Submit -->
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-150 text-sm">
                Log Masuk <i class="fa-solid fa-arrow-right-to-bracket ml-1.5"></i>
            </button>
        <?= $this->Form->end() ?>

        <!-- Footer Card -->
        <p class="text-center text-xs text-slate-500 mt-6">
            Belum mempunyai akaun? 
            
        </p>
        <a href="mailto:admin@talentroute.com?subject=Permohonan Akaun TalentRoute">
    Hubungi Admin
</a>
    </div>

</body>
</html>
