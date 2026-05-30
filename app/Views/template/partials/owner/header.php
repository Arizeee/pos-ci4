<header class="bg-white border-b border-stone-200 px-6 py-4 sticky top-0 z-30">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-stone-800" id="pageTitle">Overview</h1>
            <p class="text-stone-500 text-sm" id="currentDate"></p>
        </div>
        <div class="flex items-center gap-4">
            <!-- Period Selector -->
            <div class="hidden md:flex items-center gap-2 bg-stone-100 rounded-xl p-1">
                <button onclick="setPeriod('today')"
                        class="period-btn active px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    Hari Ini
                </button>
                <button onclick="setPeriod('week')"
                        class="period-btn px-4 py-2 rounded-lg text-sm font-medium text-stone-600 hover:bg-white hover:shadow-sm transition-all">
                    Minggu Ini
                </button>
                <button onclick="setPeriod('month')"
                        class="period-btn px-4 py-2 rounded-lg text-sm font-medium text-stone-600 hover:bg-white hover:shadow-sm transition-all">
                    Bulan Ini
                </button>
                <button onclick="setPeriod('year')"
                        class="period-btn px-4 py-2 rounded-lg text-sm font-medium text-stone-600 hover:bg-white hover:shadow-sm transition-all">
                    Tahun Ini
                </button>
            </div>

            <!-- Notifications -->
            <button class="relative p-2 text-stone-500 hover:bg-stone-100 rounded-full transition-colors">
                <span class="text-xl">🔔</span>
                <span
                    class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-medium">5</span>
            </button>

            <!-- Quick Actions -->
            <div class="relative">
                <button onclick="toggleQuickActions()"
                        class="flex items-center gap-2 bg-amber-700 hover:bg-amber-800 text-white px-4 py-2 rounded-xl font-medium transition-colors">
                    <span>⚡</span>
                    <span class="hidden sm:inline">Quick Actions</span>
                </button>
                <div id="quickActionsMenu"
                     class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-stone-200 py-2 z-50">
                    <a href="#" onclick="exportData()"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-stone-50 text-stone-700">
                        <span>📥</span> Export Data
                    </a>
                    <a href="#" onclick="generateReport()"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-stone-50 text-stone-700">
                        <span>📊</span> Generate Report
                    </a>
                    <a href="#" onclick="viewAnalytics()"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-stone-50 text-stone-700">
                        <span>📈</span> View Analytics
                    </a>
                    <hr class="my-2 border-stone-100">
                    <a href="#" onclick="refreshData()"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-stone-50 text-stone-700">
                        <span>🔄</span> Refresh Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
