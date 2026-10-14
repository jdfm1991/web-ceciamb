export default function ScheduleTable({ schedulesByDay, dayNames }) {
    // Orden lógico: Lunes a Domingo
    const dayOrder = [1, 2, 3, 4, 5, 6, 0];

    const activeDays = dayOrder.filter(
        (day) => schedulesByDay[day] && schedulesByDay[day].length > 0
    );

    if (activeDays.length === 0) {
        return (
            <div className="bg-gray-50 rounded-xl p-8 text-center">
                <svg className="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p className="text-gray-600">
                    Este médico no tiene horarios de atención configurados por el momento.
                </p>
            </div>
        );
    }

    return (
        <div className="overflow-hidden rounded-xl border border-gray-200">
            <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                    <tr>
                        <th className="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Día
                        </th>
                        <th className="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Horario
                        </th>
                        <th className="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Duración de cita
                        </th>
                    </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                    {activeDays.map((day) => (
                        <tr key={day} className="hover:bg-gray-50 transition-colors">
                            <td className="px-6 py-4 whitespace-nowrap">
                                <span className="font-medium text-gray-900">
                                    {dayNames[day]}
                                </span>
                            </td>
                            <td className="px-6 py-4">
                                <div className="flex flex-wrap gap-2">
                                    {schedulesByDay[day].map((schedule) => (
                                        <span
                                            key={schedule.id}
                                            className="inline-flex items-center bg-primary-50 text-primary-700 text-sm px-3 py-1.5 rounded-lg font-medium"
                                        >
                                            <svg className="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {schedule.start_time.slice(0, 5)} - {schedule.end_time.slice(0, 5)}
                                        </span>
                                    ))}
                                </div>
                            </td>
                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {schedulesByDay[day][0]?.slot_duration_minutes || 30} minutos
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}