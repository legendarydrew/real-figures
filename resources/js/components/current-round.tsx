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
                <ul className="grid gap-4 grid-cols-2 md:grid-cols-4 select-none">
                    {round.songs.map((song) => (
                        <li className="bg-secondary/30 rounded-md leading-none relative" key={song.id}>
                            <ActImage act={song.act} size="full"/>
                            <div className="p-3 lg:p-5 absolute bottom-0 w-full">
                                <div className="text-lg font-semibold leading-tight">{song.act.name}</div>
                                <div className="flex items-center truncate gap-2 text-sm font-semibold leading-tight">
                                    <LanguageFlag languageCode={song.language}/>
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
