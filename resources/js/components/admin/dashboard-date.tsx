import { cn } from '@/lib/utils';
import { useEffect, useState } from 'react';

export const DashboardDate: React.FC<{ className: string }> = ({ className }) => {

    const locale = 'en';
    const [today, setToday] = useState(new Date()); // Save the current date to be able to trigger an update

    useEffect(() => {
        const timer = setInterval(() => {
            // Creates an interval which will update the current data every minute
            // This will trigger a rerender every component that uses the useDate hook.
            setToday(new Date());
        }, 60 * 1000);
        return () => {
            clearInterval(timer);
        }
    }, []);

    const date = [
        today.toLocaleDateString(locale, { weekday: 'long' }),
        today.getDate(),
        today.toLocaleDateString(locale, { month: 'long' }),
        today.getFullYear()
    ].join(' ');

    const time = today.toLocaleTimeString(locale, { hour: 'numeric', hour12: false, minute: '2-digit' });

    return (
        <div className={cn('display-text text-xl flex items-center p-4', className)}>
            {date}, {time}
        </div>
    );
};
