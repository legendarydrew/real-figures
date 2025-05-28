import { ActImage } from '@/components/ui/act-image';
import { Round } from '@/types';

interface PreviousRoundProps {
    round: Round;
}

export const PreviousRound: React.FC<PreviousRoundProps> = ({ round }) => {

    return (
        <div className="mb-2 bg-black/50 p-3 rounded-sm">
            <p className="font-semibold">{round.title}</p>
            <ul className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                {round.songs.map((song) => (
                    <li className="bg-secondary/15 rounded-md leading-none relative"
                        key={song.id}>
                        <ActImage act={song.act} size="full"/>
                        <div className="p-5 absolute bottom-0">
                            <div className="text-base font-semibold">{song.act.name}</div>
                            <div className="text-sm font-semibold">{song.title}</div>
                        </div>
                    </li>
                ))}
            </ul>
        </div>

    );
};
