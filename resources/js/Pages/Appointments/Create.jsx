import { Head } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import AppointmentWizard from '@/Components/Wizard/AppointmentWizard';

export default function AppointmentCreate({ specialties, preselectedDoctor }) {
    return (
        <PublicLayout>
            <Head title="Solicitar cita" />

            {/* Header */}
            <section className="bg-gradient-to-br from-primary-700 to-primary-500 text-white py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl md:text-4xl font-bold">Solicitar una cita</h1>
                    <p className="mt-3 text-lg text-primary-100 max-w-2xl">
                        Completa los siguientes pasos para enviar tu solicitud. Recepción se pondrá en contacto contigo para confirmar.
                    </p>
                </div>
            </section>

            {/* Wizard */}
            <section className="py-12 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <AppointmentWizard
                        specialties={specialties}
                        preselectedDoctor={preselectedDoctor}
                    />
                </div>
            </section>
        </PublicLayout>
    );
}