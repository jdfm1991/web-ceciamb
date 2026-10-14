import { Link } from '@inertiajs/react';

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

export default function SpecialtyCardDetailed({ specialty }) {
    const colorClass = CATEGORY_COLORS[specialty.category] || 'bg-gray-50 text-gray-700 border-gray-200';
    const label = CATEGORY_LABELS[specialty.category] || specialty.category;
    const doctorsCount = specialty.doctors_count || 0;

    return (
        <Link
            href={`/medicos?especialidad=${specialty.slug}`}
            className="block bg-white rounded-xl border border-gray-200 hover:border-primary-400 hover:shadow-lg transition-all duration-200 p-6 group"
        >
            <div className="flex items-start justify-between mb-3">
                <span className={`inline-block text-xs font-medium px-2.5 py-1 rounded-full border ${colorClass}`}>
                    {label}
                </span>
                <svg className="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                </svg>
            </div>

            <h3 className="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-2">
                {specialty.name}
            </h3>

            {specialty.description && (
                <p className="text-sm text-gray-600 line-clamp-2 mb-3">
                    {specialty.description}
                </p>
            )}

            <div className="flex items-center text-sm text-gray-500">
                <svg className="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {doctorsCount === 0
                    ? 'Sin médicos asignados'
                    : doctorsCount === 1
                    ? '1 médico disponible'
                    : `${doctorsCount} médicos disponibles`}
            </div>
        </Link>
    );
}