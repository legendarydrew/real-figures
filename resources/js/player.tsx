import { createRoot } from 'react-dom/client';
import { SongPlayer } from '@/components/front/song-player';

const playerElement = document.getElementById('song-player');
const player = playerElement.querySelector('.song-player-video');
const playerRoot = createRoot(player);
playerRoot.render(<SongPlayer/>);

globalThis.openSongPlayer = (el: HTMLElement): void => {
    const song = JSON.parse(el.dataset.song);
    playerElement.dataset.songId = song.id;
    playerElement.dataset.actSlug = song.act.slug;
    playerElement.querySelector('.song-player-banner-flag').classList = `song-player-banner-flag flag flag:${song.language.flag}`;
    playerElement.querySelector('.song-player-banner-act').textContent = song.act.name;
    playerElement.querySelector('.song-player-banner-title').textContent = song.title;

    playerRoot.render(<SongPlayer currentSong={song}/>);

    playerElement?.showPopover();

    globalThis.trackEvent({ action: 'Open song player', label: song.act.slug, nonInteraction: false });
}

globalThis.closeSongPlayer = (): void => {
    playerElement?.hidePopover();
    playerRoot.render(<SongPlayer/>); // stop the video

    playerElement.dataset.songId = '';
    playerElement.querySelector('.song-player-banner-flag').classList = 'song-player-banner-flag flag';
    playerElement.querySelector('.song-player-banner-act').textContent = '';
    playerElement.querySelector('.song-player-banner-title').textContent = '';
}

globalThis.awardGoldenBuzzer = (): void => {
    const dialogId = `golden-buzzer-dialog-${playerElement.dataset.songId}`;
    const actSlug = `golden-buzzer-dialog-${playerElement.dataset.actSlug}`;
    document.getElementById(dialogId)?.showModal();
    globalThis.trackEvent({ action: 'Begin Golden Buzzer', label: actSlug, nonInteraction: false });
}
