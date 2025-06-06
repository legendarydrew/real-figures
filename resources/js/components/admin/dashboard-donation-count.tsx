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
              className={cn('bg-green-200 hover:bg-green-300 dark:bg-green-700 dark:hover:bg-green-600 rounded-sm p-3 flex items-center gap-5 select-none', className)}>
            <HeartIcon className="w-10 h-10"/>
            <div className="flex-grow flex flex-col gap-0 display-text">
                <span className="text-3xl leading-none">{donation_count.toLocaleString()}</span>
                <span className="text-sm">{donation_count === 1 ? 'Donation' : 'Donations'}</span>
            </div>
        </Link>
    );
};
