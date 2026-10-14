import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function Dashboard({ nextAppointment, stats, recentAppointments }) {
    return (
        <PublicLayout>
            <Head title="Mi cuenta" />

            {/* Bienvenida */}
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-gray-900">Mi cuenta</h1>
                <p className="text-gray-600 mt-1">Gestiona tus citas y datos personales.</p>
            </div>

            {/* Próxima cita */}
            {nextAppointment ? (
                <div className="bg-gradient-to-br from-primary-600 to-primary-500 rounded-xl p-6 text-white mb-8">
                    <div className="flex items-start justify-between">
                        <div>
                            <p className="text-primary-100 text-sm font-medium mb-1">Tu próxima cita</p>
                            <p className="text-2xl font-bold mb-3">{nextAppointment.reference}</p>
                            <div className="space-y-1 text-sm text-primary-100">
                                {nextAppointment.doctor_name && <p>👨‍⚕️ {nextAppointment.doctor_name}</p>}
                                {nextAppointment.specialty_name && <p>🏥 {nextAppointment.specialty_name}</p>}
                                <p>📅 {new Date(nextAppointment.requested_date + 'T00:00:00').toLocaleDateString('es-VE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                <p>🕐 {nextAppointment.requested_time.slice(0, 5)}</p>
                            </div>
                        </div>
                        <Link
                            href={`/mi-cuenta/citas/${nextAppointment.id}`}
                            className="bg-white text-primary-700 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-colors"
                        >
                            Ver detalle
                        </Link>
                    </div>
                </div>
            ) : (
                <div className="bg-white rounded-xl shadow-sm p-8 text-center mb-8">
                    <p className="text-gray-600 mb-4">No tienes citas próximas.</p>
                    <Link
                        href="/citas"
                        className="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors"
                    >
                        Solicitar una cita
                    </Link>
                </div>
            )}

            {/* Estadísticas */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div className="bg-white rounded-xl shadow-sm p-5">
                    <p className="text-sm text-gray-600 mb-1">Total de citas</p>
                    <p className="text-2xl font-bold text-gray-900">{stats.total}</p>
                </div>
                <div className="bg-white rounded-xl shadow-sm p-5">
                    <p className="text-sm text-gray-600 mb-1">Pendientes</p>
                    <p className="text-2xl font-bold text-amber-600">{stats.pending}</p>
                </div>
                <div className="bg-white rounded-xl shadow-sm p-5">
                    <p className="text-sm text-gray-600 mb-1">Confirmadas</p>
                    <p className="text-2xl font-bold text-green-600">{stats.confirmed}</p>
                </div>
                <div className="bg-white rounded-xl shadow-sm p-5">
                    <p className="text-sm text-gray-600 mb-1">Completadas</p>
                    <p className="text-2xl font-bold text-blue-600">{stats.completed}</p>
                </div>
            </div>

            {/* Últimas citas */}
            <div className="bg-white rounded-xl shadow-sm p-6">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-lg font-bold text-gray-900">Últimas citas</h2>
                    <Link href="/mi-cuenta/citas" className="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Ver todas
                    </Link>
                </div>

                {recentAppointments.length === 0 ? (
                    <p className="text-gray-500 text-sm py-4 text-center">No hay citas registradas.</p>
                ) : (
                    <div className="divide-y divide-gray-100">
                        {recentAppointments.map((apt) => (
                            <Link
                                key={apt.id}
                                href={`/mi-cuenta/citas/${apt.id}`}
                                className="flex items-center justify-between py-3 hover:bg-gray-50 -mx-2 px-2 rounded"
                            >
                                <div>
                                    <p className="font-medium text-gray-900 text-sm">{apt.reference}</p>
                                    <p className="text-xs text-gray-500 mt-0.5">
                                        {apt.specialty_name || apt.doctor_name || 'Consulta'} · {new Date(apt.requested_date + 'T00:00:00').toLocaleDateString('es-VE', { day: 'numeric', month: 'short' })} {apt.requested_time.slice(0, 5)}
                                    </p>
                                </div>
                                <span className={`text-xs font-medium px-2.5 py-1 rounded-full ${
                                    apt.status === 'confirmada' ? 'bg-green-100 text-green-700' :
                                    apt.status === 'pendiente' ? 'bg-amber-100 text-amber-700' :
                                    apt.status === 'cancelada' ? 'bg-red-100 text-red-700' :
                                    apt.status === 'completada' ? 'bg-blue-100 text-blue-700' :
                                    'bg-gray-100 text-gray-700'
                                }`}>
                                    {apt.status.charAt(0).toUpperCase() + apt.status.slice(1)}
                                </span>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}