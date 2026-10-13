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

export default function SpecialtyCard({ specialty }) {
    const colorClass = CATEGORY_COLORS[specialty.category] || 'bg-gray-50 text-gray-700 border-gray-200';
    const label = CATEGORY_LABELS[specialty.category] || specialty.category;

    return (
        <Link
            href={`/medicos?especialidad=${specialty.slug}`}
            className="block bg-white rounded-lg border border-gray-200 hover:border-primary-400 hover:shadow-md transition-all duration-200 p-5 group"
        >
            <div className="flex items-start justify-between">
                <h3 className="text-base font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                    {specialty.name}
                </h3>
                <svg className="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                </svg>
            </div>
            <span className={`inline-block mt-3 text-xs font-medium px-2 py-1 rounded border ${colorClass}`}>
                {label}
            </span>
        </Link>
    );
}