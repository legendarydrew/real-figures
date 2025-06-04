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
import { useEffect } from 'react';
import { useSongPlayer } from '@/context/song-player-context';

export const SongPlayer: React.FC = () => {

    const { isPlayerOpen, currentSong, closeSongPlayer } = useSongPlayer();

    useEffect(() => {
        if (isPlayerOpen) {
            // Called when the song player is opened.
            console.log('opened song player', currentSong?.title);
        }
    }, [isPlayerOpen]);

    return (isPlayerOpen && currentSong) ? (
        <aside className="z-10 fixed bottom-10 rounded-sm bg-gray-200 dark:bg-gray-700 border-1 shadow-lg w-full max-w-[400px]">
            <div className="flex items-center">
                <SongBanner className="flex-grow" song={currentSong}/>
                <div className="toolbar gap-1">
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
