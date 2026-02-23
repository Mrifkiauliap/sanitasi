{{--
    ============================================================
    CONFIRM MODAL COMPONENT  (memory-safe version)
    ============================================================
    Root cause fix: @foreach + nested interpolation yang berat
    diganti dengan Js::from() — PHP hanya render sekali,
    Alpine handle semua logic runtime.

    Props:
        theme : 'light' (default) | 'dark'

    Usage:
        <x-modal-confirm />
        <x-modal-confirm theme="dark" />

    Trigger via JS:
        window.dispatchEvent(new CustomEvent('open-confirm-modal', {
            detail: {
                title: 'Hapus Data?',
                message: 'Aksi ini <strong>tidak bisa dibatalkan</strong>.',
                action: '/items/1',
                method: 'DELETE',           // POST | PUT | PATCH | DELETE
                type: 'danger',             // danger | success | info | warning
                confirmText: 'Ya, Hapus',   // opsional
                cancelText: 'Batal',        // opsional
                size: 'md',                 // sm | md | lg
            }
        }));

    Trigger via Livewire:
        $this->dispatch('open-confirm-modal', type: 'danger', title: '...', action: '...', method: 'DELETE');

    Trigger via Alpine:
        $dispatch('open-confirm-modal', { type: 'danger', title: '...', action: '...', method: 'DELETE' })
    ============================================================
--}}

@props([
    'theme' => 'light',
])

@php
    $isDark = $theme === 'dark';

    $styles = [
        'backdropBg'   => $isDark ? 'rgba(0,0,0,0.65)'              : 'rgba(15,17,23,0.45)',
        'modalBg'      => $isDark ? '#111827'                        : '#ffffff',
        'modalBorder'  => $isDark ? 'rgba(255,255,255,0.07)'        : 'rgba(0,0,0,0.07)',
        'modalShadow'  => $isDark ? '0 24px 80px -12px rgba(0,0,0,0.7),0 8px 32px -8px rgba(0,0,0,0.5)'
                                  : '0 24px 80px -12px rgba(0,0,0,0.22),0 8px 32px -8px rgba(0,0,0,0.1)',
        'footerBg'     => $isDark ? 'rgba(255,255,255,0.03)'        : 'rgba(249,250,251,0.8)',
        'footerBorder' => $isDark ? 'rgba(255,255,255,0.06)'        : 'rgba(0,0,0,0.06)',
        'titleColor'   => $isDark ? '#f9fafb'                        : '#111827',
        'msgColor'     => $isDark ? '#9ca3af'                        : '#6b7280',
        'cancelBg'     => $isDark ? 'rgba(255,255,255,0.07)'        : '#ffffff',
        'cancelBorder' => $isDark ? 'rgba(255,255,255,0.12)'        : '#e5e7eb',
        'cancelColor'  => $isDark ? '#e5e7eb'                        : '#374151',
        'cancelHover'  => $isDark ? 'rgba(255,255,255,0.12)'        : '#f9fafb',
        'closeColor'   => $isDark ? '#6b7280'                        : '#9ca3af',
        'closeHover'   => $isDark ? '#e5e7eb'                        : '#374151',
        'closeHoverBg' => $isDark ? 'rgba(255,255,255,0.08)'        : 'rgba(0,0,0,0.05)',
    ];

    $typeMap = [
        'danger' => [
            'iconColor'   => $isDark ? '#f87171'                    : '#dc2626',
            'iconBg'      => $isDark ? 'rgba(239,68,68,0.15)'       : '#fef2f2',
            'iconRing'    => $isDark ? 'rgba(248,113,113,0.2)'      : '#fecaca',
            'headerGrad'  => $isDark ? 'linear-gradient(135deg,rgba(239,68,68,0.1),rgba(244,63,94,0.05))'
                                     : 'linear-gradient(135deg,#fef2f2,#fff1f2)',
            'btnBg'       => $isDark ? '#b91c1c'                    : '#dc2626',
            'btnHover'    => $isDark ? '#991b1b'                    : '#b91c1c',
            'defaultText' => 'Ya, Hapus',
        ],
        'error' => [
            'iconColor'   => $isDark ? '#f87171'                    : '#dc2626',
            'iconBg'      => $isDark ? 'rgba(239,68,68,0.15)'       : '#fef2f2',
            'iconRing'    => $isDark ? 'rgba(248,113,113,0.2)'      : '#fecaca',
            'headerGrad'  => $isDark ? 'linear-gradient(135deg,rgba(239,68,68,0.1),rgba(244,63,94,0.05))'
                                     : 'linear-gradient(135deg,#fef2f2,#fff1f2)',
            'btnBg'       => $isDark ? '#b91c1c'                    : '#dc2626',
            'btnHover'    => $isDark ? '#991b1b'                    : '#b91c1c',
            'defaultText' => 'Ya, Hapus',
        ],
        'success' => [
            'iconColor'   => $isDark ? '#34d399'                    : '#059669',
            'iconBg'      => $isDark ? 'rgba(16,185,129,0.15)'      : '#ecfdf5',
            'iconRing'    => $isDark ? 'rgba(52,211,153,0.2)'       : '#a7f3d0',
            'headerGrad'  => $isDark ? 'linear-gradient(135deg,rgba(16,185,129,0.1),rgba(20,184,166,0.05))'
                                     : 'linear-gradient(135deg,#ecfdf5,#f0fdf4)',
            'btnBg'       => $isDark ? '#047857'                    : '#059669',
            'btnHover'    => $isDark ? '#065f46'                    : '#047857',
            'defaultText' => 'Ya, Lanjutkan',
        ],
        'info' => [
            'iconColor'   => $isDark ? '#60a5fa'                    : '#2563eb',
            'iconBg'      => $isDark ? 'rgba(59,130,246,0.15)'      : '#eff6ff',
            'iconRing'    => $isDark ? 'rgba(96,165,250,0.2)'       : '#bfdbfe',
            'headerGrad'  => $isDark ? 'linear-gradient(135deg,rgba(59,130,246,0.1),rgba(99,102,241,0.05))'
                                     : 'linear-gradient(135deg,#eff6ff,#eef2ff)',
            'btnBg'       => $isDark ? '#1d4ed8'                    : '#2563eb',
            'btnHover'    => $isDark ? '#1e40af'                    : '#1d4ed8',
            'defaultText' => 'Ya, Lanjutkan',
        ],
        'warning' => [
            'iconColor'   => $isDark ? '#fbbf24'                    : '#d97706',
            'iconBg'      => $isDark ? 'rgba(245,158,11,0.15)'      : '#fffbeb',
            'iconRing'    => $isDark ? 'rgba(251,191,36,0.2)'       : '#fde68a',
            'headerGrad'  => $isDark ? 'linear-gradient(135deg,rgba(245,158,11,0.1),rgba(249,115,22,0.05))'
                                     : 'linear-gradient(135deg,#fffbeb,#fff7ed)',
            'btnBg'       => $isDark ? '#b45309'                    : '#d97706',
            'btnHover'    => $isDark ? '#92400e'                    : '#b45309',
            'defaultText' => 'Ya, Lanjutkan',
        ],
    ];
