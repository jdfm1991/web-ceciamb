import { Head, Link, useForm, usePage } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function ResendActivation({ email: initialEmail }) {
    const { flash } = usePage().props;

    const { data, setData, post, processing, errors } = useForm({
        email: initialEmail || '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/reenviar-activacion');
    };

    return (
        <PublicLayout>
            <Head title="Reenviar activación" />

            <section className="py-12 md:py-16 bg-gray-50">
                <div className="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white rounded-2xl shadow-lg p-8">

                        <div className="text-center mb-8">
                            <h1 className="text-2xl font-bold text-gray-900">Reenviar activación</h1>
                            <p className="text-gray-600 mt-2 text-sm">
                                Ingresa tu correo y te enviaremos un nuevo enlace de activación.
                            </p>
                        </div>

                        {flash?.error && (
                            <div className="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                                {flash.error}
                            </div>
                        )}

                        <form onSubmit={handleSubmit} className="space-y-5">
                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Correo electrónico *
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all ${
                                        errors.email ? 'border-red-500 bg-red-50' : 'border-gray-300'
                                    }`}
                                    placeholder="tu@email.com"
                                />
                                {errors.email && <p className="mt-1 text-sm text-red-600">{errors.email}</p>}
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Enviando...' : 'Reenviar correo'}
                            </button>
                        </form>

                        <div className="mt-6 pt-6 border-t border-gray-100 text-center text-sm">
                            <Link href="/login" className="text-primary-600 hover:text-primary-700 font-semibold">
                                Volver al login
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}