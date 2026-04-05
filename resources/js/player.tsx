import { createRoot } from 'react-dom/client';
import { SongPlayer } from '@/components/front/song-player';

const playerElement = document.getElementById('song-player');
const player = playerElement.querySelector('.song-player-video');
const playerRoot = createRoot(player);
let playlistID: number = 0;
let playlistIndex: number = 0;

playerRoot.render(<SongPlayer/>);

function playSong(song: any) {
    playerElement.dataset.songId = song.id;
    playerElement.dataset.actSlug = song.act.slug;
    playerElement.querySelector('.song-player-banner-flag').classList = `song-player-banner-flag flag flag:${song.language.flag}`;
    playerElement.querySelector('.song-player-banner-act').textContent = song.act.name;
    playerElement.querySelector('.song-player-banner-title').textContent = song.title;
    if (song.act.subtitle) {
        const subtitle = document.createElement('small');
        subtitle.innerText = song.act.subtitle;
        playerElement.querySelector('.song-player-banner-act')?.append(subtitle);
    }

    playerRoot.render(<SongPlayer currentSong={song}/>);
}

globalThis.openSongPlayer = (el: HTMLElement): void => {
    const song = JSON.parse(el.dataset.song);

    // Show or hide playlist buttons, based on if there's playlist data available.
    playlistID = Number.parseInt(el.dataset.round);
    playerElement.querySelector('.song-player-banner-playlist').classList.toggle('hidden', Number.isNaN(playlistID));
    playlistIndex = globalThis.playlist[playlistID]?.findIndex((s) => s.id === song.id) ?? 0;

    playSong(song);

    playerElement?.showPopover();

    globalThis.trackEvent("dialog_open", {
        type: 'song',
        act: song.act.slug
    });
}

globalThis.prevInSongPlayer = (): void => {
    if (Number.isNaN(playlistID)) {
        return;
    }
    playlistIndex = (playlistIndex - 1) % globalThis.playlist[playlistID].length;
    playSong(globalThis.playlist[playlistID][playlistIndex]);
    globalThis.trackEvent("playlist", {
        round_id: playlistID,
        label: 'prev'
    });
}

globalThis.nextInSongPlayer = (): void => {
    if (Number.isNaN(playlistID)) {
        return;
    }
    playlistIndex = (playlistIndex + 1) % globalThis.playlist[playlistID].length;
    playSong(globalThis.playlist[playlistID][playlistIndex]);
    globalThis.trackEvent("playlist", {
        round_id: playlistID,
        label: 'next'
    });
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
    globalThis.trackEvent("dialog_open", {
        type: 'golden_buzzer',
        act: actSlug
    });
}
