import { Round, Song } from '@/types';
import { useDialog } from '@/context/dialog-context';
import React, { useEffect, useState } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Alert } from '@/components/mode/alert';
import { LoadingButton } from '@/components/mode/loading-button';
import { VoteIcon } from 'lucide-react';
import { Button } from '@/components/ui/button';
import axios from 'axios';
import { useAnalytics } from '@/hooks/use-analytics';
import { SongBanner } from '@/components/mode/song-banner';

interface RoundVoteDialogProps {
    round: Round;
}

export const ROUND_VOTE_DIALOG_NAME = 'round-vote';

/**
 * ROUND VOTE DIALOG component
 * This component presents a dialog for allowing the user to vote on the specified Round.
 * There won't be a limit on the number of times a user can vote on a Round,
 * but they should only be able to do so once at a time.
 * Voting should be disallowed if the Round is not active (before the start time and after
 * the end time).
 */
export const RoundVoteDialog: React.FC<RoundVoteDialogProps> = ({ round }) => {

    const { openDialogName, closeDialog } = useDialog();
    const { trackEvent } = useAnalytics();

    const isOpen = openDialogName === ROUND_VOTE_DIALOG_NAME;

    const votePositions = [
        { key: 'first', ordinal: '1st' },
        { key: 'second', ordinal: '2nd' },
        { key: 'third', ordinal: '3rd' }
    ];

    const [userVotes, setUserVotes] = useState({});
    const [errors, setErrors] = useState<{ [key: string]: string }>({});
    const [isVoting, setIsVoting] = useState<boolean>(false);
    const [successful, setSuccessful] = useState<boolean>(false);

    useEffect(() => {
        if (isOpen) {
            // Reset the votes.
            setUserVotes({});
            setSuccessful(false);
            setErrors({});
        }
    }, [isOpen]);

    const isChecked = (song: Song, position: string): boolean => {
        return userVotes[position] === song.id;
    };

    const hasErrors = (): boolean => {
        return Object.entries(errors).length > 0;
    };

    const hasError = (position: string): boolean => {
        return Object.hasOwn(errors, `${position}_choice_id`);
    };

    const setVoteHandler = (song: Song, position: string): void => {
        // Ensure that Song ids are not duplicated in the user's votes.
        const newUserVotes = { ...userVotes, [position]: song.id };
        votePositions.forEach((vp) => {
            if (vp.key !== position && newUserVotes[vp.key] === song.id) {
                newUserVotes[vp.key] = 0;
            }
        });
        setUserVotes(newUserVotes);

        const updatedErrors = { ...errors };
        delete updatedErrors[`${position}_choice_id`];
        setErrors(updatedErrors);
    };

    const castVoteHandler = (): void => {
        if (isVoting) {
            return;
        }

        setErrors({});

        const payload = {
            round_id: round.id
        };
        votePositions.forEach((vp) => {
            payload[`${vp.key}_choice_id`] = userVotes[vp.key];
        });

        setIsVoting(true);
        axios.post(route('vote'), payload)
            .then(() => {
                setSuccessful(true);
                trackEvent({ category: 'Round', action: 'Vote', label: round.full_title, nonInteraction: false });
            })
            .catch((error: AxiosError) => {
                setErrors(error.response.data.errors);
            })
            .finally(() => {
                setIsVoting(false);
            });
    };

    return (
        <Dialog open={isOpen} onOpenChange={closeDialog}>
            <DialogContent className="lg:max-w-3xl">
                <DialogTitle>Cast your Vote for <span
                    className="text-muted-foreground">{round.full_title}</span>!</DialogTitle>
                <DialogDescription>
                    Vote for your three favourite Songs in this Round...
                </DialogDescription>

                <div className="my-1 max-h-[60dvh] overflow-y-auto">
                    {round.songs.map((song) => (
                        <div key={song.id}
                             className="flex items-center hover:bg-zinc-200 select-none pr-2">
                            <SongBanner className="flex-grow" song={song}/>
                            {votePositions.map((position) => (
                                <Button key={`${song.id}-${position.key}`}
                                        variant={isChecked(song, position.key) ? 'checked' : 'outline'}
                                        className={`w-20 text-center p-2 ${hasError(position.key) ? ' border-red-500' : ''}`}
                                        aria-label={`${position.key} place`}
                                        disabled={successful}
                                        onClick={() => setVoteHandler(song, position.key)}>
                                    {position.ordinal}
                                </Button>
                            ))}
                        </div>
                    ))}
                </div>
                {hasErrors() ? (
                    <Alert type="error" className="my-0"
                           message="Something went wrong with casting your vote, please try again."/>
                ) : ''}

                {successful ? (
                    <Alert type="success" message="Your vote has been cast! Thank you!"/>
                ) : (
                    <DialogFooter>
                        <LoadingButton size="lg" type="button" className="w-full text-base" isLoading={isVoting}
                                       onClick={castVoteHandler}>
                            <VoteIcon/> Cast Vote
                        </LoadingButton>
                    </DialogFooter>
                )}

            </DialogContent>
        </Dialog>
    )
};
