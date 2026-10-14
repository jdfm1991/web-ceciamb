import { useState, useEffect } from 'react';

export default function StepSpecialty({ specialties, formData, onNext, preselectedDoctor, onDoctorsLoaded }) {
    const [selectedSpecialtyId, setSelectedSpecialtyId] = useState(
        formData.specialty_id || preselectedDoctor?.specialties?.[0]?.id || ''
    );
    const [selectedDoctorId, setSelectedDoctorId] = useState(formData.doctor_id || preselectedDoctor?.id || '');
    const [doctors, setDoctors] = useState([]);
    const [loadingDoctors, setLoadingDoctors] = useState(false);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        if (preselectedDoctor && preselectedDoctor.specialties?.length > 0) {
            const specialtyId = preselectedDoctor.specialties[0].id;
            // La carga ya se dispara por el useEffect principal si selectedSpecialtyId coincide
            // Solo aseguramos que availableDoctors se llene
        }
    }, [preselectedDoctor]);

    // Cargar médicos cuando cambia la especialidad
    useEffect(() => {
        if (!selectedSpecialtyId) {
            setDoctors([]);
            if (onDoctorsLoaded) onDoctorsLoaded([]);
            return;
        }

        setLoadingDoctors(true);
        fetch(`/api/specialties/${selectedSpecialtyId}/doctors`)
            .then((res) => res.json())
            .then((data) => {
                setDoctors(data.doctors || []);
                if (onDoctorsLoaded) onDoctorsLoaded(data.doctors || []);
            })
            .catch(() => {
                setDoctors([]);
                if (onDoctorsLoaded) onDoctorsLoaded([]); // NUEVO
            })
            .finally(() => setLoadingDoctors(false));
    }, [selectedSpecialtyId]);

    const handleNext = () => {
        if (!selectedSpecialtyId) return;

        onNext({
            specialty_id: selectedSpecialtyId,
            doctor_id: selectedDoctorId || null,
        });
    };

    const filteredSpecialties = specialties.filter((s) =>
        s.name.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-2xl font-bold text-gray-900 mb-2">
                    ¿Qué especialidad necesitas?
                </h2>
                <p className="text-gray-600">
                    Selecciona una especialidad y, si lo deseas, un médico específico.
                </p>
            </div>

            {/* Buscador de especialidades */}
            <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    placeholder="Buscar especialidad..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none"
                />
            </div>

            {/* Especialidades */}
            <div>
                <label className="block text-sm font-medium text-gray-700 mb-3">
                    Especialidad *
                </label>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-80 overflow-y-auto pr-2">
                    {filteredSpecialties.map((specialty) => (
                        <button
                            key={specialty.id}
                            type="button"
                            onClick={() => {
                                setSelectedSpecialtyId(specialty.id);
                                setSelectedDoctorId('');
                            }}
                            className={`text-left px-4 py-3 rounded-lg border transition-all ${selectedSpecialtyId === specialty.id
                                ? 'border-primary-600 bg-primary-50 ring-2 ring-primary-200'
                                : 'border-gray-200 hover:border-primary-300 hover:bg-gray-50'
                                }`}
                        >
                            <div className="font-medium text-sm text-gray-900">{specialty.name}</div>
                        </button>
                    ))}
                </div>
                {filteredSpecialties.length === 0 && (
                    <p className="text-sm text-gray-500 text-center py-4">
                        No se encontraron especialidades con ese término.
                    </p>
                )}
            </div>

            {/* Médicos de la especialidad */}
            {selectedSpecialtyId && (
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-3">
                        Médico <span className="text-gray-400">(opcional)</span>
                    </label>
                    {loadingDoctors ? (
                        <div className="text-sm text-gray-500 py-4 text-center">
                            Cargando médicos...
                        </div>
                    ) : doctors.length === 0 ? (
                        <div className="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg text-sm">
                            No hay médicos disponibles en esta especialidad. Puedes continuar y recepción asignará uno.
                        </div>
                    ) : (
                        <div className="space-y-2">
                            <button
                                type="button"
                                onClick={() => setSelectedDoctorId('')}
                                className={`w-full text-left px-4 py-3 rounded-lg border transition-all ${selectedDoctorId === ''
                                    ? 'border-primary-600 bg-primary-50 ring-2 ring-primary-200'
                                    : 'border-gray-200 hover:border-primary-300 hover:bg-gray-50'
                                    }`}
                            >
                                <div className="font-medium text-sm text-gray-900">
                                    Cualquier médico disponible
                                </div>
                                <div className="text-xs text-gray-500 mt-0.5">
                                    Recepción asignará al primer médico disponible.
                                </div>
                            </button>
                            {doctors.map((doctor) => (
                                <button
                                    key={doctor.id}
                                    type="button"
                                    onClick={() => setSelectedDoctorId(doctor.id)}
                                    className={`w-full text-left px-4 py-3 rounded-lg border transition-all flex items-center ${selectedDoctorId === doctor.id
                                        ? 'border-primary-600 bg-primary-50 ring-2 ring-primary-200'
                                        : 'border-gray-200 hover:border-primary-300 hover:bg-gray-50'
                                        }`}
                                >
                                    <div className="w-10 h-10 rounded-full overflow-hidden bg-gray-100 shrink-0 mr-3">
                                        <img
                                            src={doctor.photo ? `/storage/${doctor.photo}` : '/images/logo.png'}
                                            alt={doctor.name}
                                            className="w-full h-full object-cover"
                                        />
                                    </div>
                                    <div className="font-medium text-sm text-gray-900">{doctor.name}</div>
                                </button>
                            ))}
                        </div>
                    )}
                </div>
            )}

            {/* Botón siguiente */}
            <div className="flex justify-end pt-4">
                <button
                    type="button"
                    onClick={handleNext}
                    disabled={!selectedSpecialtyId}
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