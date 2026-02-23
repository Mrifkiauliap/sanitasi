{{--
    ============================================================
    TOAST NOTIFICATION COMPONENT  (memory-safe version)
    ============================================================
    Root cause fix: @foreach Blade loop yang render 6 posisi
    × nested x-for Alpine menyebabkan PHP OOM saat compile view.
    Solusi: hapus @foreach — semua posisi dihandle murni Alpine JS.

    Props:
        default-position  : top-right (default) | top-left | top-center
                            bottom-right | bottom-left | bottom-center
        max-toasts        : integer, default 5
        theme             : 'light' (default) | 'dark'

    Usage:
        <x-toast-notification />
        <x-toast-notification theme="dark" default-position="bottom-right" :max-toasts="3" />

    Trigger via JS:
        window.dispatchEvent(new CustomEvent('open-toast', {
            detail: {
                type: 'success',        // success | error | danger | warning | info
                title: 'Berhasil!',
                message: 'Data disimpan.',
                position: 'top-right',  // opsional — override default-position
                duration: 5000,         // ms, default 5000
                autoClose: true,        // default true
                dismissible: true,      // default true
            }
        }));

    Trigger via Livewire:
        $this->dispatch('open-toast', type: 'success', title: 'OK', message: '...');

    Tutup semua:
        window.dispatchEvent(new CustomEvent('close-all-toasts'));
    ============================================================
--}}

@props([
    'defaultPosition' => 'top-right',
    'maxToasts'       => 5,
    'theme'           => 'light',
])

@php
    $isDark = $theme === 'dark';

    // Semua warna di-resolve PHP saat render — tidak ada dark: prefix Tailwind
    $styles = [
        'cardBg'          => $isDark ? 'rgba(17,24,39,0.97)'              : 'rgba(255,255,255,0.97)',
        'cardBorder'      => $isDark ? 'rgba(255,255,255,0.07)'           : 'rgba(0,0,0,0.06)',
        'cardShadow'      => $isDark ? '0 8px 32px -4px rgba(0,0,0,0.5),0 2px 8px -2px rgba(0,0,0,0.4)'
                                     : '0 8px 32px -4px rgba(0,0,0,0.10),0 2px 8px -2px rgba(0,0,0,0.06)',
        'titleColor'      => $isDark ? '#f9fafb'                          : '#111827',
        'msgColor'        => $isDark ? '#9ca3af'                          : '#6b7280',
        'progressTrack'   => $isDark ? 'rgba(255,255,255,0.06)'           : 'rgba(0,0,0,0.06)',
        'closeColor'      => $isDark ? '#6b7280'                          : '#9ca3af',
        'closeHoverColor' => $isDark ? '#e5e7eb'                          : '#374151',
        'closeHoverBg'    => $isDark ? 'rgba(255,255,255,0.1)'            : 'rgba(0,0,0,0.05)',
    ];

    // Type configs — di-encode jadi JSON untuk dipakai Alpine
    $typeMap = [
        'success' => [
            'bubbleBg'   => $isDark ? 'rgba(16,185,129,0.15)'   : '#ecfdf5',
            'bubbleText' => $isDark ? '#34d399'                  : '#059669',
            'bubbleRing' => $isDark ? 'rgba(52,211,153,0.25)'   : 'rgba(16,185,129,0.3)',
            'gradient'   => 'linear-gradient(to right,#10b981,#14b8a6)',
            'accent'     => '#10b981',
        ],
        'error' => [
            'bubbleBg'   => $isDark ? 'rgba(239,68,68,0.15)'    : '#fef2f2',
            'bubbleText' => $isDark ? '#f87171'                  : '#dc2626',
            'bubbleRing' => $isDark ? 'rgba(248,113,113,0.25)'  : 'rgba(239,68,68,0.3)',
            'gradient'   => 'linear-gradient(to right,#ef4444,#f43f5e)',
            'accent'     => '#ef4444',
        ],
        'danger' => [
            'bubbleBg'   => $isDark ? 'rgba(239,68,68,0.15)'    : '#fef2f2',
            'bubbleText' => $isDark ? '#f87171'                  : '#dc2626',
            'bubbleRing' => $isDark ? 'rgba(248,113,113,0.25)'  : 'rgba(239,68,68,0.3)',
            'gradient'   => 'linear-gradient(to right,#ef4444,#f43f5e)',
            'accent'     => '#ef4444',
        ],
        'warning' => [
            'bubbleBg'   => $isDark ? 'rgba(245,158,11,0.15)'   : '#fffbeb',
            'bubbleText' => $isDark ? '#fbbf24'                  : '#d97706',
            'bubbleRing' => $isDark ? 'rgba(251,191,36,0.25)'   : 'rgba(245,158,11,0.3)',
            'gradient'   => 'linear-gradient(to right,#f59e0b,#f97316)',
            'accent'     => '#f59e0b',
        ],
        'info' => [
            'bubbleBg'   => $isDark ? 'rgba(59,130,246,0.15)'   : '#eff6ff',
            'bubbleText' => $isDark ? '#60a5fa'                  : '#2563eb',
            'bubbleRing' => $isDark ? 'rgba(96,165,250,0.25)'   : 'rgba(59,130,246,0.3)',
            'gradient'   => 'linear-gradient(to right,#3b82f6,#6366f1)',
            'accent'     => '#3b82f6',
        ],
    ];

    $typeMapJson = json_encode($typeMap);

    // Position CSS map — dipakai Alpine untuk set style container
    $posMap = json_encode([
        'top-right'     => ['top'=>'1.25rem','right'=>'1.25rem','bottom'=>'auto','left'=>'auto','transform'=>'none'],
        'top-left'      => ['top'=>'1.25rem','right'=>'auto','bottom'=>'auto','left'=>'1.25rem','transform'=>'none'],
        'top-center'    => ['top'=>'1.25rem','right'=>'auto','bottom'=>'auto','left'=>'50%','transform'=>'translateX(-50%)'],
        'bottom-right'  => ['top'=>'auto','right'=>'1.25rem','bottom'=>'1.25rem','left'=>'auto','transform'=>'none'],
        'bottom-left'   => ['top'=>'auto','right'=>'auto','bottom'=>'1.25rem','left'=>'1.25rem','transform'=>'none'],
        'bottom-center' => ['top'=>'auto','right'=>'auto','bottom'=>'1.25rem','left'=>'50%','transform'=>'translateX(-50%)'],
    ]);
