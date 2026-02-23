<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-700 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-indigo-100 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-indigo-100']) }}>
    {{ $slot }}
</button>
