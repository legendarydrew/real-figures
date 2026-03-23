import AppLayout from '@/layouts/app-layout';
import { Head, usePage, WhenVisible } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { GoldenBuzzer, GoldenBuzzerBreakdown } from '@/types';
import { Nothing } from '@/components/mode/nothing';
import { cn } from '@/lib/utils';
import { SongBanner } from '@/components/mode/song-banner';
import { NotepadText } from 'lucide-react';
import React, { useRef, useState } from 'react';
import axios from 'axios';
import { LoadingButton } from '@/components/mode/loading-button';
import { BuzzerBreakdownDialog } from '@/components/admin/buzzer-breakdown-dialog';
import { AdminHeader } from '@/components/admin/admin-header';

interface GoldenBuzzerPageProps {
    total: number;
    count: number;
    rows: GoldenBuzzer[];
    isFirstPage: boolean;
    currentPage: number;
    hasMorePages: boolean;
}

export default function GoldenBuzzersPage({
                                              total,
                                              count,
                                              rows,
                                              currentPage,
                                              hasMorePages
                                          }: Readonly<GoldenBuzzerPageProps>) {

    const { donation } = usePage().props;
    const [isLoadingBreakdown, setIsLoadingBreakdown] = useState<boolean>(false);
    const [showBreakdown, setShowBreakdown] = useState<boolean>(false);

    const breakdownData = useRef<GoldenBuzzerBreakdown>();

    const breakdownHandler = (): void => {
        if (isLoadingBreakdown) {
            return;
        }

        setIsLoadingBreakdown(true);
        axios.get('/api/golden-buzzers/breakdown')
            .then((response) => {
                breakdownData.current = response.data;
                setShowBreakdown(true);
            })
            .finally(() => {
                setIsLoadingBreakdown(false);
            });
    };

    const closeBreakdownHandler = (): void => {
        setShowBreakdown(false);
        breakdownData.current = undefined;
    };

    return (
        <AppLayout>
            <Head title="Golden Buzzers"/>

            <div className="admin-content">

                <AdminHeader title="Golden Buzzers">
                    {!!count && (
                        <div className="flex gap-2 items-center">
                            <p className="text-sm">
                                <b>{count.toLocaleString()} Golden {count === 1 ? 'Buzzer' : 'Buzzers'}</b> {count === 1 ? 'was' : 'were'} hit.
                            </p>
                            <LoadingButton type="button" isLoading={isLoadingBreakdown} onClick={breakdownHandler}>Show
                                breakdown</LoadingButton>
                        </div>
                    )}
                </AdminHeader>

                {count ? (
                    <div className="grid lg:grid-cols-4">
                        <div className="lg:col-span-3">
                            {rows.map((row) => (
                                <Collapsible className="my-0.5 mx-4" key={row.id}>
                                    <div
                                        className="flex font-mono gap-2 items-center px-2 py-1 w-full bg-gold/50 hover:bg-gold">
                                        <CollapsibleTrigger
                                            className="flex gap-3 w-full items-center cursor-pointer select-none">
                                            <NotepadText
                                                className={cn('text-sm', row.message ? 'text-current' : 'text-muted-foreground/20')}/>
                                            <span
                                                className={cn('flex-grow text-left', row.is_anonymous ? 'italic' : 'font-semibold')}>
                                        {row.name}
                                    </span>
                                            <span className="font-semibold text-sm w-2/5">
                                        {row.round}
                                    </span>
                                            <time className="text-sm text-right">{row.created_at}</time>
                                            <span className="w-24 text-right font-semibold">{row.amount}</span>
                                        </CollapsibleTrigger>
                                    </div>
                                    <CollapsibleContent className="py-1 px-3 bg-amber-100/50">
                                        <div className="flex flex-col lg:flex-row gap-3 items-start">
                                            <SongBanner className="lg:w-1/3 flex-shrink-0" song={row.song}/>
                                            <div className="lg:flex-grow">
                                                {row.message ?
                                                    <blockquote className="mb-2 py-1 text-sm">
                                                        <b>Their message:</b><br/>
                                                        &ldquo;{row.message}&rdquo;
                                                    </blockquote> :
                                                    <Nothing className="text-sm">No message.</Nothing>}
                                            </div>
                                        </div>
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
                                    {donation.currency}&nbsp;
                                    {total.toFixed(2)}
                                </b>
                                <span className="block text-sm">
                                raised {count > 1 && `from ${count.toLocaleString()} Golden Buzzers`}.
                                </span>
                            </p>
                        </div>

                        <BuzzerBreakdownDialog open={showBreakdown} onOpenChange={closeBreakdownHandler}
                                               data={breakdownData.current}/>

                    </div>
                ) : (
                    <Nothing>
                        No Golden Buzzers... yet.
                    </Nothing>
                )}
            </div>
        </AppLayout>
    );
}
