import { Head, Link } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import PublicLayout from '@/Layouts/PublicLayout';
import ServiceCardDetailed from '@/Components/ServiceCardDetailed';

export default function ServicesIndex({ services }) {
    const [searchTerm, setSearchTerm] = useState('');

    const filteredServices = useMemo(() => {
        const term = searchTerm.toLowerCase().trim();
        if (!term) return services;

        return services.filter((service) => {
            const matchesName = service.name.toLowerCase().includes(term);
            const matchesDescription = service.description?.toLowerCase().includes(term);
            return matchesName || matchesDescription;
        });
    }, [services, searchTerm]);

    return (
        <PublicLayout>
            <Head title="Servicios" />

            {/* Header */}
            <section className="bg-gradient-to-br from-primary-700 to-primary-500 text-white py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-4xl md:text-5xl font-bold">Nuestros Servicios</h1>
                    <p className="mt-4 text-lg text-primary-100 max-w-2xl">
                        Contamos con una amplia gama de servicios clínicos y de diagnóstico para cuidar tu salud de forma integral.
                    </p>
                </div>
            </section>

            {/* Contenido */}
            <section className="py-12 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Barra de búsqueda */}
                    <div className="bg-white rounded-xl shadow-sm p-6 mb-8">
                        <div className="relative">
                            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                placeholder="Buscar servicio por nombre o descripción..."
                                className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
                            />
                            {searchTerm && (
                                <button
                                    onClick={() => setSearchTerm('')}
                                    className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    aria-label="Limpiar búsqueda"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            )}
                        </div>
                        <div className="mt-4 text-sm text-gray-600">
                            Mostrando <span className="font-semibold text-primary-600">{filteredServices.length}</span> de {services.length} servicios
                        </div>
                    </div>

                    {/* Grid */}
                    {filteredServices.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {filteredServices.map((service) => (
                                <ServiceCardDetailed key={service.id} service={service} />
                            ))}
                        </div>
                    ) : (
                        <div className="bg-white rounded-xl p-12 text-center shadow-sm">
                            <svg className="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                No se encontraron servicios
                            </h3>
                            <p className="text-gray-600 mb-4">
                                Intenta con otro término de búsqueda.
                            </p>
                            <button
                                onClick={() => setSearchTerm('')}
                                className="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium"
                            >
                                Limpiar búsqueda
                                <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    )}

                    {/* CTA final */}
                    <div className="mt-16 bg-gradient-to-r from-primary-600 to-primary-500 rounded-xl p-8 md:p-12 text-center text-white">
                        <h3 className="text-2xl md:text-3xl font-bold mb-3">
                            ¿Necesitas más información?
                        </h3>
                        <p className="text-primary-100 mb-6 max-w-2xl mx-auto">
                            Contáctanos para conocer más sobre nuestros servicios o para agendar una cita con nuestros especialistas.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-3 justify-center">
                            <Link
                                href="/contacto"
                                className="inline-flex items-center justify-center bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg"
                            >
                                Contactar
                            </Link>
                            <Link
                                href="/citas"
                                className="inline-flex items-center justify-center border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary-700 transition-colors"
                            >
                                Solicitar cita
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}