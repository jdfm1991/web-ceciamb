import { Head, Link, usePage } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function ActivationPending({ email }) {
    const { flash } = usePage().props;

    return (
        <PublicLayout>
            <Head title="Revisa tu correo" />

            <section className="py-16 md:py-24 bg-gray-50">
                <div className="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white rounded-2xl shadow-lg p-8 text-center">

                        <div className="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center mx-auto mb-6">
                            <svg className="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <h1 className="text-2xl font-bold text-gray-900 mb-3">
                            Revisa tu correo
                        </h1>
                        <p className="text-gray-600 mb-6">
                            Te enviamos un correo a <strong className="text-gray-900">{email}</strong> con un enlace para activar tu cuenta.
                        </p>

                        {flash?.success && (
                            <div className="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm mb-6">
                                {flash.success}
                            </div>
                        )}

                        <div className="bg-amber-50 border border-amber-200 rounded-xl p-4 text-left mb-6">
                            <p className="text-sm text-amber-800">
                                <strong>Nota:</strong> El enlace expira en 48 horas. Si no lo recibes, revisa tu carpeta de spam o solicita un nuevo enlace.
                            </p>
                        </div>

                        <div className="space-y-3">
                            <Link
                                href={`/reenviar-activacion?email=${encodeURIComponent(email || '')}`}
                                className="block w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold transition-colors text-center"
                            >
                                Reenviar correo
                            </Link>
                            <Link
                                href="/login"
                                className="block w-full border-2 border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors text-center"
                            >
                                Ir al login
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}