import { Song } from '@/types';
import { ActImage } from '@/components/mode/act-image';
import { cn } from '@/lib/utils';

interface SongBannerProps {
    song: Song;
    className: string;
}

export const SongBanner: React.FC<SongBannerProps> = ({ song, className, ...props }) => {

    return (
        <div className={cn("song-banner", className)} {...props}>
            <div className="song-banner-image">
                <ActImage act={song.act} size="12"/>
            </div>
            <div className="song-banner-text">
                <div className="song-banner-text-name">
                    {song.act.name}
                    <small>{song.act.subtitle}</small>
                </div>
                <div className="song-banner-text-title">
                    {song.title}
                </div>
            </div>
        </div>
    );
};
