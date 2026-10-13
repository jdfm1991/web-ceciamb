import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function PublicLayout({ children }) {
    const { clinicInfo } = usePage().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

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
                        </nav>
                    )}
                </div>
            </header>

            {/* Contenido */}
            <main className="flex-1">
                {children}
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