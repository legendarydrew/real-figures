import { ActImage } from '@/components/ui/act-image';
import { Round, Song } from '@/types';
import { LanguageFlag } from '@/components/language-flag';
import { Button } from '@/components/ui/button';
import { StarIcon } from 'lucide-react';
import { GOLDEN_BUZZER_DIALOG_NAME } from '@/components/front/golden-buzzer-dialog';
import { useDialog } from '@/context/dialog-context';

interface PreviousRoundProps {
    round: Round;
}

export const PreviousRound: React.FC<PreviousRoundProps> = ({ round }) => {

    const { openDialog } = useDialog();

    const beginBuzzerHandler = (song: Song): void => {
        openDialog(GOLDEN_BUZZER_DIALOG_NAME, { round, song });
    };

    return (
        <div className="mb-2 bg-black/50 p-3 rounded-sm">
            <p className="font-semibold">{round.title}</p>
            <ul className="grid gap-1 grid-cols-1 md:grid-cols-2 lg:grid-cols-2 select-none">
                {round.songs.map((song) => (
                    <li className="flex items-center gap-2 col-span-1 row-span-1 hover:bg-white/10 pr-2 rounded-md"
                        key={song.id}>
                        <div className="bg-secondary/15 rounded-md leading-none">
                            <ActImage act={song.act}/>
                        </div>
                        <div className="p-3 flex-grow">
                            <div className="text-sm font-semibold truncate">{song.act.name}</div>
                            <div className="flex gap-2 text-xs items-center font-semibold truncate">
                                <LanguageFlag languageCode={song.language}/>
                                {song.title}
                            </div>
                        </div>
                        <Button variant="gold" size="lg" type="button" title="Golden Buzzer"
                                onClick={() => beginBuzzerHandler(song)}>
                            <StarIcon/>
                        </Button>
                    </li>
                ))}
            </ul>
        </div>

    );
};
