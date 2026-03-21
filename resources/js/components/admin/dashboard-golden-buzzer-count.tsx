import { StarIcon } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

interface DashboardGoldenBuzzerCountProps {
    buzzer_count: number;
    className?: string;
}

export const DashboardGoldenBuzzerCount: React.FC<DashboardGoldenBuzzerCountProps> = ({ buzzer_count, className }) => {

    return (
        <Link href={route('admin.golden-buzzers')}
              className={cn('admin-dashboard-count golden-buzzer', className)}>
            <StarIcon className="size-10"/>
            <div className="admin-dashboard-count-text">
                <span>{buzzer_count.toLocaleString()}</span>
                <span>{buzzer_count === 1 ? 'Golden Buzzer' : 'Golden Buzzers'}</span>
            </div>
        </Link>
    );
};
