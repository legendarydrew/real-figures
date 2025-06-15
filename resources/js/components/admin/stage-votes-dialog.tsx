import { FC, useEffect, useState } from 'react';
import { Dialog, DialogContent, DialogTitle } from '@/components/ui/dialog';
import { RoundVoteBreakdown, Stage } from '@/types';
import axios from 'axios';
import { LoaderCircleIcon } from 'lucide-react';
import { Alert } from '@/components/alert';
import HeadingSmall from '@/components/heading-small';
import { SongBanner } from '@/components/song-banner';

interface StageVotesDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
    stage?: Stage;
}

export const StageVotesDialog: FC<StageVotesDialogProps> = ({ open, onOpenChange, stage }) => {

    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [breakdown, setBreakdown] = useState<RoundVoteBreakdown[]>([]);
    const [error, setError] = useState<string>(null);

    useEffect(() => {
        if (stage) {
            setBreakdown([]);
            fetchResults();
        }
    }, [open]);

    const fetchResults = (): void => {
        if (isLoading || !stage) {
            return;
        }

        setIsLoading(true);
        setError(null);

        axios.get(route('stages.votes', { id: stage?.id }))
            .then((response) => {
                setBreakdown(response.data);
            })
            .catch((response) => {
                setError(response.response.data.message);
            })
            .finally(() => setIsLoading(false));
    }

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent aria-describedby={undefined} className="md:max-w-4xl">
                <DialogTitle>{stage?.title} - Vote Breakdown</DialogTitle>

                {isLoading ? (<LoaderCircleIcon className="w-[3rem] h-[3rem] mx-auto my-3 animate-spin"/>) : (
                    <>
                        {error && <Alert type="error">{error}</Alert>}
                        <div className="overflow-y-auto max-h-[50dvh]">
                            {breakdown?.map((row) => (
                                <div key={row.id} className="my-2">
                                    <div className="flex text-sm font-semibold items-center">
                                        <div className="flex-grow">
                                            <HeadingSmall title={row.title}/>
                                        </div>
                                        <div className="w-[6em] px-3 text-right">
                                            <span className="text-xs">Score</span>
                                        </div>
                                        <div className="w-[6em] px-3 text-right">
                                            <span className="text-xs">1st</span>
                                        </div>
                                        <div className="w-[6em] px-3 text-right">
                                            <span className="text-xs">2nd</span>
                                        </div>
                                        <div className="w-[6em] px-3 text-right">
                                            <span className="text-xs">3rd</span>
                                        </div>
                                    </div>
                                    <ul>
                                        {row.songs.map((song) => (
                                            <li key={song.song.id} className="flex items-center hover:bg-gray-300/50">
                                                <SongBanner className="flex-grow" song={song.song}/>
                                                <div className="w-[6em] p-3 text-right font-semibold">{song.score}</div>
                                                <div
                                                    className="w-[6em] p-3 text-sm text-right">{song.first_choice_votes}</div>
                                                <div
                                                    className="w-[6em] p-3 text-sm text-right">{song.second_choice_votes}</div>
                                                <div
                                                    className="w-[6em] p-3 text-sm text-right">{song.third_choice_votes}</div>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            ))}
                        </div>
                    </>
                )}

            </DialogContent>
        </Dialog>
    )
}
