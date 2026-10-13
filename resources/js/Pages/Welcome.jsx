import PublicLayout from '@/Layouts/PublicLayout';

export default function Welcome({ message }) {
    return (
        <PublicLayout>
            <div className="min-h-[60vh] flex items-center justify-center bg-gray-50">
                <div className="text-center">
                    <h1 className="text-4xl font-bold text-primary-700">
                        Hospital de Clínicas de Ceciamb
                    </h1>
                    <p className="mt-4 text-lg text-gray-600">{message}</p>
                </div>
            </div>
        </PublicLayout>
    );
}