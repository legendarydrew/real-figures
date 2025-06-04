/**
 * SONG PLAYER component
 * This component will be used to play a YouTube video representing a Song.
 * It can also be used to award the Song with a Golden Buzzer.
 * We want this to appear regardless of which page the visitor is on, so we use it inside the
 * FrontLayout component.
 * NOTE: anything that should be able to open (or close) this song player should be *inside*
 * the SongPlayerProvider (as in, functionality is PROVIDED to the children).
 * https://ankurraina.medium.com/reactjs-pass-functions-through-context-typeerror-cannot-destructure-property-of-450a8edd55b6
 */
import { SongBanner } from '@/components/song-banner';
import { StarIcon, XIcon } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useSongPlayer } from '@/context/song-player-context';
import YouTube from 'react-youtube';

export const SongPlayer: React.FC = () => {

    const { isPlayerOpen, currentSong, closeSongPlayer } = useSongPlayer();

    const playerOptions = {
        // height: '390',
        // width: '640',
        playerVars: {
            // https://developers.google.com/youtube/player_parameters
            autoplay: 1,
            color: 'white',
            rel: 0,  // only show related videos from the same channel.
            widget_referrer: import.meta.env.VITE_APP_URL
        }
    };

    const songPlayHandler = () => {
        // Called when the YouTube video is played.
        console.log('video played.');
    }

    return (isPlayerOpen && currentSong) ? (
        <aside
            className="z-10 fixed bottom-10 rounded-sm bg-gray-200 dark:bg-gray-700 border-1 shadow-lg w-full max-w-[400px]">
            {currentSong.video_id &&
                <YouTube
                    videoId={currentSong.video_id}
                    iframeClassName="w-full max-h-360 h-[40dvh]"
                    title={`YouTube video player: ${currentSong.title} by ${currentSong.act.name}`}
                    opts={playerOptions}
                    onPlay={songPlayHandler}
                />}
            <div className="flex items-center">
                <SongBanner className="flex-grow" song={currentSong}/>
                <div className="toolbar gap-1 mr-1">
                    <Button variant="gold" type="button" title="Award a Golden Buzzer!">
                        <StarIcon/>
                    </Button>
                    <Button variant="ghost" type="button" title="Close the player" onClick={closeSongPlayer}>
                        <XIcon/>
                    </Button>
                </div>
            </div>
        </aside>
    ) : '';
};
