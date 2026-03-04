import Turnstile from "react-turnstile";

interface TurnstileWidgetProps {
    onVerify: (string) => void;
    onError: (any) => void;
}

// https://github.com/Le0Developer/react-turnstile

export const TurnstileWidget: React.FC<TurnstileWidgetProps> = ({ onVerify, onError }) => {
    const turnstileSiteKey = document.querySelector("meta[name=turnstile]").attributes['content'].value;
    // Read the Turnstile site key from a meta tag,

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
