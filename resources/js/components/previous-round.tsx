import { Round, Song } from '@/types';
import { Button } from '@/components/ui/button';
import { StarIcon } from 'lucide-react';
import { GOLDEN_BUZZER_DIALOG_NAME } from '@/components/front/golden-buzzer-dialog';
import { useDialog } from '@/context/dialog-context';
import { useSongPlayer } from '@/context/song-player-context';
import { SongBanner } from '@/components/song-banner';

interface PreviousRoundProps {
    round: Round;
}

export const PreviousRound: React.FC<PreviousRoundProps> = ({ round }) => {

    const { openDialog } = useDialog();
    const { openSongPlayer } = useSongPlayer();

    const beginBuzzerHandler = (song: Song): void => {
        openDialog(GOLDEN_BUZZER_DIALOG_NAME, { round, song });
    };

    return (
        <div className="mb-2 bg-black/50 p-3 rounded-sm">
            <p className="font-semibold">{round.title}</p>
            <ul className="grid gap-1 grid-cols-1 md:grid-cols-2 lg:grid-cols-2 select-none">
                {round.songs.map((song) => (
                    <li key={song.id}
                        className="flex items-center gap-2 col-span-1 row-span-1 hover:bg-white/10 rounded-md">
                        <button type="button" className="flex-grow cursor-pointer"
                                onClick={() => openSongPlayer(round, song)}>
                            <SongBanner className="text-left" song={song}/>
                        </button>
                        <Button className="hidden md:block" variant="gold" size="lg" type="button" title="Golden Buzzer"
                                onClick={() => beginBuzzerHandler(song)}>
                            <StarIcon/>
                        </Button>
                    </li>
                ))}
            </ul>
        </div>

    );
};
