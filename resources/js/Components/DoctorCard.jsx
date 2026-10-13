import { Link } from '@inertiajs/react';

export default function DoctorCard({ doctor }) {
    const photoUrl = doctor.photo
        ? `/storage/${doctor.photo}`
        : '/images/logo.png';

    return (
        <Link
            href={`/medicos/${doctor.slug}`}
            className="block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group"
        >
            <div className="aspect-square overflow-hidden bg-gray-100">
                <img
                    src={photoUrl}
                    alt={doctor.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
            </div>
            <div className="p-5">
                <h3 className="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors">
                    {doctor.name}
                </h3>
                <div className="flex flex-wrap gap-1 mt-2">
                    {doctor.specialties?.slice(0, 2).map((specialty) => (
                        <span
                            key={specialty.id}
                            className="text-xs bg-primary-50 text-primary-700 px-2 py-1 rounded"
                        >
                            {specialty.name}
                        </span>
                    ))}
                </div>
                {doctor.biography && (
                    <p className="text-sm text-gray-600 mt-3 line-clamp-2">
                        {doctor.biography}
                    </p>
                )}
            </div>
        </Link>
    );
}