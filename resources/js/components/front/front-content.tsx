export const FrontContent: React.FC = ({ children }) => {

    return (
        <main className="flex-grow w-full overflow-y-auto">
            <div className="max-w-5xl mx-auto py-8 px-6 lg:px-4">
                {children}
            </div>
        </main>
    );
}
