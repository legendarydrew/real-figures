import React, { RefObject, useEffect, useRef, useState } from 'react';
import { cn } from '@/lib/utils';

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

    const countdownInterval: RefObject<number | null> = useRef(null);

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
            clearInterval(countdownInterval.current);
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

    const warningClass: string = warning ? 'warning' : '';

    // When this component mounts, update the countdown timer every half second (for more accuracy).
    useEffect(() => {
        countdownInterval.current = setInterval(updateTimer, 500);

        // this function runs when the component is unmounted.
        return () => {
            clearInterval(countdownInterval.current);
        }
    }, []); // empty array = execute on mount.

    return (
        <div className="countdown-timer">
            {size.toLowerCase() === 'large' ? (
                <>
                    <div className={cn('large-digit', warningClass)}>
                        <span className="digit">{formatDigits(counter.days)}</span>
                        <span className="unit">days</span>
                    </div>
                    <div className={cn('large-digit', warningClass)}>
                        <span className="digit">{formatDigits(counter.hours)}</span>
                        <span className="unit">hours</span>
                    </div>
                    <div className={cn('large-digit', warningClass)}>
                        <span className="digit">{formatDigits(counter.mins)}</span>
                        <span className="unit">minutes</span>
                    </div>
                    <div className={cn('large-digit', warningClass)}>
                        <span className="digit">{formatDigits(counter.secs)}</span>
                        <span className="unit">seconds</span>
                    </div>
                </>
            ) : (
                <>
                    <span className={cn('small-digit', warningClass)}>{formatDigits(counter.days)}</span>
                    <span>:</span>
                    <span className={cn('small-digit', warningClass)}>{formatDigits(counter.hours)}</span>
                    <span>:</span>
                    <span className={cn('small-digit', warningClass)}>{formatDigits(counter.mins)}</span>
                    <span>:</span>
                    <span className={cn('small-digit', warningClass)}>{formatDigits(counter.secs)}</span>
                </>
            )}
        </div>
    )
};
