import { ActImage } from '@/components/ui/act-image';
import { Round } from '@/types';
import { LanguageFlag } from '@/components/language-flag';

interface PreviousRoundProps {
    round: Round;
}

export const PreviousRound: React.FC<PreviousRoundProps> = ({ round }) => {

    return (
        <div className="mb-2 bg-black/50 p-3 rounded-sm">
            <p className="font-semibold">{round.title}</p>
            <ul className="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-2 select-none">
                {round.songs.map((song) => (
                    <li className="flex items-center gap-2 col-span-1 row-span-1" key={song.id}>
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
                    </li>
                ))}
            </ul>
        </div>

    );
};
