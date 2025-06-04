/**
 * SONG PLAYER CONTEXT
 * Similar to how we have a dialog context for opening dialogs while passing data to them.
 * Originally I had thought of piggybacking the dialog context, but it would mean that the
 * song player would close when other dialogs are opened.
 */
import { createContext, useContext, useMemo, useState } from "react";
import { Song } from '@/types';

const SongPlayerContext = createContext();

export function SongPlayerProvider({ children }) {

    const [isPlayerOpen, setIsPlayerOpen] = useState<boolean>(false);
    const [currentSong, setCurrentSong] = useState<Song>();

    const openSongPlayer = (song: Song): void => {
        setCurrentSong(song);
        setIsPlayerOpen(true);
    };

    const closeSongPlayer = (): void => {
        setIsPlayerOpen(false);
        setCurrentSong(undefined);
    };

    const providerValues = useMemo(() => ({
        isPlayerOpen,
        currentSong,
        openSongPlayer,
        closeSongPlayer
    }), [currentSong]);

    return (
        <SongPlayerContext.Provider value={providerValues}>
            {children}
        </SongPlayerContext.Provider>
    );
}

export function useSongPlayer() {
    // A custom hook for accessing the context.
    return useContext(SongPlayerContext);
}
