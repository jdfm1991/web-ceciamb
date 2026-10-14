import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import ScheduleTable from '@/Components/ScheduleTable';

const CATEGORY_COLORS = {
    medica: 'bg-primary-50 text-primary-700 border-primary-200',
    subespecialidad: 'bg-blue-50 text-blue-700 border-blue-200',
    profesion_salud: 'bg-green-50 text-green-700 border-green-200',
    tecnica: 'bg-amber-50 text-amber-700 border-amber-200',
};

const CATEGORY_LABELS = {
    medica: 'Médica',
    subespecialidad: 'Subespecialidad',
    profesion_salud: 'Profesión de la Salud',
    tecnica: 'Técnica',
};

export default function DoctorShow({ doctor, schedulesByDay, dayNames }) {
    const photoUrl = doctor.photo
        ? `/storage/${doctor.photo}`
        : '/images/logo.png';

    return (
        <PublicLayout>
            <Head title={doctor.name} />

            {/* Breadcrumb */}
            <div className="bg-gray-50 border-b border-gray-200">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <nav className="flex items-center text-sm text-gray-600 space-x-2">
                        <Link href="/" className="hover:text-primary-600">Inicio</Link>
                        <span>/</span>
                        <Link href="/medicos" className="hover:text-primary-600">Médicos</Link>
                        <span>/</span>
                        <span className="text-gray-900 font-medium">{doctor.name}</span>
                    </nav>
                </div>
            </div>

            {/* Header del perfil */}
            <section className="bg-gradient-to-br from-primary-700 to-primary-500 text-white py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col md:flex-row items-center md:items-start gap-8">
                        {/* Foto */}
                        <div className="w-40 h-40 md:w-48 md:h-48 rounded-full overflow-hidden bg-white shadow-2xl shrink-0 border-4 border-white">
                            <img
                                src={photoUrl}
                                alt={doctor.name}
                                className="w-full h-full object-cover"
                            />
                        </div>

                        {/* Info */}
                        <div className="flex-1 text-center md:text-left">
                            <h1 className="text-3xl md:text-4xl font-bold">
                                {doctor.name}
                            </h1>

                            <div className="flex flex-wrap gap-2 mt-4 justify-center md:justify-start">
                                {doctor.specialties?.map((specialty) => (
                                    <span
                                        key={specialty.id}
                                        className="inline-block bg-white/20 backdrop-blur text-white text-sm px-3 py-1.5 rounded-full border border-white/30"
                                    >
                                        {specialty.name}
                                    </span>
                                ))}
                            </div>

                            <div className="mt-6 flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                                <Link
                                    href={`/citas?doctor=${doctor.slug}`}
                                    className="inline-flex items-center justify-center bg-white text-primary-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg"
                                >
                                    <svg className="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Solicitar cita
                                </Link>
                                <Link
                                    href="/medicos"
                                    className="inline-flex items-center justify-center border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary-700 transition-colors"
                                >
                                    <svg className="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Volver al directorio
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Contenido */}
            <section className="py-12 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Biografía */}
                        <div className="lg:col-span-2">
                            <div className="bg-white rounded-xl shadow-sm p-8">
                                <h2 className="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                    <svg className="w-6 h-6 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Sobre el médico
                                </h2>

                                {doctor.biography ? (
                                    <p className="text-gray-700 leading-relaxed whitespace-pre-line">
                                        {doctor.biography}
                                    </p>
                                ) : (
                                    <p className="text-gray-500 italic">
                                        Este médico aún no ha publicado su biografía.
                                    </p>
                                )}

                                {/* Especialidades detalladas */}
                                {doctor.specialties && doctor.specialties.length > 0 && (
                                    <div className="mt-8 pt-8 border-t border-gray-100">
                                        <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                            Especialidades
                                        </h3>
                                        <div className="space-y-2">
                                            {doctor.specialties.map((specialty) => {
                                                const colorClass = CATEGORY_COLORS[specialty.category] || 'bg-gray-50 text-gray-700 border-gray-200';
                                                const label = CATEGORY_LABELS[specialty.category] || specialty.category;

                                                return (
                                                    <div
                                                        key={specialty.id}
                                                        className="flex items-center justify-between py-2 px-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors"
                                                    >
                                                        <span className="font-medium text-gray-900">
                                                            {specialty.name}
                                                        </span>
                                                        <span className={`text-xs font-medium px-2.5 py-1 rounded-full border ${colorClass}`}>
                                                            {label}
                                                        </span>
                                                    </div>
                                                );
                                            })}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Horarios */}
                        <div className="lg:col-span-1">
                            <div className="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                                <h2 className="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <svg className="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Horarios de atención
                                </h2>

                                <ScheduleTable
                                    schedulesByDay={schedulesByDay}
                                    dayNames={dayNames}
                                />
                            </div>
                        </div>
                    </div>

                    {/* CTA final */}
                    <div className="mt-12 bg-gradient-to-r from-primary-600 to-primary-500 rounded-xl p-8 text-center text-white">
                        <h3 className="text-2xl font-bold mb-2">
                            ¿Listo para agendar tu cita?
                        </h3>
                        <p className="text-primary-100 mb-6">
                            Solicita una cita con {doctor.name} y recibe atención especializada.
                        </p>
                        <Link
                            href={`/citas?doctor=${doctor.slug}`}
                            className="inline-flex items-center bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg"
                        >
                            <svg className="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Solicitar cita ahora
                        </Link>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}