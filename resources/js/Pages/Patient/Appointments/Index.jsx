import { Head, Link, usePage, router } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import { useState } from 'react';

const STATUS_COLORS = {
    pendiente: 'bg-amber-100 text-amber-700',
    confirmada: 'bg-green-100 text-green-700',
    cancelada: 'bg-red-100 text-red-700',
    completada: 'bg-blue-100 text-blue-700',
    no_asistio: 'bg-gray-100 text-gray-700',
};

export default function AppointmentsIndex({ appointments, activeStatus, statuses }) {
    const { flash } = usePage().props;
    const [confirmingCancel, setConfirmingCancel] = useState(null);

    const handleCancel = (id) => {
        router.post(`/mi-cuenta/citas/${id}/cancelar`, {}, {
            onSuccess: () => setConfirmingCancel(null),
        });
    };

    const statusOptions = [
        { value: '', label: 'Todas' },
        ...Object.entries(statuses).map(([key, label]) => ({ value: key, label })),
    ];

    return (
        <PublicLayout>
            <Head title="Mis citas" />

            <div className="mb-8">
                <h1 className="text-3xl font-bold text-gray-900">Mis citas</h1>
                <p className="text-gray-600 mt-1">Historial completo de tus citas.</p>
            </div>

            {flash?.success && (
                <div className="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {flash.success}
                </div>
            )}

            {flash?.error && (
                <div className="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {flash.error}
                </div>
            )}

            {/* Filtros por estado */}
            <div className="flex flex-wrap gap-2 mb-6">
                {statusOptions.map((option) => (
                    <Link
                        key={option.value || 'all'}
                        href={`/mi-cuenta/citas${option.value ? `?status=${option.value}` : ''}`}
                        className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                            (activeStatus || '') === option.value
                                ? 'bg-primary-600 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-100'
                        }`}
                    >
                        {option.label}
                    </Link>
                ))}
            </div>

            {/* Listado */}
            {appointments.length === 0 ? (
                <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                    <p className="text-gray-600 mb-4">No tienes citas registradas.</p>
                    <Link
                        href="/citas"
                        className="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors"
                    >
                        Solicitar una cita
                    </Link>
                </div>
            ) : (
                <div className="space-y-4">
                    {appointments.map((apt) => (
                        <div key={apt.id} className="bg-white rounded-xl shadow-sm p-6">
                            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div className="flex-1">
                                    <div className="flex items-center gap-3 mb-2">
                                        <Link
                                            href={`/mi-cuenta/citas/${apt.id}`}
                                            className="font-bold text-gray-900 hover:text-primary-600"
                                        >
                                            {apt.reference}
                                        </Link>
                                        <span className={`text-xs font-medium px-2.5 py-1 rounded-full ${STATUS_COLORS[apt.status] || 'bg-gray-100 text-gray-700'}`}>
                                            {apt.status.charAt(0).toUpperCase() + apt.status.slice(1)}
                                        </span>
                                    </div>
                                    <div className="text-sm text-gray-600 space-y-0.5">
                                        {apt.doctor_name && <p>👨‍⚕️ {apt.doctor_name}</p>}
                                        {apt.specialty_name && <p>🏥 {apt.specialty_name}</p>}
                                        <p>
                                            📅 {new Date(apt.requested_date + 'T00:00:00').toLocaleDateString('es-VE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                                            {' · '}
                                            🕐 {apt.requested_time.slice(0, 5)}
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-center gap-2 shrink-0">
                                    <Link
                                        href={`/mi-cuenta/citas/${apt.id}`}
                                        className="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                                    >
                                        Ver detalle
                                    </Link>
                                    {apt.can_cancel && (
                                        <button
                                            onClick={() => setConfirmingCancel(apt.id)}
                                            className="px-4 py-2 border border-red-300 text-red-600 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors"
                                        >
                                            Cancelar
                                        </button>
                                    )}
                                </div>
                            </div>

                            {/* Confirmación de cancelación */}
                            {confirmingCancel === apt.id && (
                                <div className="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                                    <p className="text-sm text-red-800 mb-3">
                                        ¿Estás seguro de que deseas cancelar esta cita? Esta acción no se puede deshacer.
                                    </p>
                                    <div className="flex gap-2">
                                        <button
                                            onClick={() => handleCancel(apt.id)}
                                            className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors"
                                        >
                                            Sí, cancelar
                                        </button>
                                        <button
                                            onClick={() => setConfirmingCancel(null)}
                                            className="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors"
                                        >
                                            No, mantener
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            )}
        </PublicLayout>
    );
}