<!-- Sidebar Overlay (Mobile) -->
<div x-show="sidebarOpen"
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-20 bg-sidebar-foreground/20 backdrop-blur-sm lg:hidden"
     style="display: none;">
</div>

<!-- Sidebar -->
<aside
    x-bind:class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        isEffectiveCollapsed() ? 'lg:w-20' : 'w-72'
    ]"
    class="fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-300 ease-in-out bg-sidebar-background text-sidebar-foreground border-r border-sidebar-border shadow-sidebar lg:static lg:translate-x-0">

    <!-- Logo Section -->
    <div class="flex items-center px-4 py-3 border-b border-sidebar-border bg-gradient-to-r from-sidebar-accent to-white h-16"
         x-bind:class="isEffectiveCollapsed() ? 'justify-center' : 'justify-between'">
        <div x-show="!isEffectiveCollapsed()" class="flex items-center gap-3 overflow-hidden whitespace-nowrap"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-x-2"
             x-transition:enter-end="opacity-100 translate-x-0">
            <div class="flex-shrink-0 p-1.5 bg-gradient-to-br from-sky-500 to-blue-600 rounded-xl shadow-lg shadow-sky-500/30">
                 <x-lucide-droplets class="w-6 h-6 text-white" />
            </div>
            <div class="leading-none">
                <h1 class="text-base font-extrabold tracking-tight text-sidebar-foreground">
                    {{ env('APP_NAME', 'SIBANHUM') }}
                </h1>
                <p class="text-[9px] font-semibold text-sidebar-foreground/50 mt-0.5 uppercase tracking-widest">
                    Monitoring Sanitasi &amp; Air
                </p>
            </div>
        </div>
        <!-- Collapse Toggle Button (Desktop ONLY) -->
        <button x-on:click="toggleSidebar()"
                class="hidden lg:flex p-1.5 rounded-lg text-sidebar-foreground/40 hover:bg-sidebar-accent hover:text-sidebar-primary transition-colors focus:outline-none"
                :class="isEffectiveCollapsed() ? 'mt-1' : ''">
            <x-lucide-chevrons-left class="w-4 h-4 transition-transform duration-300"
                x-bind:class="isEffectiveCollapsed() ? 'rotate-180' : ''" />
        </button>
    </div>

    <!-- Date & Time Section (Hidden on Collapse) -->
    <div x-show="!isEffectiveCollapsed()"
         class="px-4 py-3 bg-sidebar-accent/30 border-b border-sidebar-border overflow-hidden whitespace-nowrap">
        <div x-data="{
            currentTime: '',
            currentDate: '',
            updateDateTime() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit', minute: '2-digit', hour12: false
                });
                const options = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' };
                this.currentDate = now.toLocaleDateString('id-ID', options);
            }
        }"
        x-init="updateDateTime(); setInterval(() => updateDateTime(), 1000)"
        class="flex items-center justify-between text-xs text-sidebar-foreground/80 font-medium">
            <div class="flex items-center gap-1.5">
                <x-lucide-calendar class="w-3.5 h-3.5 text-sidebar-primary" />
                <span x-text="currentDate"></span>
            </div>
            <div class="flex items-center gap-1.5 bg-sidebar-accent/50 px-2 py-0.5 rounded text-sidebar-primary">
                <x-lucide-clock class="w-3.5 h-3.5" />
                <span x-text="currentTime"></span>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto custom-scrollbar overflow-x-hidden">
        {{-- Section label --}}
        <p x-show="!isEffectiveCollapsed()"
           class="px-3 mb-2 text-[9px] font-bold uppercase tracking-widest text-sidebar-foreground/30">Menu Utama</p>

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 mb-1 whitespace-nowrap
                  {{ request()->routeIs('dashboard')
                     ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-md shadow-sky-500/25'
                     : 'text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}"
           :title="isEffectiveCollapsed() ? 'Dashboard' : ''">
            @if(request()->routeIs('dashboard'))
                <span class="absolute left-0 top-1/4 h-1/2 w-0.5 rounded-r-full bg-white/50" x-show="isEffectiveCollapsed()"></span>
            @endif
            <x-lucide-layout-dashboard class="w-4 h-4 flex-shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-sidebar-foreground/40 group-hover:text-sidebar-primary' }}"
                x-bind:class="!isEffectiveCollapsed() ? 'mr-3' : 'mr-0 mx-auto'" />
            <span x-show="!isEffectiveCollapsed()"
                  x-transition:enter="transition ease-out duration-200 delay-100"
                  x-transition:enter-start="opacity-0 translate-x-2"
                  x-transition:enter-end="opacity-100 translate-x-0">
                Dashboard
            </span>
        </a>

        {{-- Penyaluran Air --}}
        <a href="{{ route('penyaluran-air.index') }}"
           class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 whitespace-nowrap
                  {{ request()->routeIs('penyaluran-air.*') ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-md shadow-sky-500/25' : 'text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}"
           :title="isEffectiveCollapsed() ? 'Penyaluran Air' : ''">
            <div class="flex items-center w-full" :class="isEffectiveCollapsed() ? 'justify-center' : ''">
                <x-lucide-droplets class="w-4 h-4 flex-shrink-0 transition-colors {{ request()->routeIs('penyaluran-air.*') ? 'text-white' : 'text-sidebar-foreground/40 group-hover:text-sidebar-primary' }}"
                                   x-bind:class="!isEffectiveCollapsed() ? 'mr-3' : 'mr-0'" />
                <span x-show="!isEffectiveCollapsed()"
                      x-transition:enter="transition ease-out duration-150"
                      x-transition:enter-start="opacity-0 translate-x-2"
                      x-transition:enter-end="opacity-100 translate-x-0">
                    Penyaluran Air
                </span>
            </div>
        </a>

        {{-- Laporan Kondisi --}}
        <a href="{{ route('laporan-kondisi.index') }}"
           class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 whitespace-nowrap
                  {{ request()->routeIs('laporan-kondisi.*') ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-md shadow-sky-500/25' : 'text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}"
           :title="isEffectiveCollapsed() ? 'Laporan Kondisi' : ''">
            <div class="flex items-center w-full" :class="isEffectiveCollapsed() ? 'justify-center' : ''">
                <x-lucide-clipboard-list class="w-4 h-4 flex-shrink-0 transition-colors {{ request()->routeIs('laporan-kondisi.*') ? 'text-white' : 'text-sidebar-foreground/40 group-hover:text-sidebar-primary' }}"
                                         x-bind:class="!isEffectiveCollapsed() ? 'mr-3' : 'mr-0'" />
                <span x-show="!isEffectiveCollapsed()"
                      x-transition:enter="transition ease-out duration-150"
                      x-transition:enter-start="opacity-0 translate-x-2"
                      x-transition:enter-end="opacity-100 translate-x-0">
                    Laporan Kondisi
                </span>
            </div>
        </a>

        {{-- Group: Manajemen Data --}}
        <div x-data="{
                expanded: {{ request()->routeIs('manajemen-data.*') ? 'true' : 'false' }},
                hovered: false,
                timeout: null,
                top: 0,
                handleMouseEnter() {
                    if (this.isEffectiveCollapsed()) {
                        this.top = this.$el.getBoundingClientRect().top;
                        clearTimeout(this.timeout);
                        this.hovered = true;
                    }
                },
                handleMouseLeave() {
                    if (this.isEffectiveCollapsed()) {
                        this.timeout = setTimeout(() => this.hovered = false, 200);
                    }
                }
             }"
             @mouseenter="handleMouseEnter()"
             @mouseleave="handleMouseLeave()"
             class="mb-2 relative">

            <button x-on:click="isEffectiveCollapsed() ? (toggleSidebar(), expanded = true) : expanded = !expanded"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 whitespace-nowrap focus:outline-none"
                    :class="(expanded && !isEffectiveCollapsed()) ? 'bg-sidebar-accent/50 text-sidebar-accent-foreground' : 'text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground'"
                    :title="isEffectiveCollapsed() ? 'Manajemen Data' : ''">
                <div class="flex items-center w-full" :class="isEffectiveCollapsed() ? 'justify-center' : ''">
                    <x-lucide-database class="w-4 h-4 flex-shrink-0 transition-colors {{ request()->routeIs('manajemen-data.*') ? 'text-sky-600' : 'text-sidebar-foreground/40 group-hover:text-sidebar-primary' }}"
                                       x-bind:class="!isEffectiveCollapsed() ? 'mr-3' : 'mr-0'" />
                    <span x-show="!isEffectiveCollapsed()" class="flex-1 text-left {{ request()->routeIs('manajemen-data.*') ? 'text-sky-700 font-semibold' : '' }}">Manajemen Data</span>
                </div>
                <x-lucide-chevron-right x-show="!isEffectiveCollapsed()"
                                        class="w-4 h-4 text-sidebar-foreground/40 transition-transform duration-200"
                                        x-bind:class="expanded ? 'rotate-90' : ''" />
            </button>

            <div x-show="expanded && !isEffectiveCollapsed()"
                 x-collapse
                 class="space-y-1 mt-1 pl-3 border-l-2 border-sky-100 ml-4">
                <a href="{{ route('manajemen-data.wilayah.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200
                          {{ request()->routeIs('manajemen-data.wilayah.*') ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-sm' : 'text-sidebar-foreground/60 hover:text-sidebar-foreground hover:bg-sidebar-accent/50' }}">
                    <x-lucide-map-pin class="w-3.5 h-3.5 mr-2 {{ request()->routeIs('manajemen-data.wilayah.*') ? 'text-white' : 'text-sidebar-foreground/40' }}" />
                    Wilayah
                </a>
                <a href="{{ route('manajemen-data.sanitasi.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200
                          {{ request()->routeIs('manajemen-data.sanitasi.*') ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-sm' : 'text-sidebar-foreground/60 hover:text-sidebar-foreground hover:bg-sidebar-accent/50' }}">
                    <x-lucide-droplets class="w-3.5 h-3.5 mr-2 {{ request()->routeIs('manajemen-data.sanitasi.*') ? 'text-white' : 'text-sidebar-foreground/40' }}" />
                    Sanitasi
                </a>
            </div>

            <!-- Flyout (Desktop collapsed only) -->
            <template x-teleport="body">
                <div x-show="isEffectiveCollapsed() && hovered"
                     @mouseenter="clearTimeout(timeout); hovered = true"
                     @mouseleave="timeout = setTimeout(() => hovered = false, 200)"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-x-2"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 -translate-x-2"
                     class="fixed z-50 w-48 py-1 ml-4 bg-sidebar-background border border-sidebar-border shadow-xl rounded-md"
                     :style="{ left: '5rem', top: top + 'px' }">

                    <div class="px-3 py-2 text-xs font-bold text-sidebar-foreground/50 uppercase tracking-wider border-b border-sidebar-border/50 mb-1">
                        Manajemen Data
                    </div>

                    <div class="space-y-0.5 p-1">
                        <a href="{{ route('manajemen-data.wilayah.index') }}"
                        class="block px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('manajemen-data.wilayah.*') ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-sm' : 'text-sidebar-foreground/70 hover:text-sidebar-primary hover:bg-sidebar-accent' }}">
                            Wilayah
                        </a>
                        <a href="{{ route('manajemen-data.sanitasi.index') }}"
                        class="block px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('manajemen-data.sanitasi.*') ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-sm' : 'text-sidebar-foreground/70 hover:text-sidebar-primary hover:bg-sidebar-accent' }}">
                            Sanitasi
                        </a>
                    </div>
                </div>
            </template>
        </div>

        {{-- Group: Pengaturan --}}
        <div x-data="{
                expanded: {{ request()->routeIs('pengaturan.*') ? 'true' : 'false' }},
                hovered: false,
                timeout: null,
                top: 0,
                handleMouseEnter() {
                    if (this.isEffectiveCollapsed()) {
                        this.top = this.$el.getBoundingClientRect().top;
                        clearTimeout(this.timeout);
                        this.hovered = true;
                    }
                },
                handleMouseLeave() {
                    if (this.isEffectiveCollapsed()) {
                        this.timeout = setTimeout(() => this.hovered = false, 200);
                    }
                }
             }"
             @mouseenter="handleMouseEnter()"
             @mouseleave="handleMouseLeave()"
             class="mb-2 relative">

            <button x-on:click="isEffectiveCollapsed() ? (toggleSidebar(), expanded = true) : expanded = !expanded"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 whitespace-nowrap focus:outline-none"
                    :class="(expanded && !isEffectiveCollapsed()) ? 'bg-sidebar-accent/50 text-sidebar-accent-foreground' : 'text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground'"
                    :title="isEffectiveCollapsed() ? 'Pengaturan' : ''">
                <div class="flex items-center w-full" :class="isEffectiveCollapsed() ? 'justify-center' : ''">
                    <x-lucide-settings class="w-4 h-4 flex-shrink-0 transition-colors {{ request()->routeIs('pengaturan.*') ? 'text-sky-600' : 'text-sidebar-foreground/40 group-hover:text-sidebar-primary' }}"
                                       x-bind:class="!isEffectiveCollapsed() ? 'mr-3' : 'mr-0'" />
                    <span x-show="!isEffectiveCollapsed()" class="flex-1 text-left {{ request()->routeIs('pengaturan.*') ? 'text-sky-700 font-semibold' : '' }}">Pengaturan</span>
                </div>
                <x-lucide-chevron-right x-show="!isEffectiveCollapsed()"
                                        class="w-4 h-4 text-sidebar-foreground/40 transition-transform duration-200"
                                        x-bind:class="expanded ? 'rotate-90' : ''" />
            </button>

            <div x-show="expanded && !isEffectiveCollapsed()"
                 x-collapse
                 class="space-y-1 mt-1 pl-3 border-l-2 border-sky-100 ml-4">
                <a href="{{ route('pengaturan.manajemen-user.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200
                          {{ request()->routeIs('pengaturan.manajemen-user.*') ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-sm' : 'text-sidebar-foreground/60 hover:text-sidebar-foreground hover:bg-sidebar-accent/50' }}">
                    <x-lucide-user-cog class="w-3.5 h-3.5 mr-2 {{ request()->routeIs('pengaturan.manajemen-user.*') ? 'text-white' : 'text-sidebar-foreground/40' }}" />
                    Manajemen User
                </a>
            </div>

            <!-- Flyout (Desktop collapsed only) -->
            <template x-teleport="body">
                <div x-show="isEffectiveCollapsed() && hovered"
                     @mouseenter="clearTimeout(timeout); hovered = true"
                     @mouseleave="timeout = setTimeout(() => hovered = false, 200)"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-x-2"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 -translate-x-2"
                     class="fixed z-50 w-48 py-1 ml-4 bg-sidebar-background border border-sidebar-border shadow-xl rounded-md"
                     :style="{ left: '5rem', top: top + 'px' }">

                    <div class="px-3 py-2 text-xs font-bold text-sidebar-foreground/50 uppercase tracking-wider border-b border-sidebar-border/50 mb-1">
                        Pengaturan
                    </div>

                    <div class="space-y-0.5 p-1">
                        <a href="{{ route('pengaturan.manajemen-user.index') }}"
                        class="block px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('pengaturan.manajemen-user.*') ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-sm' : 'text-sidebar-foreground/70 hover:text-sidebar-primary hover:bg-sidebar-accent' }}">
                            Manajemen User
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </nav>
</aside>
