import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { GoldenBuzzer } from '@/types';
import { useState } from 'react';
import { LoadingButton } from '@/components/ui/loading-button';
import { Nothing } from '@/components/nothing';
import { cn } from '@/lib/utils';
import { SongBanner } from '@/components/song-banner';

interface GoldenBuzzerPageProps {
    messages: GoldenBuzzer[];
    isFirstPage: boolean;
    currentPage: number;
    hasMorePages: boolean;
}

export default function GoldenBuzzersPage({ rows, currentPage, hasMorePages }: Readonly<GoldenBuzzerPageProps>) {

    const [isLoading, setIsLoading] = useState(false);

    const nextPageHandler = (): void => {
        router.reload({
            data: {
                page: currentPage + 1
            },
            preserveUrl: true,
            only: ['rows'],
            reset: ['currentPage', 'hasMorePages'],
            onStart: () => {
                setIsLoading(true);
            },
            onFinish: () => {
                setIsLoading(false);
            }
        });
    }

    return (
        <AppLayout>
            <Head title="Golden Buzzers"/>

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl mb-3">Golden Buzzers</h1>
            </div>

            {rows?.length ? (
                <>
                    {rows.map((row) => (
                        <Collapsible className="my-1 mx-4" key={row.id}>
                            <div
                                className="flex gap-2 items-center p-2 w-full bg-amber-400 hover:bg-amber-500 dark:bg-amber-800">
                                <CollapsibleTrigger
                                    className="flex gap-3 w-full items-center cursor-pointer select-none">
                                    <span
                                        className={cn('flex-grow text-left', row.is_anonymous ? 'italic' : 'font-semibold')}>
                                        {row.name}
                                    </span>
                                    <span className="font-semibold w-2/5">
                                        {row.round}
                                    </span>
                                    <time className="text-sm text-right">{row.created_at}</time>
                                </CollapsibleTrigger>
                            </div>
                            <CollapsibleContent className="py-1 px-3 bg-amber-100/50">
                                <div className="flex gap-3 items-start">
                                    <SongBanner song={row.song}/>
                                    <div className="flex-grow">
                                        {row.message ?
                                            <blockquote className="mb-2 py-1">&ldquo;{row.message}&rdquo;</blockquote> :
                                            <Nothing>No message.</Nothing>}
                                    </div>
                                </div>
                            </CollapsibleContent>
                        </Collapsible>
                    ))}
                    {hasMorePages ? (
                        <LoadingButton variant="secondary" className="mx-auto my-2" isLoading={isLoading}
                                       onClick={nextPageHandler}>More Golden Buzzers</LoadingButton>
                    ) : ''}
                </>
            ) : (
                <Nothing>
                    No Golden Buzzers... yet.
                </Nothing>
            )}
        </AppLayout>
    );
}
