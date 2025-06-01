import { ActImage } from '@/components/ui/act-image';
import { Round, Song } from '@/types';
import { LanguageFlag } from '@/components/language-flag';
import { ROUND_VOTE_DIALOG_NAME, RoundVoteDialog } from '@/components/front/round-vote-dialog';
import { Button } from '@/components/ui/button';
import { useDialog } from '@/context/dialog-context';
import { StarIcon } from 'lucide-react';
import { GOLDEN_BUZZER_DIALOG_NAME } from '@/components/front/golden-buzzer-dialog';

interface CurrentRoundProps {
    round?: Round;
}

/**
 * CURRENT ROUND component
 * This component displays the Acts in the specified Round, acting as the current Round,
 * with the ability to listen to and vote on their respective songs.
 */
export const CurrentRound: React.FC<CurrentRoundProps> = ({ round }) => {

    const { openDialog } = useDialog();

    const beginVoteHandler = (): void => {
        openDialog(ROUND_VOTE_DIALOG_NAME);
    };

    const beginBuzzerHandler = (song: Song): void => {
        openDialog(GOLDEN_BUZZER_DIALOG_NAME, { stage: round?.id, song });
    };

    return (
        round ? (
            <>
                {/* The Acts and their songs. */}
                <ul className="grid gap-4 grid-cols-2 md:grid-cols-4 select-none">
                    {round.songs.map((song) => (
                        <li key={song.id}>
                            <div className="bg-secondary/30 rounded-t-md leading-none relative">
                                <ActImage act={song.act} size="full"/>
                                <div className="p-3 lg:p-5 absolute bottom-0 w-full">
                                    <div className="text-lg font-semibold leading-tight">{song.act.name}</div>
                                    <div
                                        className="flex items-center truncate gap-2 text-sm font-semibold leading-tight">
                                        <LanguageFlag languageCode={song.language}/>
                                        {song.title}
                                    </div>
                                </div>
                            </div>
                            <Button className="w-full rounded-t-none uppercase" variant="gold" size="lg" type="button"
                                    onClick={() => beginBuzzerHandler(song)}>
                                <StarIcon/> Golden Buzzer <StarIcon/>
                            </Button>
                        </li>
                    ))}
                </ul>

                {/* A big button for casting a vote. */}
                <Button size="lg" className="w-full my-3 text-base" type="button" onClick={beginVoteHandler}>Cast your
                    Vote...</Button>
                <RoundVoteDialog round={round}/>
            </>
        ) : ''
    )
};
