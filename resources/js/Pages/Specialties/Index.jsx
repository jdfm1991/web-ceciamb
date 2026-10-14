import { Head } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import PublicLayout from '@/Layouts/PublicLayout';
import SpecialtyFilters from '@/Components/SpecialtyFilters';
import SpecialtyCardDetailed from '@/Components/SpecialtyCardDetailed';

export default function SpecialtiesIndex({ specialties, categories }) {
    const [searchTerm, setSearchTerm] = useState('');
    const [activeCategory, setActiveCategory] = useState('all');

    const filteredSpecialties = useMemo(() => {
        return specialties.filter((specialty) => {
            const matchesSearch = specialty.name
                .toLowerCase()
                .includes(searchTerm.toLowerCase().trim());

            const matchesCategory =
                activeCategory === 'all' || specialty.category === activeCategory;

            return matchesSearch && matchesCategory;
        });
    }, [specialties, searchTerm, activeCategory]);

    return (
        <PublicLayout>
            <Head>
                <title>Especialidades</title>
                <meta name="description" content="Hospital de Clínicas de Ceciamb - Atención médica de calidad con sensibilidad humana. Agenda tu cita con nuestros especialistas." />
            </Head>

            {/* Header de la página */}
            <section className="bg-gradient-to-br from-primary-700 to-primary-500 text-white py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-4xl md:text-5xl font-bold">Especialidades</h1>
                    <p className="mt-4 text-lg text-primary-100 max-w-2xl">
                        Contamos con más de 50 especialidades y subespecialidades para atender todas tus necesidades de salud.
                    </p>
                </div>
            </section>

            {/* Contenido */}
            <section className="py-12 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Filtros */}
                    <SpecialtyFilters
                        categories={categories}
                        activeCategory={activeCategory}
                        onCategoryChange={setActiveCategory}
                        searchTerm={searchTerm}
                        onSearchChange={setSearchTerm}
                        totalResults={filteredSpecialties.length}
                        totalSpecialties={specialties.length}
                    />

                    {/* Grid */}
                    {filteredSpecialties.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            {filteredSpecialties.map((specialty) => (
                                <SpecialtyCardDetailed key={specialty.id} specialty={specialty} />
                            ))}
                        </div>
                    ) : (
                        <div className="bg-white rounded-xl p-12 text-center shadow-sm">
                            <svg className="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                No se encontraron especialidades
                            </h3>
                            <p className="text-gray-600 mb-4">
                                Intenta con otro término de búsqueda o cambia la categoría.
                            </p>
                            <button
                                onClick={() => {
                                    setSearchTerm('');
                                    setActiveCategory('all');
                                }}
                                className="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium"
                            >
                                Limpiar filtros
                                <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    )}
                </div>
            </section>
        </PublicLayout>
    );
}