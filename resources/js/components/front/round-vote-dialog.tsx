import { Round, Song } from '@/types';
import { useDialog } from '@/context/dialog-context';
import React, { useEffect, useState } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Alert } from '@/components/alert';
import { LoadingButton } from '@/components/ui/loading-button';
import { VoteIcon } from 'lucide-react';
import { ActImage } from '@/components/ui/act-image';
import { Button } from '@/components/ui/button';
import { LanguageFlag } from '@/components/language-flag';

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
    const isOpen = openDialogName === ROUND_VOTE_DIALOG_NAME;

    const votePositions = [
        { key: 'first', ordinal: '1st' },
        { key: 'second', ordinal: '2nd' },
        { key: 'third', ordinal: '3rd' }
    ];

    const [userVotes, setUserVotes] = useState({});
    const [failed, setFailed] = useState<boolean>(false);

    useEffect(() => {
        if (isOpen) {
            // Reset the votes.
            setUserVotes({});
        }
    }, [isOpen]);

    const isChecked = (song: Song, position: string): boolean => {
        return userVotes[position] === song.id;
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
    };

    const castVoteHandler = (): void => {
        setFailed(false);
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
                             className="flex items-center hover:bg-gray-100/5 select-none hover:bg-gray-100 pr-2">
                            <div className="flex-grow flex gap-2 items-center">
                                <ActImage act={song.act} size="12"/>
                                <p className="leading-tight">
                                    {song.act.name}<br/>
                                    <span className="text-xs flex gap-2 items-center">
                                        <LanguageFlag languageCode={song.language}/>
                                        {song.title}
                                    </span>
                                </p>
                            </div>
                            {votePositions.map((position) => (
                                <Button key={`${song.id}-${position.key}`}
                                        variant={isChecked(song, position.key) ? 'checked' : 'outline'}
                                        className="w-20 text-center p-2"
                                        aria-label={`${position.key} place`}
                                        onClick={() => setVoteHandler(song, position.key)}>
                                    {position.ordinal}
                                </Button>
                            ))}
                        </div>
                    ))}
                </div>
                {failed && (
                    <Alert type="error"
                           message="Something went wrong with casting your vote, please try again."/>
                )}

                <DialogFooter>
                    <LoadingButton size="lg" type="button" className="w-full text-base" onClick={castVoteHandler}>
                        <VoteIcon/> Cast Vote
                    </LoadingButton>
                </DialogFooter>

            </DialogContent>
        </Dialog>
    )
};
