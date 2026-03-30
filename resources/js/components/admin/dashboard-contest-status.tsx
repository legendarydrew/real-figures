import { CardContent } from '@/components/ui/card';
import { CountdownTimer } from '@/components/mode/countdown-timer';
import { cn } from '@/lib/utils';
import { Act } from '@/types';
import { ActImage } from '@/components/mode/act-image';
import { Link } from '@inertiajs/react';

interface Props {
    data: {
        status: string
        round?: string;
        countdown?: string;
        acts?: Act[];
    };
    className: string;
}

export const DashboardContestStatus: React.FC<Props> = ({ data, className }) => {

    return (
        <div
            className={cn('bg-blue-800 text-white text-shadow-sm rounded-sm flex flex-col gap-2 justify-center items-start p-8',
                'bg-[url("/img/bg-stage.jpg")] bg-cover bg-center',
                className)}>
            <CardContent>
                {data.status === 'over' && (
                    <h2 className="display-text text-lg">The Contest is over.</h2>
                )}
                {data.status === 'coming-soon' && (
                    <h2 className="display-text text-lg">Contest has not yet started.</h2>
                )}
                {data.round && (<h2 className="display-text text-2xl">{data.round}</h2>)}
                {data.status === 'judgement' && (
                    <Link className="button primary small mb-2" href={route('admin.stages')}>Voting and Judgement</Link>
                )}
                {data.countdown && (
                    <div className="flex gap-1">
                        {data.status === 'countdown' && (<b>begins in</b>)}
                        {data.status === 'active' && (<b>ends in</b>)}
                        <CountdownTimer timestamp={data.countdown} size="small"/>
                    </div>
                )}
                {data.acts && (
                    <div className="flex gap-1 mb-2">
                        {data.acts.map((act) => (<ActImage key={act.slug} act={act} size="12"/>))}
                    </div>
                )}
                <small className="font-semibold">Contest status</small>
            </CardContent>
        </div>
    );
};
