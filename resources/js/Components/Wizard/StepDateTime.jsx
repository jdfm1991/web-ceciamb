import { useState, useEffect } from 'react';

export default function StepDateTime({ formData, onNext, onBack }) {
    const [selectedDate, setSelectedDate] = useState(formData.requested_date || '');
    const [selectedTime, setSelectedTime] = useState(formData.requested_time || '');
    const [slots, setSlots] = useState([]);
    const [loadingSlots, setLoadingSlots] = useState(false);
    const [dayName, setDayName] = useState('');

    // Generar las próximas 30 fechas disponibles
    const availableDates = Array.from({ length: 30 }, (_, i) => {
        const date = new Date();
        date.setDate(date.getDate() + i + 1); // Desde mañana
        return date;
    });

    // Cargar franjas cuando cambia la fecha
    useEffect(() => {
        if (!selectedDate || !formData.doctor_id) {
            setSlots([]);
            return;
        }

        setLoadingSlots(true);
        setSelectedTime('');

        fetch(`/api/doctors/${formData.doctor_id}/available-slots?date=${selectedDate}`)
            .then((res) => res.json())
            .then((data) => {
                setSlots(data.slots || []);
                setDayName(data.day_name || '');
            })
            .catch(() => {
                setSlots([]);
                setDayName('');
            })
            .finally(() => setLoadingSlots(false));
    }, [selectedDate, formData.doctor_id]);

    const handleNext = () => {
        if (!selectedDate || !selectedTime) return;

        onNext({
            requested_date: selectedDate,
            requested_time: selectedTime,
        });
    };

    const formatDate = (date) => {
        return date.toLocaleDateString('es-VE', {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
        });
    };

    const formatDateForApi = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    if (!formData.doctor_id) {
        return (
            <div className="space-y-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-900 mb-2">
                        Selecciona un médico primero
                    </h2>
                    <p className="text-gray-600">
                        Para ver horarios disponibles, necesitas elegir un médico específico.
                    </p>
                </div>
                <div className="flex justify-start pt-4">
                    <button
                        type="button"
                        onClick={onBack}
                        className="inline-flex items-center border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                    >
                        <svg className="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver a elegir médico
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-2xl font-bold text-gray-900 mb-2">
                    ¿Cuándo te gustaría venir?
                </h2>
                <p className="text-gray-600">
                    Selecciona una fecha y una hora disponible.
                </p>
            </div>

            {/* Selección de fecha */}
            <div>
                <label className="block text-sm font-medium text-gray-700 mb-3">
                    Fecha *
                </label>
                <div className="grid grid-cols-4 sm:grid-cols-7 gap-2">
                    {availableDates.slice(0, 14).map((date) => {
                        const dateStr = formatDateForApi(date);
                        const isSelected = selectedDate === dateStr;

                        return (
                            <button
                                key={dateStr}
                                type="button"
                                onClick={() => setSelectedDate(dateStr)}
                                className={`px-2 py-3 rounded-lg border text-center transition-all ${isSelected
                                        ? 'border-primary-600 bg-primary-600 text-white'
                                        : 'border-gray-200 hover:border-primary-300 hover:bg-gray-50'
                                    }`}
                            >
                                <div className={`text-xs uppercase font-medium ${isSelected ? 'text-primary-100' : 'text-gray-500'}`}>
                                    {date.toLocaleDateString('es-VE', { weekday: 'short' })}
                                </div>
                                <div className={`text-lg font-bold mt-0.5 ${isSelected ? 'text-white' : 'text-gray-900'}`}>
                                    {date.getDate()}
                                </div>
                                <div className={`text-xs ${isSelected ? 'text-primary-100' : 'text-gray-500'}`}>
                                    {date.toLocaleDateString('es-VE', { month: 'short' })}
                                </div>
                            </button>
                        );
                    })}
                </div>
            </div>

            {/* Selección de hora */}
            {selectedDate && (
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-3">
                        Hora disponible *
                        {dayName && <span className="text-gray-400 font-normal ml-2">({dayName})</span>}
                    </label>

                    {loadingSlots ? (
                        <div className="text-sm text-gray-500 py-6 text-center">
                            <svg className="animate-spin inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            Buscando horarios disponibles...
                        </div>
                    ) : slots.length === 0 ? (
                        <div className="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg text-sm">
                            No hay horarios disponibles para esta fecha. Prueba con otro día.
                        </div>
                    ) : (
                        <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                            {slots.map((slot) => (
                                <button
                                    key={slot.start_time_full}
                                    type="button"
                                    onClick={() => setSelectedTime(slot.start_time_full)}
                                    className={`px-3 py-2.5 rounded-lg border text-sm font-medium transition-all ${selectedTime === slot.start_time_full
                                            ? 'border-primary-600 bg-primary-600 text-white'
                                            : 'border-gray-200 hover:border-primary-300 hover:bg-gray-50 text-gray-900'
                                        }`}
                                >
                                    {slot.start_time}
                                </button>
                            ))}
                        </div>
                    )}
                </div>
            )}

            {/* Botones */}
            <div className="flex justify-between pt-4">
                <button
                    type="button"
                    onClick={onBack}
                    className="inline-flex items-center border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    <svg className="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                    </svg>
                    Atrás
                </button>
                <button
                    type="button"
                    onClick={handleNext}
                    disabled={!selectedDate || !selectedTime}
                    className="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Continuar
                    <svg className="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    );
}