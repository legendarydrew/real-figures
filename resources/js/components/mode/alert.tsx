import { CheckIcon, CircleAlertIcon, InfoIcon, SpeechIcon, TriangleAlertIcon } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Icon } from '@/components/icon';

interface AlertProps {
    type?: 'success' | 'warning' | 'error' | 'info';
    message: string;
}

export const Alert: React.FC<AlertProps> = ({ type, message, className, children, ...props }) => {

    const alertIcon = () => {
        switch (type) {
            case 'warning':
                return TriangleAlertIcon;
            case 'error':
                return CircleAlertIcon;
            case 'info':
                return InfoIcon;
            case 'success':
                return CheckIcon;
            default:
                return SpeechIcon;
        }
    };

    return (message || children) ? (
        <div className={cn("alert", type, message ? 'with-message' : '', className)} {...props}>
            <Icon className="alert-icon" iconNode={alertIcon()}/>
            <div className="alert-text">{message ?? children}</div>
        </div>
    ) : '';
}
