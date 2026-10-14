import { useState } from 'react';
import WizardProgress from '@/Components/WizardProgress';
import StepSpecialty from './StepSpecialty';
import StepDateTime from './StepDateTime';
import StepPatientData from './StepPatientData';
import StepConfirmation from './StepConfirmation';

export default function AppointmentWizard({ specialties, preselectedDoctor }) {
    const initialStep = preselectedDoctor ? 2 : 1;

    const [currentStep, setCurrentStep] = useState(initialStep);
    const [formData, setFormData] = useState({
        specialty_id: preselectedDoctor?.specialties?.[0]?.id || '',
        doctor_id: preselectedDoctor?.id || '',
        requested_date: '',
        requested_time: '',
        patient_name: '',
        patient_email: '',
        patient_phone: '',
        message: '',
    });

    const updateFormData = (newData) => {
        setFormData((prev) => ({ ...prev, ...newData }));
    };

    const goToStep = (step) => {
        setCurrentStep(step);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const nextStep = (newData) => {
        updateFormData(newData);
        goToStep(currentStep + 1);
    };

    const prevStep = () => {
        goToStep(currentStep - 1);
    };

    const getSpecialtyName = () => {
        const specialty = specialties.find((s) => s.id === formData.specialty_id);
        return specialty?.name || '';
    };

    const getDoctorName = () => {
        if (!formData.doctor_id) return null;
        if (preselectedDoctor && formData.doctor_id === preselectedDoctor.id) {
            return preselectedDoctor.name;
        }
        return null; // Se resolverá desde el backend al confirmar
    };

    return (
        <div className="max-w-4xl mx-auto">
            <WizardProgress currentStep={currentStep} />

            <div className="bg-white rounded-xl shadow-sm p-6 md:p-8">
                {currentStep === 1 && (
                    <StepSpecialty
                        specialties={specialties}
                        formData={formData}
                        onNext={nextStep}
                        preselectedDoctor={preselectedDoctor}
                    />
                )}

                {currentStep === 2 && (
                    <StepDateTime
                        formData={formData}
                        onNext={nextStep}
                        onBack={prevStep}
                    />
                )}

                {currentStep === 3 && (
                    <StepPatientData
                        formData={formData}
                        onNext={nextStep}
                        onBack={prevStep}
                    />
                )}

                {currentStep === 4 && (
                    <StepConfirmation
                        formData={formData}
                        specialtyName={getSpecialtyName()}
                        doctorName={getDoctorName()}
                        onBack={prevStep}
                    />
                )}
            </div>
        </div>
    );
}