import { cn } from '@/lib/utils';

interface HeadingProps {
    title: string;
    description?: string;
}

export default function Heading({ className, title, description }: Readonly<HeadingProps>) {
    return (
        <div className={cn('space-y-0.5', className)}>
            <h2 className="display-text text-xl">{title}</h2>
            {description && <p className="text-muted-foreground text-sm">{description}</p>}
        </div>
    );
}
