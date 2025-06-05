import { User2Icon } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

// https://www.geeksforgeeks.org/create-a-line-chart-using-recharts-in-reactjs/

interface DashboardMessageCountProps {
    subscriber_count: number;
    className?: string;
}

export const DashboardSubscriberCount: React.FC<DashboardMessageCountProps> = ({ subscriber_count, className }) => {

    return (
        <Link href={route('admin.subscribers')}
              className={cn('bg-indigo-200 hover:bg-indigo-300 dark:bg-indigo-700 dark:hover:bg-indigo-600 rounded-sm p-3 flex items-center gap-5 select-none', className)}>
            <User2Icon className="w-10 h-10"/>
            <div className="flex-grow flex flex-col gap-0 display-text">
                <span className="text-3xl leading-none">{subscriber_count.toLocaleString()}</span>
                <span className="text-sm">{subscriber_count === 1 ? 'Subscriber' : 'Subscribers'}</span>
            </div>
        </Link>
    );
};
