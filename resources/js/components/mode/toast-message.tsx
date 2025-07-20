import toast from 'react-hot-toast';
import { XIcon } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Alert } from '@/components/mode/alert';
import { Button } from '@/components/ui/button';

/**
 * Helpers for creating toast messages.
 */

const displayToastMessage = (message: string, type: string = 'default') => {
    return toast.custom((t) => (
        <Alert type={type} className={cn(t.visible ? 'animate-enter' : 'animate-leave', "shadow-md")}>
            <div className="flex gap-5 items-center">
                <div className="flex-grow font-semibold">{message}</div>
                <Button variant="ghost" size="icon" type="button" className="w-auto h-auto hover:bg-transparent"
                        onClick={() => toast.remove(t.id)}>
                    <XIcon className="w-2 h-2"/>
                </Button>
            </div>
        </Alert>
    ));
}

export const RTToast = {
    default: (message: string) => displayToastMessage(message),
    success: (message: string) => displayToastMessage(message, 'success'),
    info: (message: string) => displayToastMessage(message, 'info'),
    error: (message: string) => displayToastMessage(message, 'error'),
    warning: (message: string) => displayToastMessage(message, 'warning')
};
