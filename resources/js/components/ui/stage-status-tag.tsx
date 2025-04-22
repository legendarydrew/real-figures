import { Stage } from '@/types';

/**
 * A very simple component for displaying the status of a Stage.
 */

interface StageStatusTagProps {
    stage: Stage;
}

export const StageStatusTag: React.FC<StageStatusTagProps> = ({ stage }) => {

    const isActive = (): boolean => {
        return stage.rounds?.length > 0;
    }

    const hasEnded = (): boolean => {
        return !!(stage.status?.has_started && stage.status?.has_ended);
    }

    const hasStarted = (): boolean => {
        return !!stage.status?.has_started;
    }

    const tagClasses = (): string => {
        let baseClasses = "text-xs rounded-lg py-1 px-2"
        if (isActive()) {
            if (hasEnded()) {
                baseClasses += " bg-blue-500 text-white";
            } else if (hasStarted()) {
                baseClasses += " bg-green-500 text-white";
            } else {
                baseClasses += " bg-orange-500 text-white";
            }
        } else {
            baseClasses += " bg-gray-500 text-white";
        }
        return baseClasses;
    }

    return (<span className={tagClasses()}>{stage.status.text}</span>);
};
