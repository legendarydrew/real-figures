import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function titleCase(str: string) {
    return str?.length ? str[0].toUpperCase() + str.slice(1) : '';
}

// https://stackoverflow.com/a/77850950/4073160
export const cssVar = (name) => {
    return getComputedStyle(document.documentElement).getPropertyValue(name);
}

export function formatDate(locale: unknown, timestamp: string): string {
    return new Date(timestamp).toLocaleDateString(locale as string, {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}
