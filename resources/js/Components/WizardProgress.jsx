const STEPS = [
    { number: 1, label: 'Especialidad' },
    { number: 2, label: 'Fecha y hora' },
    { number: 3, label: 'Tus datos' },
    { number: 4, label: 'Confirmar' },
];

export default function WizardProgress({ currentStep }) {
    return (
        <div className="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div className="flex items-center justify-between">
                {STEPS.map((step, index) => (
                    <div key={step.number} className="flex items-center flex-1">
                        {/* Círculo del paso */}
                        <div className="flex flex-col items-center">
                            <div
                                className={`w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm transition-colors ${
                                    currentStep > step.number
                                        ? 'bg-primary-600 text-white'
                                        : currentStep === step.number
                                        ? 'bg-primary-600 text-white ring-4 ring-primary-100'
                                        : 'bg-gray-200 text-gray-500'
                                }`}
                            >
                                {currentStep > step.number ? (
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                                    </svg>
                                ) : (
                                    step.number
                                )}
                            </div>
                            <span
                                className={`mt-2 text-xs font-medium hidden sm:block ${
                                    currentStep >= step.number ? 'text-primary-600' : 'text-gray-500'
                                }`}
                            >
                                {step.label}
                            </span>
                        </div>

                        {/* Línea conectora */}
                        {index < STEPS.length - 1 && (
                            <div className="flex-1 h-0.5 mx-2 sm:mx-4">
                                <div
                                    className={`h-full transition-colors ${
                                        currentStep > step.number ? 'bg-primary-600' : 'bg-gray-200'
                                    }`}
                                />
                            </div>
                        )}
                    </div>
                ))}
            </div>
        </div>
    );
}