import { Head, useForm, usePage } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function ProfileEdit({ user }) {
    const { flash } = usePage().props;

    const profileForm = useForm({
        name: user.name,
        phone: user.phone || '',
    });

    const passwordForm = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const handleProfileSubmit = (e) => {
        e.preventDefault();
        profileForm.post('/mi-cuenta/perfil');
    };

    const handlePasswordSubmit = (e) => {
        e.preventDefault();
        passwordForm.post('/mi-cuenta/perfil/password', {
            onSuccess: () => passwordForm.reset(),
        });
    };

    const inputClass = (errors, field) =>
        `w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all ${
            errors[field] ? 'border-red-500 bg-red-50' : 'border-gray-300'
        }`;

    return (
        <PublicLayout>
            <Head title="Mi perfil" />

            <div className="mb-8">
                <h1 className="text-3xl font-bold text-gray-900">Mi perfil</h1>
                <p className="text-gray-600 mt-1">Actualiza tus datos personales y contraseña.</p>
            </div>

            {flash?.success && (
                <div className="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {flash.success}
                </div>
            )}

            {/* Datos personales */}
            <div className="bg-white rounded-xl shadow-sm p-6 md:p-8 mb-6">
                <h2 className="text-lg font-bold text-gray-900 mb-4">Datos personales</h2>

                <form onSubmit={handleProfileSubmit} className="space-y-5">
                    <div>
                        <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1.5">
                            Correo electrónico
                        </label>
                        <input
                            id="email"
                            type="email"
                            value={user.email}
                            disabled
                            className="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                        />
                        <p className="mt-1 text-xs text-gray-500">El correo no se puede cambiar.</p>
                    </div>

                    <div>
                        <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-1.5">
                            Nombre completo *
                        </label>
                        <input
                            id="name"
                            type="text"
                            value={profileForm.data.name}
                            onChange={(e) => profileForm.setData('name', e.target.value)}
                            className={inputClass(profileForm.errors, 'name')}
                        />
                        {profileForm.errors.name && <p className="mt-1 text-sm text-red-600">{profileForm.errors.name}</p>}
                    </div>

                    <div>
                        <label htmlFor="phone" className="block text-sm font-medium text-gray-700 mb-1.5">
                            Teléfono
                        </label>
                        <input
                            id="phone"
                            type="tel"
                            value={profileForm.data.phone}
                            onChange={(e) => profileForm.setData('phone', e.target.value)}
                            className={inputClass(profileForm.errors, 'phone')}
                            placeholder="0414-1234567"
                        />
                        {profileForm.errors.phone && <p className="mt-1 text-sm text-red-600">{profileForm.errors.phone}</p>}
                    </div>

                    <div className="flex justify-end">
                        <button
                            type="submit"
                            disabled={profileForm.processing}
                            className="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50"
                        >
                            {profileForm.processing ? 'Guardando...' : 'Guardar cambios'}
                        </button>
                    </div>
                </form>
            </div>

            {/* Cambiar contraseña */}
            <div className="bg-white rounded-xl shadow-sm p-6 md:p-8">
                <h2 className="text-lg font-bold text-gray-900 mb-4">Cambiar contraseña</h2>

                <form onSubmit={handlePasswordSubmit} className="space-y-5">
                    <div>
                        <label htmlFor="current_password" className="block text-sm font-medium text-gray-700 mb-1.5">
                            Contraseña actual *
                        </label>
                        <input
                            id="current_password"
                            type="password"
                            value={passwordForm.data.current_password}
                            onChange={(e) => passwordForm.setData('current_password', e.target.value)}
                            className={inputClass(passwordForm.errors, 'current_password')}
                        />
                        {passwordForm.errors.current_password && <p className="mt-1 text-sm text-red-600">{passwordForm.errors.current_password}</p>}
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-1.5">
                                Nueva contraseña *
                            </label>
                            <input
                                id="password"
                                type="password"
                                value={passwordForm.data.password}
                                onChange={(e) => passwordForm.setData('password', e.target.value)}
                                className={inputClass(passwordForm.errors, 'password')}
                                placeholder="Mínimo 8 caracteres"
                            />
                            {passwordForm.errors.password && <p className="mt-1 text-sm text-red-600">{passwordForm.errors.password}</p>}
                        </div>

                        <div>
                            <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700 mb-1.5">
                                Confirmar nueva contraseña *
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                value={passwordForm.data.password_confirmation}
                                onChange={(e) => passwordForm.setData('password_confirmation', e.target.value)}
                                className={inputClass(passwordForm.errors, 'password_confirmation')}
                            />
                        </div>
                    </div>

                    <div className="flex justify-end">
                        <button
                            type="submit"
                            disabled={passwordForm.processing}
                            className="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50"
                        >
                            {passwordForm.processing ? 'Cambiando...' : 'Cambiar contraseña'}
                        </button>
                    </div>
                </form>
            </div>
        </PublicLayout>
    );
}