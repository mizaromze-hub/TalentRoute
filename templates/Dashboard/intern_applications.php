<div class="max-w-7xl mx-auto p-4 md:p-6 space-y-6 text-slate-200 pb-12">
    
    <!-- HEADER HALAMAN -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/40 p-5 rounded-2xl border border-slate-800 shadow-md">
        <div>
            <h1 class="text-xl font-extrabold text-white tracking-wide flex items-center gap-2">
                📂 Halaman Permohonan Internship
            </h1>
            <p class="text-xs text-slate-400 mt-1">
                Cari kekosongan latihan industri dan pantau status permohonan aktif anda di sini.
            </p>
        </div>
        <div class="bg-purple-950/40 text-purple-400 text-xs font-bold px-3 py-1.5 rounded-xl border border-purple-900/50">
            Jumlah Permohonan: <span class="text-white font-mono font-black"><?= count($myApplications ?? []) ?></span>
        </div>
    </div>

    <!-- STRUKTUR GRID UTAMA (2/3 UTAMA, 1/3 SENARAI SYARIKAT) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- KOLUM KIRI: STATUS PERMOHONAN SAYA -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-xl">
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    📋 Log Permohonan Aktif Anda
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead>
                            <tr class="bg-slate-900/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-700">
                                <th class="p-3.5">Nama Syarikat / Posisi</th>
                                <th class="p-3.5">Tarikh Mohon</th>
                                <th class="p-3.5 text-center">Status</th>
                                <th class="p-3.5 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-750">
                            <?php if (!empty($myApplications)): foreach ($myApplications as $app): ?>
                                <tr class="hover:bg-slate-700/20 transition">
                                    <td class="p-3.5">
                                        <span class="font-bold text-white block text-sm"><?= h($app['company_name']) ?></span>
                                        <span class="text-purple-400 text-[11px] font-mono block mt-0.5"><?= h($app['position'] ?? 'Internship Trainee') ?></span>
                                    </td>
                                    <td class="p-3.5 text-slate-400 font-mono text-[11px]">
                                        <?= isset($app['created_at']) ? date('d/m/Y', strtotime($app['created_at'])) : 'Recent' ?>
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wide
                                            <?= strtolower($app['status']) === 'offered' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 
                                               (strtolower($app['status']) === 'interview' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 
                                               (strtolower($app['status']) === 'rejected' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20')) ?>">
                                            <?= h($app['status']) ?>
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <a href="#" class="bg-slate-700/80 hover:bg-slate-700 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg border border-slate-600 transition inline-block">
                                            🔍 Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="4" class="p-10 text-center text-slate-500 italic">
                                        Tiada rekod permohonan aktif dijumpai. Sila cari kekosongan di sebelah kanan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- KOLUM KANAN: KEKOSONGAN SYARIKAT BARU -->
        <div class="space-y-6">
            <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-xl space-y-4">
                <h2 class="text-xs font-bold text-white uppercase tracking-wider border-b border-slate-700 pb-2.5 flex items-center gap-1.5">
                    🚀 Peluang Kekosongan Terkini
                </h2>

                <!-- KOTAK CARIAN RINGKAS -->
                <div class="relative">
                    <input type="text" placeholder="Cari syarikat atau posisi..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 transition">
                </div>

                <!-- SENARAI KEKOSONGAN -->
                <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1">
                    
                    <!-- Contoh Syarikat 1 -->
                    <div class="bg-slate-900/60 p-3.5 rounded-xl border border-slate-750 space-y-2.5 hover:border-purple-500/40 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-xs font-black text-white tracking-wide">Nexus Tech Sdn Bhd</h4>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Software Engineer Intern</span>
                            </div>
                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-0.5 rounded text-[9px] font-bold uppercase font-mono">Full-time</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-800/80 text-[10px] text-slate-400">
                            <span>📍 Kuala Lumpur</span>
                            <button class="bg-purple-600 hover:bg-purple-700 text-white font-extrabold px-3 py-1 rounded-lg transition">
                                Mohon
                            </button>
                        </div>
                    </div>

                    <!-- Contoh Syarikat 2 -->
                    <div class="bg-slate-900/60 p-3.5 rounded-xl border border-slate-750 space-y-2.5 hover:border-purple-500/40 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-xs font-black text-white tracking-wide">Pixl Creative Agency</h4>
                                <span class="text-[10px] text-slate-400 block mt-0.5">UI/UX Designer Intern</span>
                            </div>
                            <span class="bg-purple-500/10 text-purple-400 border border-purple-500/20 px-2 py-0.5 rounded text-[9px] font-bold uppercase font-mono">Remote</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-800/80 text-[10px] text-slate-400">
                            <span>📍 Selangor</span>
                            <button class="bg-purple-600 hover:bg-purple-700 text-white font-extrabold px-3 py-1 rounded-lg transition">
                                Mohon
                            </button>
                        </div>
                    </div>

                    <!-- Contoh Syarikat 3 -->
                    <div class="bg-slate-900/60 p-3.5 rounded-xl border border-slate-750 space-y-2.5 hover:border-purple-500/40 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-xs font-black text-white tracking-wide">Vortex Systems</h4>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Data Analyst Intern</span>
                            </div>
                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-0.5 rounded text-[9px] font-bold uppercase font-mono">Full-time</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-800/80 text-[10px] text-slate-400">
                            <span>📍 Penang</span>
                            <button class="bg-purple-600 hover:bg-purple-700 text-white font-extrabold px-3 py-1 rounded-lg transition">
                                Mohon
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>