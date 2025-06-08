import { VoteIcon } from 'lucide-react';
import { Link, usePage } from '@inertiajs/react';
import { cn } from '@/lib/utils';
import { StageStatusTag } from '@/components/ui/stage-status-tag';

// https://www.geeksforgeeks.org/create-a-line-chart-using-recharts-in-reactjs/

interface DashboardVoteCountProps {
    vote_count?: number;
    className?: string;
}

export const DashboardVoteCount: React.FC<DashboardVoteCountProps> = ({ vote_count, className }) => {

    const { currentStage } = usePage().props;

    return currentStage ? (
        <Link href={route('admin.stages')}
            className={cn("flex justify-between display-text bg-gray-500 hover:bg-gray-600 text-light rounded-sm overflow-hidden", className)}>

            {/* Stage details. */}
            <div className="flex flex-col p-3 w-full">
                { currentStage.title }<br />
                <div className="flex justify-between items-center text-sm">
                    Current Stage
                    <StageStatusTag stage={currentStage} />
                </div>
            </div>

            {/* Number of votes. */}
            <div
                className={cn("flex-shrink-0 bg-gray-700 text-light dark:bg-gray-200 dark:text-dark p-3 flex items-center gap-5 select-none", className)}>
                <VoteIcon className="w-10 h-10"/>
                <div className="flex-grow flex flex-col gap-0">
                    <span className="text-3xl leading-none">{vote_count.toLocaleString()}</span>
                    <span className="text-sm">{vote_count === 1 ? 'vote' : 'votes'} cast</span>
                </div>
            </div>
        </Link>
            ) : '';
            };
