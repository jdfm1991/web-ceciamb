import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function AppointmentSuccess({ appointment }) {
    return (
        <PublicLayout>
            <Head title="Solicitud enviada" />

            <section className="py-16 md:py-24 bg-gray-50">
                <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white rounded-2xl shadow-lg p-8 md:p-12 text-center">
                        {/* Icono de éxito */}
                        <div className="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
                            <svg className="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <h1 className="text-3xl font-bold text-gray-900 mb-3">
                            ¡Solicitud enviada!
                        </h1>
                        <p className="text-gray-600 mb-6">
                            Hemos recibido tu solicitud de cita. Recepción se pondrá en contacto contigo para confirmarla.
                        </p>

                        {/* Número de referencia */}
                        <div className="bg-primary-50 border border-primary-200 rounded-xl p-6 mb-6">
                            <p className="text-sm font-medium text-primary-700 mb-1">Número de referencia</p>
                            <p className="text-2xl font-bold text-primary-700">{appointment.reference}</p>
                        </div>

                        {/* Detalles */}
                        <div className="bg-gray-50 rounded-xl p-6 text-left space-y-3 mb-8">
                            <div className="flex justify-between">
                                <span className="text-sm text-gray-600">Paciente:</span>
                                <span className="text-sm font-medium text-gray-900">{appointment.patient_name}</span>
                            </div>
                            {appointment.specialty_name && (
                                <div className="flex justify-between">
                                    <span className="text-sm text-gray-600">Especialidad:</span>
                                    <span className="text-sm font-medium text-gray-900">{appointment.specialty_name}</span>
                                </div>
                            )}
                            {appointment.doctor_name && (
                                <div className="flex justify-between">
                                    <span className="text-sm text-gray-600">Médico:</span>
                                    <span className="text-sm font-medium text-gray-900">{appointment.doctor_name}</span>
                                </div>
                            )}
                            <div className="flex justify-between">
                                <span className="text-sm text-gray-600">Fecha:</span>
                                <span className="text-sm font-medium text-gray-900">
                                    {new Date(appointment.requested_date + 'T00:00:00').toLocaleDateString('es-VE', {
                                        weekday: 'long',
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                    })}
                                </span>
                            </div>
                            <div className="flex justify-between">
                                <span className="text-sm text-gray-600">Hora:</span>
                                <span className="text-sm font-medium text-gray-900">
                                    {appointment.requested_time.slice(0, 5)}
                                </span>
                            </div>
                            <div className="flex justify-between">
                                <span className="text-sm text-gray-600">Estado:</span>
                                <span className="text-sm font-medium text-amber-600">Pendiente de confirmación</span>
                            </div>
                        </div>

                        {/* Importante */}
                        <div className="bg-amber-50 border border-amber-200 rounded-xl p-4 text-left mb-8">
                            <div className="flex items-start">
                                <svg className="w-5 h-5 text-amber-600 mr-3 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div className="text-sm text-amber-800">
                                    <p className="font-semibold mb-1">Esta es una solicitud, no una cita confirmada</p>
                                    <p>Recepción revisará tu solicitud y se pondrá en contacto contigo por teléfono o correo para confirmarla.</p>
                                </div>
                            </div>
                        </div>

                        {/* Acciones */}
                        <div className="flex flex-col sm:flex-row gap-3 justify-center">
                            <Link
                                href="/"
                                className="inline-flex items-center justify-center border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                            >
                                Volver al inicio
                            </Link>
                            <Link
                                href="/medicos"
                                className="inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors"
                            >
                                Ver más médicos
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}