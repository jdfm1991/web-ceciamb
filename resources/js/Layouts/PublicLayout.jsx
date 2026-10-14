import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function PublicLayout({ children }) {
    const { clinicInfo, auth } = usePage().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    const currentPath = window.location.pathname;
    const isPatientArea = currentPath.startsWith('/mi-cuenta');

    const navigation = [
        { name: 'Inicio', href: '/' },
        { name: 'Especialidades', href: '/especialidades' },
        { name: 'Médicos', href: '/medicos' },
        { name: 'Servicios', href: '/servicios' },
        { name: 'Contacto', href: '/contacto' },
    ];

    const isActive = (href) => {
        const currentPath = window.location.pathname;
        return href === '/' ? currentPath === '/' : currentPath.startsWith(href);
    };

    return (
        <div className="min-h-screen flex flex-col">
            {/* Header */}
            <header className="bg-white shadow-sm sticky top-0 z-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-20">
                        {/* Logo */}
                        <Link href="/" className="flex items-center space-x-3">
                            {clinicInfo?.logo && (
                                <img
                                    src={`/storage/${clinicInfo.logo}`}
                                    alt={clinicInfo.name}
                                    className="h-12 w-auto"
                                />
                            )}
                            <span className="text-xl font-bold text-primary-700 hidden sm:block">
                                {clinicInfo?.name?.split(',')[0] || 'Ceciamb'}
                            </span>
                        </Link>

                        {/* Navegación desktop */}
                        <nav className="hidden md:flex items-center space-x-8">
                            {navigation.map((item) => (
                                <Link
                                    key={item.name}
                                    href={item.href}
                                    className={`text-sm font-medium transition-colors ${isActive(item.href)
                                        ? 'text-primary-600 border-b-2 border-primary-600'
                                        : 'text-gray-700 hover:text-primary-600'
                                        }`}
                                >
                                    {item.name}
                                </Link>
                            ))}
                            <Link
                                href="/citas"
                                className="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-lg font-medium text-sm transition-colors shadow-sm"
                            >
                                Solicitar Cita
                            </Link>
                            {auth?.user ? (
                                <div className="relative group">
                                    <button className="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-primary-600">
                                        <div className="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-xs">
                                            {auth.user.name.charAt(0).toUpperCase()}
                                        </div>
                                        <span>{auth.user.name.split(' ')[0]}</span>
                                    </button>
                                    <div className="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                        <Link href="/mi-cuenta" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                            Mi cuenta
                                        </Link>
                                        <Link href="/logout" method="post" as="button" className="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-lg">
                                            Cerrar sesión
                                        </Link>
                                    </div>
                                </div>
                            ) : (
                                <Link href="/login" className="text-sm font-medium text-gray-700 hover:text-primary-600">
                                    Iniciar sesión
                                </Link>
                            )}
                        </nav>

                        {/* Botón menú móvil */}
                        <button
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="md:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100"
                            aria-label="Abrir menú"
                        >
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {mobileMenuOpen ? (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                ) : (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                                )}
                            </svg>
                        </button>
                    </div>

                    {/* Navegación móvil */}
                    {mobileMenuOpen && (
                        <nav className="md:hidden pb-4 border-t border-gray-200 pt-4">
                            {navigation.map((item) => (
                                <Link
                                    key={item.name}
                                    href={item.href}
                                    className={`block py-2 px-3 rounded-lg text-base font-medium ${isActive(item.href)
                                        ? 'bg-primary-50 text-primary-700'
                                        : 'text-gray-700 hover:bg-gray-50'
                                        }`}
                                    onClick={() => setMobileMenuOpen(false)}
                                >
                                    {item.name}
                                </Link>
                            ))}
                            <Link
                                href="/citas"
                                className="block mt-3 bg-primary-600 hover:bg-primary-700 text-white text-center px-6 py-3 rounded-lg font-medium"
                                onClick={() => setMobileMenuOpen(false)}
                            >
                                Solicitar Cita
                            </Link>
                            {auth?.user ? (
                                <>
                                    <Link href="/mi-cuenta" className="block py-2 px-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50" onClick={() => setMobileMenuOpen(false)}>
                                        Mi cuenta
                                    </Link>
                                    <Link href="/logout" method="post" as="button" className="block w-full text-left py-2 px-3 rounded-lg text-base font-medium text-red-600 hover:bg-red-50" onClick={() => setMobileMenuOpen(false)}>
                                        Cerrar sesión
                                    </Link>
                                </>
                            ) : (
                                <Link href="/login" className="block py-2 px-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50" onClick={() => setMobileMenuOpen(false)}>
                                    Iniciar sesión
                                </Link>
                            )}
                        </nav>
                    )}
                </div>
            </header>

            {/* Contenido */}
            <main className="flex-1">
                {isPatientArea ? (
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
                            {/* Sidebar */}
                            <aside className="lg:col-span-1">
                                <div className="bg-white rounded-xl shadow-sm p-4 sticky top-24">
                                    <nav className="space-y-1">
                                        <Link
                                            href="/mi-cuenta"
                                            className={`flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-colors ${currentPath === '/mi-cuenta'
                                                    ? 'bg-primary-50 text-primary-700'
                                                    : 'text-gray-700 hover:bg-gray-50'
                                                }`}
                                        >
                                            <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Inicio
                                        </Link>
                                        <Link
                                            href="/mi-cuenta/citas"
                                            className={`flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-colors ${currentPath.startsWith('/mi-cuenta/citas')
                                                    ? 'bg-primary-50 text-primary-700'
                                                    : 'text-gray-700 hover:bg-gray-50'
                                                }`}
                                        >
                                            <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Mis citas
                                        </Link>
                                        <Link
                                            href="/mi-cuenta/perfil"
                                            className={`flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-colors ${currentPath.startsWith('/mi-cuenta/perfil')
                                                    ? 'bg-primary-50 text-primary-700'
                                                    : 'text-gray-700 hover:bg-gray-50'
                                                }`}
                                        >
                                            <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Mi perfil
                                        </Link>
                                    </nav>
                                </div>
                            </aside>

                            {/* Contenido principal */}
                            <div className="lg:col-span-3">
                                {children}
                            </div>
                        </div>
                    </div>
                ) : (
                    children
                )}
            </main>

            {/* Footer */}
            <footer className="bg-gray-900 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {/* Información institucional */}
                        <div>
                            <h3 className="text-lg font-bold mb-4">{clinicInfo?.name || 'Ceciamb'}</h3>
                            <p className="text-gray-400 text-sm leading-relaxed">
                                {clinicInfo?.slogan}
                            </p>
                            <p className="text-gray-400 text-sm mt-4">
                                RIF: {clinicInfo?.rif}
                            </p>
                        </div>

                        {/* Contacto */}
                        <div>
                            <h4 className="text-md font-semibold mb-4">Contacto</h4>
                            <ul className="space-y-2 text-sm text-gray-400">
                                <li>{clinicInfo?.address}</li>
                                <li>
                                    <a href={`tel:${clinicInfo?.phone}`} className="hover:text-primary-400">
                                        {clinicInfo?.phone}
                                    </a>
                                </li>
                                <li>
                                    <a href={`mailto:${clinicInfo?.email}`} className="hover:text-primary-400">
                                        {clinicInfo?.email}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {/* Enlaces rápidos */}
                        <div>
                            <h4 className="text-md font-semibold mb-4">Enlaces rápidos</h4>
                            <ul className="space-y-2 text-sm">
                                {navigation.map((item) => (
                                    <li key={item.name}>
                                        <Link href={item.href} className="text-gray-400 hover:text-primary-400">
                                            {item.name}
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>

                    <div className="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                        <p>&copy; {new Date().getFullYear()} {clinicInfo?.name || 'Ceciamb'}. Todos los derechos reservados.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}