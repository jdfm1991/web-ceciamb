import { Head, Link, router } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import { useState } from 'react';

const STATUS_COLORS = {
    pendiente: 'bg-amber-100 text-amber-700',
    confirmada: 'bg-green-100 text-green-700',
    cancelada: 'bg-red-100 text-red-700',
    completada: 'bg-blue-100 text-blue-700',
    no_asistio: 'bg-gray-100 text-gray-700',
};

export default function AppointmentShow({ appointment }) {
    const [confirmingCancel, setConfirmingCancel] = useState(false);

    const handleCancel = () => {
        router.post(`/mi-cuenta/citas/${appointment.id}/cancelar`);
    };

    const photoUrl = appointment.doctor_photo
        ? `/storage/${appointment.doctor_photo}`
        : '/images/logo.png';

    return (
        <PublicLayout>
            <Head title={appointment.reference} />

            <div className="mb-6">
                <Link href="/mi-cuenta/citas" className="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    ← Volver a mis citas
                </Link>
            </div>

            <div className="bg-white rounded-xl shadow-sm p-6 md:p-8">
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">{appointment.reference}</h1>
                        <p className="text-sm text-gray-500 mt-1">Solicitada desde el portal</p>
                    </div>
                    <span className={`text-sm font-medium px-3 py-1.5 rounded-full ${STATUS_COLORS[appointment.status]}`}>
                        {appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
                    </span>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Fecha</p>
                        <p className="text-gray-900 font-semibold capitalize">
                            {new Date(appointment.requested_date + 'T00:00:00').toLocaleDateString('es-VE', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                            })}
                        </p>
                    </div>
                    <div>
                        <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Hora</p>
                        <p className="text-gray-900 font-semibold">{appointment.requested_time.slice(0, 5)}</p>
                    </div>
                    {appointment.specialty_name && (
                        <div>
                            <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Especialidad</p>
                            <p className="text-gray-900 font-semibold">{appointment.specialty_name}</p>
                        </div>
                    )}
                    {appointment.doctor_name && (
                        <div>
                            <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Médico</p>
                            <div className="flex items-center mt-1">
                                <div className="w-8 h-8 rounded-full overflow-hidden bg-gray-100 mr-2">
                                    <img src={photoUrl} alt={appointment.doctor_name} className="w-full h-full object-cover" />
                                </div>
                                <p className="text-gray-900 font-semibold">{appointment.doctor_name}</p>
                            </div>
                        </div>
                    )}
                </div>

                <div className="border-t border-gray-100 pt-6 mb-8">
                    <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Datos del paciente</p>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span className="text-gray-500">Nombre:</span>{' '}
                            <span className="font-medium text-gray-900">{appointment.patient_name}</span>
                        </div>
                        <div>
                            <span className="text-gray-500">Email:</span>{' '}
                            <span className="font-medium text-gray-900">{appointment.patient_email}</span>
                        </div>
                        <div>
                            <span className="text-gray-500">Teléfono:</span>{' '}
                            <span className="font-medium text-gray-900">{appointment.patient_phone}</span>
                        </div>
                    </div>
                </div>

                {appointment.message && (
                    <div className="border-t border-gray-100 pt-6 mb-8">
                        <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Mensaje</p>
                        <p className="text-gray-700 text-sm whitespace-pre-line">{appointment.message}</p>
                    </div>
                )}

                {appointment.can_cancel && (
                    <div className="border-t border-gray-100 pt-6">
                        {!confirmingCancel ? (
                            <button
                                onClick={() => setConfirmingCancel(true)}
                                className="inline-flex items-center border border-red-300 text-red-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors"
                            >
                                Cancelar esta cita
                            </button>
                        ) : (
                            <div className="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p className="text-sm text-red-800 mb-3">
                                    ¿Estás seguro de que deseas cancelar esta cita? Esta acción no se puede deshacer.
                                </p>
                                <div className="flex gap-2">
                                    <button
                                        onClick={handleCancel}
                                        className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors"
                                    >
                                        Sí, cancelar
                                    </button>
                                    <button
                                        onClick={() => setConfirmingCancel(false)}
                                        className="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors"
                                    >
                                        No, mantener
                                    </button>
                                </div>
                            </div>
                        )}
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}