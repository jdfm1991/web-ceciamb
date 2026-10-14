export default function SpecialtyFilters({ categories, activeCategory, onCategoryChange, searchTerm, onSearchChange, totalResults, totalSpecialties }) {
    const categoryOptions = [
        { value: 'all', label: 'Todas' },
        ...Object.entries(categories).map(([key, label]) => ({
            value: key,
            label: label,
        })),
    ];

    return (
        <div className="bg-white rounded-xl shadow-sm p-6 mb-8">
            {/* Barra de búsqueda */}
            <div className="relative mb-6">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    value={searchTerm}
                    onChange={(e) => onSearchChange(e.target.value)}
                    placeholder="Buscar especialidad por nombre..."
                    className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
                />
                {searchTerm && (
                    <button
                        onClick={() => onSearchChange('')}
                        className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                        aria-label="Limpiar búsqueda"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                )}
            </div>

            {/* Filtros por categoría */}
            <div className="flex flex-wrap gap-2">
                {categoryOptions.map((option) => (
                    <button
                        key={option.value}
                        onClick={() => onCategoryChange(option.value)}
                        className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                            activeCategory === option.value
                                ? 'bg-primary-600 text-white shadow-sm'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        }`}
                    >
                        {option.label}
                    </button>
                ))}
            </div>

            {/* Contador de resultados */}
            <div className="mt-4 text-sm text-gray-600">
                Mostrando <span className="font-semibold text-primary-600">{totalResults}</span> de {totalSpecialties} especialidades
            </div>
        </div>
    );
}