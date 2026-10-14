import { Link } from '@inertiajs/react';

export default function DoctorCardDirectory({ doctor }) {
    const photoUrl = doctor.photo
        ? `/storage/${doctor.photo}`
        : '/images/logo.png';

    return (
        <Link
            href={`/medicos/${doctor.slug}`}
            className="block bg-white rounded-xl border border-gray-200 hover:border-primary-400 hover:shadow-lg transition-all duration-200 overflow-hidden group"
        >
            <div className="flex flex-col sm:flex-row">
                {/* Foto */}
                <div className="sm:w-48 sm:shrink-0 aspect-square sm:aspect-auto bg-gray-100">
                    <img
                        src={photoUrl}
                        alt={doctor.name}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                </div>

                {/* Info */}
                <div className="flex-1 p-6">
                    <h3 className="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors">
                        {doctor.name}
                    </h3>

                    <div className="flex flex-wrap gap-1.5 mt-2">
                        {doctor.specialties?.map((specialty) => (
                            <span
                                key={specialty.id}
                                className="text-xs bg-primary-50 text-primary-700 px-2 py-1 rounded-full"
                            >
                                {specialty.name}
                            </span>
                        ))}
                    </div>

                    {doctor.biography && (
                        <p className="text-sm text-gray-600 mt-3 line-clamp-3">
                            {doctor.biography}
                        </p>
                    )}

                    <div className="mt-4 flex items-center text-sm font-medium text-primary-600 group-hover:text-primary-700">
                        Ver perfil completo
                        <svg className="ml-1 w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </div>
        </Link>
    );
}