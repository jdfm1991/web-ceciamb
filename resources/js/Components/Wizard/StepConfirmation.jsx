import { useForm } from '@inertiajs/react';

export default function StepConfirmation({ formData, specialtyName, doctorName, onBack }) {
    const { data, setData, post, processing, errors } = useForm({
        patient_name: formData.patient_name,
        patient_email: formData.patient_email,
        patient_phone: formData.patient_phone,
        message: formData.message || '',
        specialty_id: formData.specialty_id,
        doctor_id: formData.doctor_id,
        requested_date: formData.requested_date,
        requested_time: formData.requested_time ? formData.requested_time.slice(0, 5) : '',
    });

    const handleConfirm = () => {
        post('/citas');
    };

    const formatDate = (dateStr) => {
        if (!dateStr) return '';
        const date = new Date(dateStr + 'T00:00:00');
        return date.toLocaleDateString('es-VE', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    };

    const formatTime = (timeStr) => {
        if (!timeStr) return '';
        return timeStr.slice(0, 5);
    };

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-2xl font-bold text-gray-900 mb-2">
                    Revisa y confirma
                </h2>
                <p className="text-gray-600">
                    Verifica que todos los datos sean correctos antes de enviar.
                </p>
            </div>

            {/* Resumen */}
            <div className="bg-gray-50 rounded-xl p-6 space-y-4">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Especialidad</p>
                        <p className="text-gray-900 font-semibold">{specialtyName || 'Sin asignar'}</p>
                    </div>
                    <div>
                        <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Médico</p>
                        <p className="text-gray-900 font-semibold">{doctorName || 'Cualquier médico disponible'}</p>
                    </div>
                    <div>
                        <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Fecha</p>
                        <p className="text-gray-900 font-semibold capitalize">{formatDate(formData.requested_date)}</p>
                    </div>
                    <div>
                        <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Hora</p>
                        <p className="text-gray-900 font-semibold">{formatTime(formData.requested_time)}</p>
                    </div>
                </div>

                <div className="border-t border-gray-200 pt-4">
                    <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Datos del paciente</p>
                    <div className="space-y-1">
                        <p className="text-gray-900"><span className="font-medium">Nombre:</span> {formData.patient_name}</p>
                        <p className="text-gray-900"><span className="font-medium">Email:</span> {formData.patient_email}</p>
                        <p className="text-gray-900"><span className="font-medium">Teléfono:</span> {formData.patient_phone}</p>
                    </div>
                </div>

                {formData.message && (
                    <div className="border-t border-gray-200 pt-4">
                        <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Mensaje</p>
                        <p className="text-gray-700 text-sm">{formData.message}</p>
                    </div>
                )}
            </div>

            {/* Aviso importante */}
            <div className="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <div className="flex items-start">
                    <svg className="w-5 h-5 text-amber-600 mr-3 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div className="text-sm text-amber-800">
                        <p className="font-semibold mb-1">Esta es una solicitud, no una confirmación</p>
                        <p>Recepción revisará tu solicitud y te contactará para confirmar la cita. La franja horaria quedará reservada por 24 horas.</p>
                    </div>
                </div>
            </div>

            {/* Errores del servidor */}
            {Object.keys(errors).length > 0 && (
                <div className="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <p className="font-semibold mb-2">Por favor corrige los siguientes errores:</p>
                    <ul className="list-disc list-inside space-y-1 text-sm">
                        {Object.values(errors).map((error, i) => (
                            <li key={i}>{error}</li>
                        ))}
                    </ul>
                </div>
            )}

            {/* Botones */}
            <div className="flex flex-col sm:flex-row justify-between gap-3 pt-4">
                <button
                    type="button"
                    onClick={onBack}
                    disabled={processing}
                    className="inline-flex items-center justify-center border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors disabled:opacity-50"
                >
                    <svg className="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                    </svg>
                    Corregir
                </button>
                <button
                    type="button"
                    onClick={handleConfirm}
                    disabled={processing}
                    className="inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {processing ? (
                        <>
                            <svg className="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            Enviando solicitud...
                        </>
                    ) : (
                        <>
                            <svg className="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Confirmar solicitud
                        </>
                    )}
                </button>
            </div>
        </div>
    );
}