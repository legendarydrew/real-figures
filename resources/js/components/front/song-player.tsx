/**
 * SONG PLAYER component
 * This component will be used to play a YouTube video representing a Song.
 */
import YouTube from 'react-youtube';
import axios from 'axios';
import React from 'react';

export const SongPlayer: React.FC = ({ currentSong }) => {

    const playerOptions = {
        playerVars: {
            // https://developers.google.com/youtube/player_parameters
            autoplay: 1,
            color: 'white',
            rel: 0,  // only show related videos from the same channel.
            widget_referrer: import.meta.env.VITE_APP_URL
        }
    };

    const songPlayHandler = (e) => {
        // Called when the YouTube video is played.
        // We will only record a play if the video is at (or near) the beginning.
        if (e.target.getCurrentTime() <= 0.5) {
            axios.put(`/api/songs/${currentSong.id}/play`).then();
        }
    };

    return (currentSong?.video_id ?
        <YouTube
            videoId={currentSong.video_id}
            iframeClassName="w-full max-h-360 h-[40dvh]"
            title={`YouTube video player: ${currentSong.title} by ${currentSong.act.name}`}
            opts={playerOptions}
            onPlay={songPlayHandler}
        /> : '');
};
