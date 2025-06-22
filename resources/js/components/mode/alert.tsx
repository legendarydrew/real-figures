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

    const alertClasses = (): string => {
        switch (type) {
            case 'warning':
                return 'bg-orange-100 text-orange-800';
            case 'info':
                return 'bg-blue-100 text-blue-800';
            case 'error':
                return 'bg-red-100 text-red-800';
            case 'success':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-indigo-100 text-indigo-800';
        }
    }
    return (message || children) ? (
        <div
            className={cn("flex gap-3 justify-between items-center px-3 py-2 bg-green-100 rounded-sm my-3 text-sm", alertClasses(), message ? 'font-semibold' : '', className)}
            {...props}>
            <Icon className="text-lg" iconNode={alertIcon()}/>
            <div className="flex-grow">{message ?? children}</div>
        </div>
    ) : '';
}
