/**
 * SONG PLAYER CONTEXT
 * Similar to how we have a dialog context for opening dialogs while passing data to them.
 * Originally I had thought of piggybacking the dialog context, but it would mean that the
 * song player would close when other dialogs are opened.
 */
import { createContext, useContext, useMemo, useState } from "react";
import { Round, Song } from '@/types';

const SongPlayerContext = createContext();

export function SongPlayerProvider({ children }) {

    const [isPlayerOpen, setIsPlayerOpen] = useState<boolean>(false);
    const [currentRound, setCurrentRound] = useState<Round>();
    const [currentSong, setCurrentSong] = useState<Song>();

    const openSongPlayer = (round: Round, song: Song): void => {
        setCurrentRound(round);
        setCurrentSong(song);
        setIsPlayerOpen(true);
    };

    const closeSongPlayer = (): void => {
        setIsPlayerOpen(false);
        setCurrentSong(undefined);
        setCurrentRound(undefined);
    };

    const providerValues = useMemo(() => ({
        isPlayerOpen,
        currentRound,
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
