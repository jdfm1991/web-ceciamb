import { usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function StepPatientData({ formData, onNext, onBack }) {
    const { auth } = usePage().props;

    const [data, setData] = useState({
        patient_name: formData.patient_name || auth?.user?.name || '',
        patient_email: formData.patient_email || auth?.user?.email || '',
        patient_phone: formData.patient_phone || '',
        message: formData.message || '',
    });

    const [errors, setErrors] = useState({});

    const validate = () => {
        const newErrors = {};

        if (!data.patient_name || data.patient_name.length < 3) {
            newErrors.patient_name = 'El nombre debe tener al menos 3 caracteres.';
        }

        if (!data.patient_email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.patient_email)) {
            newErrors.patient_email = 'Ingresa un correo electrónico válido.';
        }

        if (!data.patient_phone || !/^[0-9+\-\s()]{7,20}$/.test(data.patient_phone)) {
            newErrors.patient_phone = 'Ingresa un teléfono válido (solo números, +, -, espacios).';
        }

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleNext = () => {
        if (!validate()) return;
        onNext(data);
    };

    const inputClass = (field) =>
        `w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all ${
            errors[field] ? 'border-red-500 bg-red-50' : 'border-gray-300'
        }`;

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-2xl font-bold text-gray-900 mb-2">
                    Tus datos de contacto
                </h2>
                <p className="text-gray-600">
                    Usaremos esta información para confirmar tu cita.
                </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label htmlFor="patient_name" className="block text-sm font-medium text-gray-700 mb-1.5">
                        Nombre completo *
                    </label>
                    <input
                        id="patient_name"
                        type="text"
                        value={data.patient_name}
                        onChange={(e) => setData({ ...data, patient_name: e.target.value })}
                        className={inputClass('patient_name')}
                        placeholder="Tu nombre completo"
                    />
                    {errors.patient_name && <p className="mt-1 text-sm text-red-600">{errors.patient_name}</p>}
                </div>

                <div>
                    <label htmlFor="patient_email" className="block text-sm font-medium text-gray-700 mb-1.5">
                        Correo electrónico *
                    </label>
                    <input
                        id="patient_email"
                        type="email"
                        value={data.patient_email}
                        onChange={(e) => setData({ ...data, patient_email: e.target.value })}
                        className={inputClass('patient_email')}
                        placeholder="tu@email.com"
                    />
                    {errors.patient_email && <p className="mt-1 text-sm text-red-600">{errors.patient_email}</p>}
                </div>
            </div>

            <div>
                <label htmlFor="patient_phone" className="block text-sm font-medium text-gray-700 mb-1.5">
                    Teléfono *
                </label>
                <input
                    id="patient_phone"
                    type="tel"
                    value={data.patient_phone}
                    onChange={(e) => setData({ ...data, patient_phone: e.target.value })}
                    className={inputClass('patient_phone')}
                    placeholder="0414-1234567"
                />
                {errors.patient_phone && <p className="mt-1 text-sm text-red-600">{errors.patient_phone}</p>}
            </div>

            <div>
                <label htmlFor="message" className="block text-sm font-medium text-gray-700 mb-1.5">
                    Mensaje <span className="text-gray-400">(opcional)</span>
                </label>
                <textarea
                    id="message"
                    rows="4"
                    value={data.message}
                    onChange={(e) => setData({ ...data, message: e.target.value })}
                    className={inputClass('message')}
                    placeholder="Cuéntanos brevemente el motivo de tu consulta..."
                />
            </div>

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
                    className="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors"
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