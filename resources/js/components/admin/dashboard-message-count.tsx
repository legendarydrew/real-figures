import { Mail } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

// https://www.geeksforgeeks.org/create-a-line-chart-using-recharts-in-reactjs/

interface DashboardMessageCountProps {
    message_count: number;
    className?: string;
}

export const DashboardMessageCount: React.FC<DashboardMessageCountProps> = ({ message_count, className }) => {

    return (
        <Link href={route('admin.contact')}
              className={cn('admin-dashboard-count messages', className)}>
            <Mail className="size-10"/>
            <div className="admin-dashboard-count-text">
                <span>{message_count.toLocaleString()}</span>
                <span>unread {message_count === 1 ? 'message' : 'messages'}</span>
            </div>
        </Link>
    );
};
