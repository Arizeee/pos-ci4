<header class="bg-white border-b border-stone-200 px-6 py-4 sticky top-0 z-30">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-stone-800" id="pageTitle">Dashboard</h1>
            <p class="text-stone-500 text-sm" id="currentDate"></p>
        </div>
        <div class="flex items-center gap-4">
            <button class="relative p-2 text-stone-500 hover:bg-stone-100 rounded-full">
                <span class="text-xl">🔔</span>
                <span
                    class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
            </button>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-xl">👤</span>
                </div>
                <div class="hidden sm:block">
                    <p class="font-medium text-stone-800">Admin</p>
                    <p class="text-stone-500 text-sm">Super Admin</p>
                </div>
            </div>
        </div>
    </div>
</header>