@endphp

<div
    x-data="{
        show: false,
        title: '', message: '', action: '', method: 'POST',
        type: 'danger', confirmText: 'Ya, Lanjutkan', cancelText: 'Batal',
        size: 'md', loading: false,

        /* Warna fixed dari PHP via Js::from() — satu kali render */
        types: {{ Js::from($typeMap) }},
        sizeMap: { sm: '400px', md: '540px', lg: '720px' },

        c() { return this.types[this.type] || this.types.danger; },

        open(detail) {
            this.title   = detail.title   || 'Konfirmasi';
            this.message = detail.message || '';
            this.action  = detail.action  || '#';
            this.method  = detail.method  || 'POST';
            this.type    = detail.type    || 'danger';
            this.size    = detail.size    || 'md';
            this.loading = false;
            this.confirmText = detail.confirmText || (this.types[this.type] ? this.types[this.type].defaultText : 'Ya, Lanjutkan');
            this.cancelText  = detail.cancelText  || 'Batal';
            this.$nextTick(() => {
                this.show = true;
                setTimeout(() => this.$refs.cancelBtn && this.$refs.cancelBtn.focus(), 120);
            });
        },

        close() { this.show = false; this.loading = false; },
    }"
    x-on:open-confirm-modal.window="open($event.detail)"
    x-on:keydown.escape.window="close()"
    x-show="show"
    x-cloak
    style="display:none;"
    role="dialog"
    aria-modal="true"
    :aria-labelledby="show ? 'confirm-modal-title' : null"
    :aria-describedby="show ? 'confirm-modal-desc' : null"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @click="close()"
        style="position:fixed;inset:0;z-index:9997;background:{{ $styles['backdropBg'] }};backdrop-filter:blur(3px);"
        aria-hidden="true"
    ></div>

    {{-- Scroll wrapper --}}
    <div style="position:fixed;inset:0;z-index:9998;overflow-y:auto;display:flex;align-items:center;justify-content:center;padding:1rem;">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            @click.stop
            :style="'width:100%;max-width:'+(sizeMap[size]||'540px')+';border-radius:1.25rem;overflow:hidden;background:{{ $styles['modalBg'] }};border:1px solid {{ $styles['modalBorder'] }};box-shadow:{{ $styles['modalShadow'] }}'"
        >
            {{-- Header --}}
            <div :style="'background:'+c().headerGrad+';padding:1.5rem 1.5rem 1.25rem'">
                <div style="display:flex;align-items:flex-start;gap:1rem;">

                    {{-- Icon --}}
                    <div
                        :style="'flex-shrink:0;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:'+c().iconBg+';box-shadow:inset 0 0 0 1px '+c().iconRing"
                    >
                        <template x-if="type === 'danger' || type === 'error'">
                            <svg width="20" height="20" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :stroke="c().iconColor" viewBox="0 0 24 24">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                            </svg>
                        </template>
                        <template x-if="type === 'success'">
                            <svg width="20" height="20" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :stroke="c().iconColor" viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </template>
                        <template x-if="type === 'info'">
                            <svg width="20" height="20" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :stroke="c().iconColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        </template>
                        <template x-if="type === 'warning'">
                            <svg width="20" height="20" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :stroke="c().iconColor" viewBox="0 0 24 24">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                        </template>
                    </div>

                    {{-- Title + Message --}}
                    <div style="flex:1;padding-top:2px;">
                        <h3
                            id="confirm-modal-title"
                            style="margin:0;font-size:1rem;font-weight:700;line-height:1.4;letter-spacing:-0.01em;color:{{ $styles['titleColor'] }}"
                            x-text="title"
                        ></h3>
                        <p
                            id="confirm-modal-desc"
                            style="margin:6px 0 0;font-size:0.875rem;line-height:1.6;color:{{ $styles['msgColor'] }}"
                            x-html="message"
                        ></p>
                    </div>

                    {{-- Close X --}}
                    <button
                        @click="close()"
                        type="button"
                        style="flex-shrink:0;padding:6px;border-radius:8px;border:none;background:transparent;cursor:pointer;color:{{ $styles['closeColor'] }};transition:all .15s;"
                        onmouseover="this.style.color='{{ $styles['closeHover'] }}';this.style.background='{{ $styles['closeHoverBg'] }}'"
                        onmouseout="this.style.color='{{ $styles['closeColor'] }}';this.style.background='transparent'"
                        aria-label="Tutup"
                    >
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Footer --}}
            <div style="background:{{ $styles['footerBg'] }};border-top:1px solid {{ $styles['footerBorder'] }};padding:1rem 1.5rem;display:flex;flex-direction:row-reverse;gap:0.625rem;flex-wrap:wrap;align-items:center;">

                {{-- Confirm --}}
                <form :action="action" method="POST" @submit="loading = true" style="display:contents;">
                    @csrf
                    <template x-if="['PUT','PATCH','DELETE'].includes(method.toUpperCase())">
                        <input type="hidden" name="_method" :value="method">
                    </template>
                    <button
                        type="submit"
                        :disabled="loading"
                        :style="'padding:10px 20px;border-radius:10px;font-size:0.875rem;font-weight:600;color:white;border:none;cursor:pointer;transition:all .15s;box-shadow:0 1px 3px rgba(0,0,0,0.15);opacity:'+(loading?'.7':'1')+';background:'+c().btnBg"
                        onmouseover="if(!this.disabled)this.style.filter='brightness(1.08)'"
                        onmouseout="this.style.filter='none'"
                        x-text="loading ? 'Memproses...' : confirmText"
                    ></button>
                </form>

                {{-- Cancel --}}
                <button
                    x-ref="cancelBtn"
                    type="button"
                    @click="close()"
                    :disabled="loading"
                    style="padding:10px 20px;border-radius:10px;font-size:0.875rem;font-weight:600;border:none;cursor:pointer;transition:all .15s;color:{{ $styles['cancelColor'] }};background:{{ $styles['cancelBg'] }};box-shadow:inset 0 0 0 1px {{ $styles['cancelBorder'] }}"
                    onmouseover="if(!this.disabled)this.style.background='{{ $styles['cancelHover'] }}'"
                    onmouseout="this.style.background='{{ $styles['cancelBg'] }}'"
                    x-text="cancelText"
                ></button>
            </div>
        </div>
    </div>
</div>
