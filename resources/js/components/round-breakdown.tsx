import { SongBanner } from '@/components/song-banner';
import { RoundVoteBreakdown } from '@/types';
import { Badge } from '@/components/ui/badge';

interface RoundBreakdownProps {
    breakdown: RoundVoteBreakdown;
    className?: string;
}

export const RoundBreakdown: React.FC<RoundBreakdownProps> = ({ breakdown, className }) => {

    const wasManualOutcome = (): boolean => {
        return breakdown.songs.every((song) => song.was_manual);
    };

    return (
        <div className={className}>
            <div className="flex bg-zinc-600 py-2 text-sm font-semibold items-end leading-none sticky-top">
                <div className="flex-grow flex gap-3 items-end">
                    <h3 className="display-text pl-3 text-base">{breakdown.title}</h3>
                    {wasManualOutcome() && <Badge variant="secondary" title="Votes cast by an independent panel.">Judged</Badge>}
                </div>
                <div className="w-[6em] px-3 text-right">
                    <span className="text-xs">Score</span>
                </div>
                <div className="w-[6em] px-3 text-right">
                    <span className="text-xs">1st<br/>choice</span>
                </div>
                <div className="w-[6em] px-3 text-right">
                    <span className="text-xs">2nd<br/>choice</span>
                </div>
                <div className="w-[6em] px-3 text-right">
                    <span className="text-xs">3rd<br/>choice</span>
                </div>
            </div>
            <ul>
                {breakdown.songs.map((song) => (
                    <li key={song.song.id}
                        className="flex items-center hover:bg-zinc-200/20 p-1 leading-tight rounded-sm  ">
                        <SongBanner className="flex-grow" song={song.song}/>
                        <div
                            className="w-[6em] p-3 text-right font-semibold">{song.score}</div>
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
    )
}
