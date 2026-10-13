import { Link } from '@inertiajs/react';

export default function ServiceCard({ service }) {
    const imageUrl = service.image
        ? `/storage/${service.image}`
        : '/images/logo.png';

    return (
        <div className="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group">
            <div className="h-40 bg-gray-100 overflow-hidden">
                <img
                    src={imageUrl}
                    alt={service.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
            </div>
            <div className="p-6">
                <h3 className="text-lg font-bold text-gray-900 mb-2">
                    {service.name}
                </h3>
                <p className="text-sm text-gray-600 line-clamp-3">
                    {service.description || 'Servicio disponible en la clínica.'}
                </p>
                <Link
                    href={`/servicios#${service.slug}`}
                    className="mt-4 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700"
                >
                    Más información
                    <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>
        </div>
    );
}