import { Head, Link } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import PublicLayout from '@/Layouts/PublicLayout';
import DoctorCardDirectory from '@/Components/DoctorCardDirectory';

export default function DoctorsIndex({ doctors, activeSpecialty }) {
    const [searchTerm, setSearchTerm] = useState('');

    const filteredDoctors = useMemo(() => {
        return doctors.filter((doctor) => {
            const term = searchTerm.toLowerCase().trim();
            if (!term) return true;

            const matchesName = doctor.name.toLowerCase().includes(term);
            const matchesBiography = doctor.biography?.toLowerCase().includes(term);
            const matchesSpecialty = doctor.specialties?.some((s) =>
                s.name.toLowerCase().includes(term)
            );

            return matchesName || matchesBiography || matchesSpecialty;
        });
    }, [doctors, searchTerm]);

    return (
        <PublicLayout>
            <Head title={activeSpecialty ? `Médicos - ${activeSpecialty.name}` : 'Directorio Médico'} />

            {/* Header */}
            <section className="bg-gradient-to-br from-primary-700 to-primary-500 text-white py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-4xl md:text-5xl font-bold">
                        {activeSpecialty ? `Médicos de ${activeSpecialty.name}` : 'Directorio Médico'}
                    </h1>
                    <p className="mt-4 text-lg text-primary-100 max-w-2xl">
                        {activeSpecialty
                            ? `Profesionales especializados en ${activeSpecialty.name}.`
                            : 'Conoce a nuestro equipo de profesionales de la salud.'}
                    </p>
                </div>
            </section>

            {/* Contenido */}
            <section className="py-12 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Chip de filtro activo */}
                    {activeSpecialty && (
                        <div className="mb-6 flex items-center gap-3">
                            <span className="text-sm text-gray-600">Filtrando por:</span>
                            <div className="inline-flex items-center bg-primary-100 text-primary-700 px-3 py-1.5 rounded-full text-sm font-medium">
                                {activeSpecialty.name}
                                <Link
                                    href="/medicos"
                                    className="ml-2 hover:text-primary-900"
                                    aria-label="Quitar filtro"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    )}

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
                                placeholder="Buscar por nombre, especialidad o biografía..."
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
                            Mostrando <span className="font-semibold text-primary-600">{filteredDoctors.length}</span> de {doctors.length} médicos
                        </div>
                    </div>

                    {/* Grid */}
                    {filteredDoctors.length > 0 ? (
                        <div className="space-y-4">
                            {filteredDoctors.map((doctor) => (
                                <DoctorCardDirectory key={doctor.id} doctor={doctor} />
                            ))}
                        </div>
                    ) : (
                        <div className="bg-white rounded-xl p-12 text-center shadow-sm">
                            <svg className="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                No se encontraron médicos
                            </h3>
                            <p className="text-gray-600 mb-4">
                                {activeSpecialty
                                    ? `No hay médicos disponibles en ${activeSpecialty.name} por el momento.`
                                    : 'Intenta con otro término de búsqueda.'}
                            </p>
                            {activeSpecialty && (
                                <Link
                                    href="/medicos"
                                    className="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium"
                                >
                                    Ver todos los médicos
                                    <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            )}
                        </div>
                    )}
                </div>
            </section>
        </PublicLayout>
    );
}