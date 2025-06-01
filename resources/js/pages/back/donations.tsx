import AppLayout from '@/layouts/app-layout';
import { Head, WhenVisible } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Donation } from '@/types';
import React from 'react';
import { Nothing } from '@/components/nothing';
import { cn } from '@/lib/utils';
import { NotepadText } from 'lucide-react';

interface DonationsPageProps {
    count: number;
    rows: Donation[];
    isFirstPage: boolean;
    currentPage: number;
    hasMorePages: boolean;
}

export default function DonationsPage({ count, rows, currentPage, hasMorePages }: Readonly<DonationsPageProps>) {

    return (
        <AppLayout>
            <Head title="Donations"/>

            <div className="flex lg:justify-between lg:items-end mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">Donations</h1>
                {count ? <p className="text-sm">{count.toLocaleString()} Donation(s) were made.</p> : ''}
            </div>

            {count ? (
                <>
                    {rows.map((row) => (
                        <Collapsible className="my-1 mx-4" key={row.id}>
                            <div
                                className="flex gap-2 items-center px-2 py-1 w-full bg-green-400 hover:bg-green-500 dark:bg-green-800">
                                <CollapsibleTrigger
                                    className="flex gap-3 w-full items-center cursor-pointer select-none">
                                    <NotepadText className={cn('text-sm', row.message ? 'text-current' : 'text-muted-foreground/20')}/>
                                    <span
                                        className={cn('flex-grow text-left', row.is_anonymous ? 'italic' : 'font-semibold')}>
                                        {row.name}
                                    </span>
                                    <time className="w-[12em] text-sm text-right">{row.created_at}</time>
                                    <span className="w-[10em] text-right font-semibold">{row.amount}</span>
                                </CollapsibleTrigger>
                            </div>
                            <CollapsibleContent className="py-1 px-3 bg-green-100/50">
                                {row.message ?
                                    <blockquote className="mb-2 py-1 text-sm">
                                        <b>Their message:</b><br/>
                                        &ldquo;{row.message}&rdquo;
                                    </blockquote> :
                                    <Nothing className="text-sm justify-start">No message.</Nothing>}
                            </CollapsibleContent>
                        </Collapsible>
                    ))}
                    {hasMorePages ? (
                        <WhenVisible always params={{
                            data: { page: currentPage + 1 },
                            only: ['rows'],
                            reset: ['currentPage', 'hasMorePages'],
                            preserveUrl: true
                        }}/>) : ''}
                </>
            ) : (
                <Nothing>
                    No Donations... yet.
                </Nothing>
            )}
        </AppLayout>
    );
}
