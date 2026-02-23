<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-red-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-100 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-red-100']) }}>
    {{ $slot }}
</button>
