import React, { useEffect, useRef, useState } from 'react';

interface CountdownTimerProps {
    timestamp: string;
}

export const CountdownTimer: React.FC<CountdownTimerProps> = ({ timestamp }) => {

    const [counter, setCounter] = useState({ days: 0, hours: 0, mins: 0, secs: 0 });

    useEffect(() => updateTimer(), [timestamp]);

    const countdownInterval = useRef(null);

    const updateTimer = () => {
        const now = new Date().getTime();
        const time = new Date(timestamp).getTime();
        const seconds = Math.max(0, Math.floor((time - now) / 1000));

        setCounter({
            days: Math.floor(seconds / 86400),
            hours: Math.floor(seconds / 3600) % 24,
            mins: Math.floor(seconds / 60) % 60,
            secs: seconds % 60
        });
    };

    const formatDigits = (value: number): string => {
        return ('00' + value.toString()).slice(-2);
    }

    // When this component mounts, update the countdown timer every half second (for more accuracy).
    useEffect(() => {
        countdownInterval.current = setInterval(updateTimer, 500);

        // this function runs when the component is unmounted.
        return () => {
            // Your code here
            clearInterval(countdownInterval.current);
        }
    }, []); // empty array = execute on mount.

    return (
        <div className="flex justify-center">
            <span>{formatDigits(counter.days)}:{formatDigits(counter.hours)}:{formatDigits(counter.mins)}:{formatDigits(counter.secs)}</span>
        </div>
    )
};
