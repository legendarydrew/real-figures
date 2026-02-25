const playerElement = document.getElementById('song-player');

globalThis.openSongPlayer = (el: HTMLElement): void => {
    const song = JSON.parse(el.dataset.song);
    playerElement.querySelector('.song-player-banner-flag').classList = `song-player-banner-flag flag flag:${song.language.flag}`
    playerElement.querySelector('.song-player-banner-act').textContent = song.act.name;
    playerElement.querySelector('.song-player-banner-title').textContent = song.title;
    playerElement?.showPopover();
}

globalThis.closeSongPlayer = (): void => {
    playerElement?.hidePopover();
}
