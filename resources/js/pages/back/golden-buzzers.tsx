import AppLayout from '@/layouts/app-layout';
import { Head, WhenVisible } from '@inertiajs/react';
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

interface GoldenBuzzerPageProps {
    count: number;
    rows: GoldenBuzzer[];
    isFirstPage: boolean;
    currentPage: number;
    hasMorePages: boolean;
}

export default function GoldenBuzzersPage({ count, rows, currentPage, hasMorePages }: Readonly<GoldenBuzzerPageProps>) {

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

            <div className="flex lg:justify-between lg:items-end mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">Golden Buzzers</h1>
                {count ? (
                    <div className="flex gap-2 items-center">
                        <p className="text-sm">
                            <b>{count.toLocaleString()} Golden {count === 1 ? 'Buzzer' : 'Buzzers'}</b> {count === 1 ? 'was' : 'were'} hit.
                        </p>
                        <LoadingButton type="button" isLoading={isLoadingBreakdown} onClick={breakdownHandler}>Show
                            breakdown</LoadingButton>
                    </div>
                ) : ''}
            </div>

            {count ? (
                <>
                    {rows.map((row) => (
                        <Collapsible className="my-1 mx-4" key={row.id}>
                            <div
                                className="flex gap-2 items-center px-2 py-1 w-full bg-amber-400 hover:bg-amber-500 dark:bg-amber-800">
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
                                            <Nothing className="text-sm justify-start">No message.</Nothing>}
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

                    <BuzzerBreakdownDialog open={showBreakdown} onOpenChange={closeBreakdownHandler} data={breakdownData.current} />
                </>
            ) : (
                <Nothing>
                    No Golden Buzzers... yet.
                </Nothing>
            )}
        </AppLayout>
    );
}
