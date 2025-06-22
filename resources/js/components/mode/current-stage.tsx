import Heading from '@/components/heading';
import { CountdownTimer } from '@/components/mode/countdown-timer';
import { router } from '@inertiajs/react';
import { Round, Stage } from '@/types';

interface CurrentStageProps {
    stage: Stage;
    round: Round;
    countdown: string;
}

export const CurrentStage: React.FC<CurrentStageProps> = ({ stage, round, countdown }) => {
    const countdownEndHandler = () => {
        router.reload();
    }

    return stage && (
        <div className="grid grid-cols-1 md:grid-cols-4 gap-3 md:gap-y-0">
            <div className="col-span-1 md:col-span-3 col-start-1 row-start-1 text-center md:text-left">
                <Heading title={round.full_title}/>
            </div>
            {stage.description && (
                <div className="content my-3 text-sm text-white/95 col-span-1 md:col-span-3 col-start-1 text-center md:text-left md:row-start-2"
                     dangerouslySetInnerHTML={{ __html: stage.description }}/>
            )}
            <div className="col-span-1 text-center col-start-1 md:col-start-4 row-start-2 md:row-start-1 md:row-span-2">
                <span className="text-sm">Voting ends in</span>
                <CountdownTimer timestamp={countdown} onEnd={countdownEndHandler}/>
            </div>
        </div>
    )
};
