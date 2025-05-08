interface HeadingProps {
    title: string;
    description?: string;
}

export default function Heading({ title, description }: Readonly<HeadingProps>) {
    return (
        <div className="mb-3 space-y-0.5">
            <h2 className="display-text text-xl">{title}</h2>
            {description && <p className="text-muted-foreground text-sm">{description}</p>}
        </div>
    );
}
