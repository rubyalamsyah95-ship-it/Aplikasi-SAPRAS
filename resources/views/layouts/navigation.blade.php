<div class="flex items-center">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="inline-flex items-center px-4 py-2 border border-slate-100 text-sm font-semibold rounded-2xl text-slate-600 bg-slate-50 hover:bg-white hover:shadow-md transition-all duration-200 focus:outline-none">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs shadow-sm font-bold uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="text-left hidden sm:block">
                        <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ Auth::user()->role }}</p>
                    </div>
                    <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            <!-- HEADER: NAMA & ROLE -->
            <div class="px-4 py-3 border-b border-slate-50 mb-1">
                <p class="text-sm font-bold text-slate-800 leading-tight uppercase">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest mt-1">
                    {{ Auth::user()->role }}
                </p>
            </div>

            <!-- TOMBOL LOGOUT (Satu-satunya pilihan klik) -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')" 
                        class="text-red-500 hover:bg-red-50 font-bold flex items-center py-2.5 transition-all duration-200"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="bi bi-box-arrow-right mr-2 text-lg"></i> {{ __('Logout') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</div>