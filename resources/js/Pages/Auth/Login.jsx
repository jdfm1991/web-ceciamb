import { Head, Link, useForm, usePage } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function Login() {
    const { flash } = usePage().props;

    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/login');
    };

    const inputClass = (field) =>
        `w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all ${
            errors[field] ? 'border-red-500 bg-red-50' : 'border-gray-300'
        }`;

    return (
        <PublicLayout>
            <Head title="Iniciar sesión" />

            <section className="py-12 md:py-16 bg-gray-50">
                <div className="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white rounded-2xl shadow-lg p-8">

                        <div className="text-center mb-8">
                            <h1 className="text-2xl font-bold text-gray-900">Iniciar sesión</h1>
                            <p className="text-gray-600 mt-2 text-sm">
                                Accede a tu cuenta para gestionar tus citas.
                            </p>
                        </div>

                        {flash?.success && (
                            <div className="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                                {flash.success}
                            </div>
                        )}

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
                                    className={inputClass('email')}
                                    placeholder="tu@email.com"
                                />
                                {errors.email && <p className="mt-1 text-sm text-red-600">{errors.email}</p>}
                            </div>

                            <div>
                                <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Contraseña *
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    className={inputClass('password')}
                                    placeholder="Tu contraseña"
                                />
                                {errors.password && <p className="mt-1 text-sm text-red-600">{errors.password}</p>}
                            </div>

                            <div className="flex items-center">
                                <input
                                    id="remember"
                                    type="checkbox"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                                />
                                <label htmlFor="remember" className="ml-2 text-sm text-gray-600">
                                    Recordarme
                                </label>
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Iniciando sesión...' : 'Iniciar sesión'}
                            </button>
                        </form>

                        <div className="mt-6 pt-6 border-t border-gray-100 space-y-2 text-center text-sm">
                            <p className="text-gray-600">
                                ¿No tienes cuenta?{' '}
                                <Link href="/registro" className="text-primary-600 hover:text-primary-700 font-semibold">
                                    Regístrate
                                </Link>
                            </p>
                            <p>
                                <Link href="/reenviar-activacion" className="text-gray-500 hover:text-primary-600">
                                    Reenviar correo de activación
                                </Link>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}