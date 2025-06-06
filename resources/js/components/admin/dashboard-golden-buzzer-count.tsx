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
              className={cn('bg-amber-200 hover:bg-amber-300 dark:bg-amber-700 dark:hover:bg-amber-600 rounded-sm p-3 flex items-center gap-5 select-none', className)}>
            <StarIcon className="w-10 h-10"/>
            <div className="flex-grow flex flex-col gap-0 display-text">
                <span className="text-3xl leading-none">{buzzer_count.toLocaleString()}</span>
                <span className="text-sm">{buzzer_count === 1 ? 'Golden Buzzer' : 'Golden Buzzers'}</span>
            </div>
        </Link>
    );
};
