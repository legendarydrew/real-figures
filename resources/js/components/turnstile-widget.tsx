import Turnstile from "react-turnstile";
import { usePage } from '@inertiajs/react';

interface TurnstileWidgetProps {
    onVerify: (string) => void;
    onError: (any) => void;
}

// https://github.com/Le0Developer/react-turnstile

export const TurnstileWidget: React.FC<TurnstileWidgetProps> = ({ onVerify, onError }) => {
    const { turnstileSiteKey } = usePage().props;

    const verifyHandler = (token: string) => {
        if (onVerify) {
            onVerify(token);
        }
    };

    const errorHandler = (e: any) => {
        if (onError) {
            onError(e);
        }
    };

    return (
        <Turnstile sitekey={turnstileSiteKey} refreshExpired="auto" onError={errorHandler} onVerify={verifyHandler}/>
    );
}
