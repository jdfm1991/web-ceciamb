import { Link, Head } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import SectionTitle from '@/Components/SectionTitle';
import ServiceCard from '@/Components/ServiceCard';
import SpecialtyCard from '@/Components/SpecialtyCard';
import DoctorCard from '@/Components/DoctorCard';

export default function Home({ services, specialties, doctors }) {
    return (
        <PublicLayout>
            <Head title="Inicio" />

            {/* Sección Hero */}
            <section className="relative bg-gradient-to-br from-primary-700 via-primary-600 to-primary-500 text-white overflow-hidden">
                <div className="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10" />
                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
                    <div className="max-w-3xl">
                        <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                            Tu salud, nuestra prioridad
                        </h1>
                        <p className="mt-6 text-lg md:text-xl text-primary-100 leading-relaxed">
                            En el Hospital de Clínicas de Ceciamb ofrecemos atención médica de calidad con sensibilidad humana. Agenda tu cita hoy mismo.
                        </p>
                        <div className="mt-8 flex flex-col sm:flex-row gap-4">
                            <Link
                                href="/citas"
                                className="inline-flex items-center justify-center bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg"
                            >
                                Solicitar Cita
                                <svg className="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </Link>
                            <a
                                href="tel:02867120100"
                                className="inline-flex items-center justify-center border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary-700 transition-colors"
                            >
                                <svg className="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Llamar ahora
                            </a>
                        </div>
                        <div className="mt-10 grid grid-cols-3 gap-6 max-w-lg">
                            <div>
                                <div className="text-3xl font-bold">55+</div>
                                <div className="text-sm text-primary-100 mt-1">Especialidades</div>
                            </div>
                            <div>
                                <div className="text-3xl font-bold">24/7</div>
                                <div className="text-sm text-primary-100 mt-1">Emergencias</div>
                            </div>
                            <div>
                                <div className="text-3xl font-bold">15+</div>
                                <div className="text-sm text-primary-100 mt-1">Años de servicio</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Sección Servicios */}
            <section className="py-16 md:py-24 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <SectionTitle
                        title="Nuestros Servicios"
                        subtitle="Contamos con tecnología de punta y un equipo humano comprometido con tu bienestar."
                        centered
                    />
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        {services.map((service) => (
                            <ServiceCard key={service.id} service={service} />
                        ))}
                    </div>
                    <div className="text-center mt-10">
                        <Link
                            href="/servicios"
                            className="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold"
                        >
                            Ver todos los servicios
                            <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </section>

            {/* Sección Especialidades */}
            <section className="py-16 md:py-24 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <SectionTitle
                        title="Especialidades Médicas"
                        subtitle="Más de 50 especialidades y subespecialidades a tu disposición."
                        centered
                    />
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        {specialties.map((specialty) => (
                            <SpecialtyCard key={specialty.id} specialty={specialty} />
                        ))}
                    </div>
                    <div className="text-center mt-10">
                        <Link
                            href="/especialidades"
                            className="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold"
                        >
                            Ver todas las especialidades
                            <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </section>

            {/* Sección Médicos */}
            <section className="py-16 md:py-24 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <SectionTitle
                        title="Nuestro Equipo Médico"
                        subtitle="Profesionales altamente capacitados comprometidos con tu salud."
                        centered
                    />
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {doctors.map((doctor) => (
                            <DoctorCard key={doctor.id} doctor={doctor} />
                        ))}
                    </div>
                    <div className="text-center mt-10">
                        <Link
                            href="/medicos"
                            className="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold"
                        >
                            Ver directorio completo
                            <svg className="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </section>

            {/* Sección Sobre la Clínica */}
            <section className="py-16 md:py-24 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <SectionTitle
                                title="Sobre Ceciamb"
                                subtitle="Más de una década cuidando la salud de nuestra comunidad."
                            />
                            <p className="text-gray-600 leading-relaxed mb-4">
                                El Hospital de Clínicas de Ceciamb es un centro de salud comprometido con la excelencia médica y el trato humano. Contamos con instalaciones modernas, tecnología de punta y un equipo de especialistas dedicados a brindar atención integral.
                            </p>
                            <p className="text-gray-600 leading-relaxed mb-6">
                                Nuestra misión es ofrecer servicios de salud accesibles y de calidad, con un enfoque en la prevención, el diagnóstico oportuno y el tratamiento efectivo.
                            </p>
                            <div className="space-y-3">
                                <div className="flex items-start">
                                    <div className="shrink-0 w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center mt-0.5">
                                        <svg className="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                        </svg>
                                    </div>
                                    <span className="ml-3 text-gray-700">Atención médica especializada</span>
                                </div>
                                <div className="flex items-start">
                                    <div className="shrink-0 w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center mt-0.5">
                                        <svg className="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                        </svg>
                                    </div>
                                    <span className="ml-3 text-gray-700">Tecnología de diagnóstico avanzada</span>
                                </div>
                                <div className="flex items-start">
                                    <div className="shrink-0 w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center mt-0.5">
                                        <svg className="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                        </svg>
                                    </div>
                                    <span className="ml-3 text-gray-700">Equipo humano comprometido</span>
                                </div>
                                <div className="flex items-start">
                                    <div className="shrink-0 w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center mt-0.5">
                                        <svg className="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                        </svg>
                                    </div>
                                    <span className="ml-3 text-gray-700">Atención con sensibilidad humana</span>
                                </div>
                            </div>
                        </div>
                        <div className="relative">
                            <div className="aspect-square rounded-2xl overflow-hidden bg-gray-100 shadow-xl">
                                <img
                                    src="/images/logo.png"
                                    alt="Hospital de Clínicas de Ceciamb"
                                    className="w-full h-full object-contain p-12"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Sección Contacto Rápido */}
            <section className="py-16 bg-primary-700 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                        <div className="flex flex-col items-center">
                            <div className="w-14 h-14 rounded-full bg-primary-600 flex items-center justify-center mb-4">
                                <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 className="font-bold text-lg mb-2">Dirección</h3>
                            <p className="text-primary-100 text-sm">Detrás del C.C. Río Caura, Ciudad Guayana</p>
                        </div>
                        <div className="flex flex-col items-center">
                            <div className="w-14 h-14 rounded-full bg-primary-600 flex items-center justify-center mb-4">
                                <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <h3 className="font-bold text-lg mb-2">Teléfono</h3>
                            <a href="tel:02867120100" className="text-primary-100 text-sm hover:text-white">0286-7120100</a>
                        </div>
                        <div className="flex flex-col items-center">
                            <div className="w-14 h-14 rounded-full bg-primary-600 flex items-center justify-center mb-4">
                                <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 className="font-bold text-lg mb-2">Email</h3>
                            <a href="mailto:atencionalpublico@grupoceciamb.com" className="text-primary-100 text-sm hover:text-white">atencionalpublico@grupoceciamb.com</a>
                        </div>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}