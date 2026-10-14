import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function NotFound() {
    return (
        <PublicLayout>
            <Head title="Página no encontrada" />

            <section className="py-24 bg-gray-50">
                <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div className="text-9xl font-bold text-primary-200 mb-4">404</div>
                    <h1 className="text-3xl font-bold text-gray-900 mb-4">
                        Página no encontrada
                    </h1>
                    <p className="text-gray-600 mb-8 text-lg">
                        Lo sentimos, la página que buscas no existe o fue movida.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-3 justify-center">
                        <Link
                            href="/"
                            className="inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors"
                        >
                            Ir al inicio
                        </Link>
                        <Link
                            href="/contacto"
                            className="inline-flex items-center justify-center border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-white transition-colors"
                        >
                            Contactar soporte
                        </Link>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}