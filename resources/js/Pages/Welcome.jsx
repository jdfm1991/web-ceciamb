import { Head } from '@inertiajs/react';

export default function Welcome({ message }) {
    return (
        <>
            <Head title="Bienvenido" />
            <div className="min-h-screen flex items-center justify-center bg-gray-100">
                <div className="text-center">
                    <h1 className="text-4xl font-bold text-gray-800">
                        Hospital de Clínicas de Ceciamb
                    </h1>
                    <p className="mt-4 text-lg text-gray-600">{message}</p>
                </div>
            </div>
        </>
    );
}