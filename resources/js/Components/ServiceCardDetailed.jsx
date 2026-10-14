export default function ServiceCardDetailed({ service }) {
    const imageUrl = service.image
        ? `/storage/${service.image}`
        : '/images/logo.png';

    return (
        <div
            id={service.slug}
            className="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group scroll-mt-24"
        >
            <div className="h-56 bg-gray-100 overflow-hidden">
                <img
                    src={imageUrl}
                    alt={service.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
            </div>
            <div className="p-6">
                <h3 className="text-xl font-bold text-gray-900 mb-3">
                    {service.name}
                </h3>
                <p className="text-gray-600 leading-relaxed">
                    {service.description || 'Servicio disponible en la clínica.'}
                </p>
                <a
                    href="/citas"
                    className="mt-5 inline-flex items-center text-sm font-semibold text-primary-600 hover:text-primary-700"
                >
                    Solicitar información
                    <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    );
}