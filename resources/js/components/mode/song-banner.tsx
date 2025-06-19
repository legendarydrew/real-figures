import { Song } from '@/types';
import { ActImage } from '@/components/mode/act-image';
import { LanguageFlag } from '@/components/mode/language-flag';
import { cn } from '@/lib/utils';

interface SongBannerProps {
    song: Song;
    className: string;
}

export const SongBanner: React.FC<SongBannerProps> = ({ song, className, ...props }) => {

    return (
        <div className={cn("flex items-center gap-2", className)} {...props}>
            <div className="bg-secondary/15 rounded-md leading-none">
                <ActImage act={song.act}/>
            </div>
            <div className="px-3 py-1 flex-grow text-left display-text">
                <div className="text-sm truncate">{song.act.name}</div>
                <div className="flex gap-2 text-xs items-center truncate">
                    <LanguageFlag languageCode={song.language}/>
                    {song.title}
                </div>
            </div>
        </div>
    );
};
