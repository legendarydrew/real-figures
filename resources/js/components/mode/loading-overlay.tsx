import { LoaderCircleIcon } from 'lucide-react';

interface Props {
    isLoading?: boolean;
}

export const LoadingOverlay: React.FC<Props> = ({ isLoading = false, children }) => {

    return (
        <div className="loading-overlay">
            {children}
            {isLoading && (<div className="loading-overlay-cover">
                <LoaderCircleIcon className="loading-overlay-icon"/>
            </div>)}
        </div>
    );
}
