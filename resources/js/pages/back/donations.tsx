import AppLayout from '@/layouts/app-layout';
import { Head, usePage, WhenVisible } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Donation } from '@/types';
import React from 'react';
import { Nothing } from '@/components/mode/nothing';
import { cn } from '@/lib/utils';
import { NotepadText } from 'lucide-react';

interface DonationsPageProps {
    total: number;
    count: number;
    rows: Donation[];
    isFirstPage: boolean;
    currentPage: number;
    hasMorePages: boolean;
}

export default function DonationsPage({ total, count, rows, currentPage, hasMorePages }: Readonly<DonationsPageProps>) {

    const {donation} = usePage().props;

    return (
        <AppLayout>
            <Head title="Donations"/>

            <h1 className="display-text text-2xl p-4 mb-2">Donations</h1>

            {count ? (
                <div className="grid lg:grid-cols-3">
                    <div className="lg:col-span-2">
                        {rows.map((row) => (
                            <Collapsible className="my-0.5 mx-4" key={row.id}>
                                <div
                                    className="flex font-mono gap-2 items-center px-2 py-1 w-full bg-donation/50 hover:bg-donation hover:text-donation-foreground">
                                    <CollapsibleTrigger
                                        disabled={!row.message}
                                        className="flex gap-3 w-full items-center cursor-pointer select-none">
                                        <NotepadText
                                            className={cn('text-sm', row.message ? 'text-current' : 'text-muted-foreground/20')}/>
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
                    </div>
                    <div className="col-span-1">
                        <p className="sticky top-0 px-8">
                            <b className="text-3xl display-text block">
                                { donation.currency }&nbsp;
                                {total.toFixed(2)}
                            </b>
                            <span className="block text-sm">
                                raised {count > 1 && `from ${count.toLocaleString()} donations`}.
                                </span>
                        </p>
                    </div>
                </div>
            ) : (
                <Nothing>
                    No Donations... yet.
                </Nothing>
            )}
        </AppLayout>
    );
}
