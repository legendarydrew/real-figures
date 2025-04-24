import { cn } from '@/lib/utils';

/**
 * The famous "Nothing" style, now as a React component.
 * Use this to display a message when there are no results.
 */

export const Nothing: React.FC = ({ children, className, ...props }) => {

    const nothingClasses = 'px-10 py-4 flex items-center justify-center italic text-base text-muted-foreground';

    return (
        <div className={cn(nothingClasses, className)} {...props}>
            {children}
        </div>
    );
}
