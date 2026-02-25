import { createRoot } from 'react-dom/client';
import { SongPlayer } from '@/components/front/song-player';

const playerElement = document.getElementById('song-player');
const player = playerElement.querySelector('.song-player-video');
const playerRoot = createRoot(player);
playerRoot.render(<SongPlayer/>);

globalThis.openSongPlayer = (el: HTMLElement): void => {
    const song = JSON.parse(el.dataset.song);
    playerElement.querySelector('.song-player-banner-flag').classList = `song-player-banner-flag flag flag:${song.language.flag}`;
    playerElement.querySelector('.song-player-banner-act').textContent = song.act.name;
    playerElement.querySelector('.song-player-banner-title').textContent = song.title;

    playerRoot.render(<SongPlayer currentSong={song}/>);

    playerElement?.showPopover();
}

globalThis.closeSongPlayer = (): void => {
    playerElement?.hidePopover();
    playerRoot.render(<SongPlayer/>); // stop the video

    playerElement.querySelector('.song-player-banner-flag').classList = 'song-player-banner-flag flag';
    playerElement.querySelector('.song-player-banner-act').textContent = '';
    playerElement.querySelector('.song-player-banner-title').textContent = '';
}
