import { HeartIcon } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

interface DashboardDonationCountProps {
    donation_count: number;
    className?: string;
}

export const DashboardDonationCount: React.FC<DashboardDonationCountProps> = ({ donation_count, className }) => {

    return (
        <Link href={route('admin.donations')}
              className={cn('admin-dashboard-count donations', className)}>
            <HeartIcon className="size-10"/>
            <div className="admin-dashboard-count-text">
                <span>{donation_count.toLocaleString()}</span>
                <span>{donation_count === 1 ? 'Donation' : 'Donations'}</span>
            </div>
        </Link>
    );
};
