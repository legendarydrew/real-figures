import React, { useEffect, useRef, useState } from 'react';

interface CountdownTimerProps {
    timestamp: string;
    size: 'small' | 'large';
    warnAt?: string; // in the form of [days]:[hours]:[minutes]:[seconds].
    onEnd: () => void;
}

export const CountdownTimer: React.FC<CountdownTimerProps> = ({
                                                                  timestamp, size = 'small', warnAt, onEnd = () => {
    }
                                                              }) => {

    const [counter, setCounter] = useState({ days: 0, hours: 0, mins: 0, secs: 0 });
    const [warning, setWarning] = useState<boolean>(false);

    let warnSeconds: number = 3600;  // one hour.

    useEffect(() => updateTimer(), [timestamp]);
    useEffect(() => updateWarnTime(), [warnAt]);

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

        setWarning(seconds <= warnSeconds);

        if (seconds <= 0) {
            onEnd();
        }
    };

    const updateWarnTime = () => {
        const parts = warnAt?.split(":").map((v) => parseInt(v, 10)).reverse();
        if (!parts || parts.includes(NaN)) {
            warnSeconds = 3600;
        } else {
            warnSeconds = parts[0] + (parts[1] ? parts[1] * 60 : 0) + (parts[2] ? parts[2] * 3600 : 0) + (parts[3] ? parts[3] * 86400 : 0);
        }

    };

    const formatDigits = (value: number): string => {
        return ('00' + value.toString()).slice(-2);
    }

    const smallWarningClasses = (): string => {
        return warning ? ' text-destructive-foreground text-shadow-sm' : '';
    }

    const largeWarningClasses = (): string => {
        return warning ? ' text-green-200 text-shadow-sm' : ' text-white';
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
        <div
            className="flex justify-center font-semibold items-center gap-1 text-xl text-center leading-none">
            {size.toLowerCase() === 'large' ? (
                <>
                    <div
                        className={`flex flex-col gap-0.5 leading-none w-18 p-2 bg-gray-800 ${largeWarningClasses} rounded-sm text-center`}>
                        <span className="text-3xl">{formatDigits(counter.days)}</span>
                        <span className="text-xs">days</span>
                    </div>
                    <div
                        className={`flex flex-col gap-0.5 leading-none w-18 p-2 bg-gray-800 ${largeWarningClasses} rounded-sm text-center`}>
                        <span className="text-3xl">{formatDigits(counter.hours)}</span>
                        <span className="text-xs">hours</span>
                    </div>
                    <div
                        className={`flex flex-col gap-0.5 leading-none w-18 p-2 bg-gray-800 ${largeWarningClasses} rounded-sm text-center`}>
                        <span className="text-3xl">{formatDigits(counter.mins)}</span>
                        <span className="text-xs">minutes</span>
                    </div>
                    <div
                        className={`flex flex-col gap-0.5 leading-none w-18 p-2 bg-gray-800 ${largeWarningClasses} rounded-sm text-center`}>
                        <span className="text-3xl">{formatDigits(counter.secs)}</span>
                        <span className="text-xs">seconds</span>
                    </div>
                </>
            ) : (
                <>
                    <span className={`w-6 ${smallWarningClasses}`}>{formatDigits(counter.days)}</span>
                    <span>:</span>
                    <span className={`w-6 ${smallWarningClasses}`}>{formatDigits(counter.hours)}</span>
                    <span>:</span>
                    <span className={`w-6 ${smallWarningClasses}`}>{formatDigits(counter.mins)}</span>
                    <span>:</span>
                    <span className={`w-6 ${smallWarningClasses}`}>{formatDigits(counter.secs)}</span>
                </>
            )}
        </div>
    )
};