@endphp

{{-- Satu wrapper — semua posisi dirender Alpine, tidak ada @foreach Blade --}}
<div
    x-data="{
        notifications: [],
        defaultPosition: '{{ $defaultPosition }}',
        maxToasts: {{ $maxToasts }},

        /* Warna dari PHP — sudah fixed sesuai theme prop */
        types: {{ Js::from($typeMap) }},
        posMap: {{ Js::from([
            'top-right'     => ['top'=>'1.25rem','right'=>'1.25rem','bottom'=>'auto','left'=>'auto','transform'=>'none'],
            'top-left'      => ['top'=>'1.25rem','right'=>'auto','bottom'=>'auto','left'=>'1.25rem','transform'=>'none'],
            'top-center'    => ['top'=>'1.25rem','right'=>'auto','bottom'=>'auto','left'=>'50%','transform'=>'translateX(-50%)'],
            'bottom-right'  => ['top'=>'auto','right'=>'1.25rem','bottom'=>'1.25rem','left'=>'auto','transform'=>'none'],
            'bottom-left'   => ['top'=>'auto','right'=>'auto','bottom'=>'1.25rem','left'=>'1.25rem','transform'=>'none'],
            'bottom-center' => ['top'=>'auto','right'=>'auto','bottom'=>'1.25rem','left'=>'50%','transform'=>'translateX(-50%)'],
        ]) }},
        positions: ['top-right','top-left','top-center','bottom-right','bottom-left','bottom-center'],

        c(type) { return this.types[type] || this.types.info; },

        positionStyle(pos) {
            const p = this.posMap[pos] || this.posMap['top-right'];
            return [
                'position:fixed',
                'z-index:9999',
                'display:flex',
                'flex-direction:column',
                'gap:0.625rem',
                'width:calc(100% - 2rem)',
                'max-width:22rem',
                'pointer-events:none',
                'top:'    + p.top,
                'right:'  + p.right,
                'bottom:' + p.bottom,
                'left:'   + p.left,
                'transform:' + p.transform,
            ].join(';');
        },

        notifsByPos(pos) {
            return this.notifications.filter(n => n.position === pos);
        },

        add(detail) {
            const pos = detail.position || this.defaultPosition;
            const atPos = this.notifications.filter(n => n.position === pos);
            if (atPos.length >= this.maxToasts) this.remove(atPos[0].id);

            const id        = Date.now() + Math.random();
            const duration  = detail.duration || 5000;
            const autoClose   = detail.autoClose   !== undefined ? detail.autoClose   : true;
            const dismissible = detail.dismissible !== undefined ? detail.dismissible : true;

            this.notifications.push({
                id, duration,
                type: detail.type || 'info',
                title: detail.title || '',
                message: detail.message || '',
                position: pos, autoClose, dismissible,
                remaining: duration,
                start: null, timer: null,
                paused: false, visible: true,
            });

            if (autoClose) this.$nextTick(() => this.startTimer(id));
        },

        remove(id) {
            const n = this.notifications.find(n => n.id === id);
            if (!n) return;
            clearTimeout(n.timer);
            n.visible = false;
            setTimeout(() => { this.notifications = this.notifications.filter(x => x.id !== id); }, 300);
        },

        removeAll() {
            this.notifications.forEach(n => clearTimeout(n.timer));
            this.notifications = [];
        },

        startTimer(id) {
            const n = this.notifications.find(n => n.id === id);
            if (!n || !n.autoClose) return;
            n.start = Date.now();
            clearTimeout(n.timer);
            n.timer = setTimeout(() => { if (!n.paused) this.remove(id); }, n.remaining);
        },

        pauseTimer(id) {
            const n = this.notifications.find(n => n.id === id);
            if (!n || !n.timer || n.paused) return;
            clearTimeout(n.timer);
            n.paused = true;
            n.remaining -= (Date.now() - n.start);
        },

        resumeTimer(id) {
            const n = this.notifications.find(n => n.id === id);
            if (!n || !n.paused) return;
            n.paused = false;
            this.startTimer(id);
        },
    }"
    x-on:open-toast.window="add($event.detail)"
    x-on:close-all-toasts.window="removeAll()"
    role="region"
    aria-label="Notifikasi"
    aria-live="polite"
    aria-atomic="false"
