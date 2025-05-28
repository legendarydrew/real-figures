import { ActImage } from '@/components/ui/act-image';
import { Round } from '@/types';
import { LanguageFlag } from '@/components/language-flag';

interface CurrentRoundProps {
    round?: Round;
}

/**
 * This component displays the Acts in the specified Round, acting as the current Round,
 * with the ability to listen to and vote on their respective songs.
 */
export const CurrentRound: React.FC<CurrentRoundProps> = ({ round }) => {

    return (
        round ? (
            <>
                {/* The Acts and their songs. */}
                <ul className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    {round.songs.map((song) => (
                        <li className="bg-secondary/30 rounded-md leading-none relative" key={song.id}>
                            <ActImage act={song.act} size="full"/>
                            <div className="p-5 absolute bottom-0">
                                <div className="text-lg font-semibold">{song.act.name}</div>
                                <div className="text-base font-semibold">
                                    <LanguageFlag language={song.language}/>
                                    {song.title}
                                </div>
                            </div>
                        </li>
                    ))}
                </ul>
            </>
        ) : ''
    )
};
