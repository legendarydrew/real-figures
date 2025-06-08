import { Mail } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

// https://www.geeksforgeeks.org/create-a-line-chart-using-recharts-in-reactjs/

interface DashboardMessageCountProps {
    message_count: number;
    className?: string;
}

export const DashboardMessageCount: React.FC<DashboardMessageCountProps> = ({ message_count, className }) => {

    return message_count ? (
        <Link href={route('admin.contact')}
              className={cn('bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-sm p-3 flex items-center gap-5 select-none', className)}>
            <Mail className="w-10 h-10"/>
            <div className="flex-grow flex flex-col gap-0 display-text">
                <span className="text-3xl leading-none">{message_count.toLocaleString()}</span>
                <span className="text-sm">unread {message_count === 1 ? 'message' : 'messages'}</span>
            </div>
        </Link>
    ) : '';
};
