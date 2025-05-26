export default function HeadingSmall({ title, description }: Readonly<{ title: string; description?: string }>) {
    return (
        <header>
            <h3 className="display-text text-base leading-tight my-1.5">{title}</h3>
            {description && <p className="text-muted-foreground text-sm">{description}</p>}
        </header>
    );
}