>
    {{--
        SATU x-for untuk semua posisi — tidak ada @foreach Blade.
        Alpine render 6 container posisi, masing-masing punya x-for notifikasi.
    --}}
    <template x-for="pos in positions" :key="pos">
        <div :style="positionStyle(pos)">
            <template x-for="n in notifsByPos(pos)" :key="n.id">
                <div
                    x-show="n.visible"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    @mouseenter="pauseTimer(n.id)"
                    @mouseleave="resumeTimer(n.id)"
                    @focusin="pauseTimer(n.id)"
                    @focusout="resumeTimer(n.id)"
                    :style="`
                        position:relative;
                        width:100%;
                        overflow:hidden;
                        border-radius:1rem;
                        pointer-events:auto;
                        backdrop-filter:blur(12px);
                        background:{{ $styles['cardBg'] }};
                        border:1px solid {{ $styles['cardBorder'] }};
                        box-shadow:{{ $styles['cardShadow'] }};
                    `"
                    role="alert"
                    :aria-label="n.title + (n.message ? ': ' + n.message : '')"
                >
                    {{-- Accent bar kiri --}}
                    <div :style="'position:absolute;left:0;top:0;bottom:0;width:3px;border-radius:1rem 0 0 1rem;background:'+c(n.type).accent"></div>

                    <div style="padding:0.875rem 0.75rem 0.875rem 1rem;">
                        <div style="display:flex;align-items:flex-start;gap:0.75rem;">

                            {{-- Icon bubble --}}
                            <div style="flex-shrink:0;margin-top:2px;">
                                <div
                                    :style="'width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;ring:1px inset;background:'+c(n.type).bubbleBg+';color:'+c(n.type).bubbleText+';box-shadow:inset 0 0 0 1px '+c(n.type).bubbleRing"
                                >
                                    <template x-if="n.type === 'success'">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                                        </svg>
                                    </template>
                                    <template x-if="n.type === 'error' || n.type === 'danger'">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                        </svg>
                                    </template>
                                    <template x-if="n.type === 'warning'">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                                        </svg>
                                    </template>
                                    <template x-if="n.type === 'info'">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                    </template>
                                </div>
                            </div>

                            {{-- Teks --}}
                            <div style="flex:1;min-width:0;padding-top:2px;">
                                <p
                                    x-show="n.title"
                                    x-text="n.title"
                                    style="font-size:0.875rem;font-weight:600;line-height:1.4;letter-spacing:-0.01em;margin:0;color:{{ $styles['titleColor'] }}"
                                ></p>
                                <p
                                    x-show="n.message"
                                    x-text="n.message"
                                    :style="'font-size:0.75rem;line-height:1.5;margin:0;color:{{ $styles['msgColor'] }};' + (n.title ? 'margin-top:2px' : '')"
                                ></p>
                            </div>

                            {{-- Tombol tutup --}}
                            <template x-if="n.dismissible">
                                <button
                                    @click.stop="remove(n.id)"
                                    type="button"
                                    style="flex-shrink:0;padding:6px;border-radius:8px;border:none;background:transparent;cursor:pointer;color:{{ $styles['closeColor'] }};transition:all .15s;margin:-2px -2px 0 0;"
                                    onmouseover="this.style.color='{{ $styles['closeHoverColor'] }}';this.style.background='{{ $styles['closeHoverBg'] }}'"
                                    onmouseout="this.style.color='{{ $styles['closeColor'] }}';this.style.background='transparent'"
                                    aria-label="Tutup notifikasi"
                                >
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    <template x-if="n.autoClose">
                        <div :style="'position:absolute;bottom:0;left:0;right:0;height:2px;overflow:hidden;border-radius:0 0 1rem 1rem;background:{{ $styles['progressTrack'] }}'">
                            <div :style="`background:${c(n.type).gradient};animation:toast-shrink ${n.duration}ms linear forwards;animation-play-state:${n.paused?'paused':'running'};height:100%;`"></div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </template>

    <style>
        @keyframes toast-shrink { from { width:100%; } to { width:0%; } }
    </style>
</div>
