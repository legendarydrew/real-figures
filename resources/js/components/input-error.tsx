import { cn } from '@/lib/utils';
import { type HTMLAttributes } from 'react';
import { MessageSquareWarningIcon } from 'lucide-react';

export default function InputError({ message, className = '', ...props }: HTMLAttributes<HTMLParagraphElement> & {
    message?: string
}) {
    return message ? (
        <p {...props} className={cn('input-error', className)}>
            <MessageSquareWarningIcon className="size-4"/> {message}
        </p>
    ) : null;
}
