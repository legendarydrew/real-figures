import { User2Icon } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

interface DashboardSubscriberCountProps {
    subscriber_count: number;
    className?: string;
}

export const DashboardSubscriberCount: React.FC<DashboardSubscriberCountProps> = ({ subscriber_count, className }) => {

    return (
        <Link href={route('admin.subscribers')}
              className={cn('admin-dashboard-count subscribers', className)}>
            <User2Icon className="size-10"/>
            <div className="admin-dashboard-count-text">
                <span>{subscriber_count.toLocaleString()}</span>
                <span>{subscriber_count === 1 ? 'Subscriber' : 'Subscribers'}</span>
            </div>
        </Link>
    );
};
