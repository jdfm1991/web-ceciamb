import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function AboutIndex() {
    return (
        <PublicLayout>
            <Head>
                <title>Sobre Nosotros - Hospital de Clínicas de Ceciamb</title>
                <meta name="description" content="Hospital de Clínicas de Ceciamb - Atención médica de calidad con sensibilidad humana. Agenda tu cita con nuestros especialistas." />
            </Head>
            {/* Header */}
            <section className="bg-gradient-to-br from-primary-700 to-primary-500 text-white py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-4xl md:text-5xl font-bold">Sobre Nosotros</h1>
                    <p className="mt-4 text-lg text-primary-100 max-w-2xl">
                        Más de una década cuidando la salud de nuestra comunidad con excelencia médica y trato humano.
                    </p>
                </div>
            </section>

            {/* Historia */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">Nuestra Historia</h2>
                            <div className="space-y-4 text-gray-600 leading-relaxed">
                                <p>
                                    El Hospital de Clínicas de Ceciamb nació con la visión de ofrecer servicios de salud accesibles y de calidad en Ciudad Guayana. Desde nuestros inicios, nos hemos comprometido con la excelencia médica y el trato humano.
                                </p>
                                <p>
                                    A lo largo de los años, hemos crecido en infraestructura, tecnología y equipo humano, consolidándonos como un referente en la región. Contamos con más de 50 especialidades y subespecialidades, así como con servicios de diagnóstico avanzado.
                                </p>
                                <p>
                                    Nuestro compromiso es seguir innovando para brindar a nuestros pacientes la mejor atención posible, siempre con calidez y sensibilidad humana.
                                </p>
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

            {/* Misión y Visión */}
            <section className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {/* Misión */}
                        <div className="bg-white rounded-xl shadow-sm p-8">
                            <div className="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center mb-4">
                                <svg className="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-bold text-gray-900 mb-3">Nuestra Misión</h3>
                            <p className="text-gray-600 leading-relaxed">
                                Ofrecer servicios de salud integrales y de alta calidad, con un enfoque en la prevención, el diagnóstico oportuno y el tratamiento efectivo, siempre con sensibilidad humana y compromiso con nuestros pacientes.
                            </p>
                        </div>

                        {/* Visión */}
                        <div className="bg-white rounded-xl shadow-sm p-8">
                            <div className="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center mb-4">
                                <svg className="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-bold text-gray-900 mb-3">Nuestra Visión</h3>
                            <p className="text-gray-600 leading-relaxed">
                                Ser el centro de salud de referencia en la región, reconocido por la excelencia de nuestros servicios, la innovación tecnológica y el trato humano hacia cada paciente que confía en nosotros.
                            </p>
                        </div>
                    </div>

                    {/* Valores */}
                    <div className="mt-12">
                        <h3 className="text-2xl font-bold text-gray-900 text-center mb-8">Nuestros Valores</h3>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            {[
                                { title: 'Excelencia', desc: 'Buscamos la mejora continua en cada servicio.' },
                                { title: 'Humanidad', desc: 'Tratamos a cada paciente con calidez y respeto.' },
                                { title: 'Compromiso', desc: 'Nos dedicamos a la salud de nuestra comunidad.' },
                                { title: 'Innovación', desc: 'Incorporamos tecnología para mejor atención.' },
                            ].map((valor) => (
                                <div key={valor.title} className="bg-white rounded-xl shadow-sm p-6 text-center">
                                    <h4 className="text-lg font-bold text-primary-700 mb-2">{valor.title}</h4>
                                    <p className="text-sm text-gray-600">{valor.desc}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA */}
            <section className="py-16 bg-gradient-to-r from-primary-600 to-primary-500 text-white">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h3 className="text-3xl font-bold mb-4">¿Listo para cuidar tu salud?</h3>
                    <p className="text-primary-100 mb-8 text-lg">
                        Agenda tu cita con nuestros especialistas y recibe atención de calidad.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-3 justify-center">
                        <Link
                            href="/citas"
                            className="inline-flex items-center justify-center bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg"
                        >
                            Solicitar cita
                        </Link>
                        <Link
                            href="/contacto"
                            className="inline-flex items-center justify-center border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary-700 transition-colors"
                        >
                            Contactar
                        </Link>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}