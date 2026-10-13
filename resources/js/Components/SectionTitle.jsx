export default function SectionTitle({ title, subtitle, centered = false, light = false }) {
    return (
        <div className={`mb-12 ${centered ? 'text-center' : ''}`}>
            <h2 className={`text-3xl md:text-4xl font-bold ${light ? 'text-white' : 'text-gray-900'}`}>
                {title}
            </h2>
            {subtitle && (
                <p className={`mt-3 text-lg ${light ? 'text-gray-200' : 'text-gray-600'} ${centered ? 'max-w-3xl mx-auto' : ''}`}>
                    {subtitle}
                </p>
            )}
            <div className={`mt-4 h-1 w-20 bg-primary-600 rounded ${centered ? 'mx-auto' : ''}`} />
        </div>
    );
}