import toast from 'react-hot-toast';
import { Check, CircleAlertIcon, InfoIcon, LucideIcon, TriangleAlertIcon, XIcon } from 'lucide-react';
import { Icon } from '@/components/icon';

/**
 * Helpers for creating toast messages.
 */

const displayToastMessage = (message: string, icon: LucideIcon | null) => {
    return toast.custom((t) => (
        <div
            className={`${
                t.visible ? 'animate-enter' : 'animate-leave'
            } max-w-md w-full bg-white shadow-lg rounded-md pointer-events-auto flex`}
        >
            <div className="flex-1 w-0 p-4">
                <div className="flex items-start">
                    {icon && (
                        <div className="flex-shrink-0">
                            <Icon iconNode={icon}/>
                        </div>
                    )}
                    <div className="ml-3 flex-1 text-sm font-medium text-gray-900">
                        {message}
                    </div>
                </div>
            </div>
            <button
                onClick={() => toast.dismiss(t.id)}
                className="text-sm p-2 font-medium hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                <XIcon/>
            </button>
        </div>
    ));
}

export const Toaster = {
    success: (message: string) => displayToastMessage(message, Check),
    info: (message: string) => displayToastMessage(message, InfoIcon),
    error: (message: string) => displayToastMessage(message, CircleAlertIcon),
    warning: (message: string) => displayToastMessage(message, TriangleAlertIcon)
};
