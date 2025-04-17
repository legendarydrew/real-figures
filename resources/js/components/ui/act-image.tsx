import { Act } from '@/types';
import { PersonStanding } from 'lucide-react';
import React from 'react';
import { cn } from '@/lib/utils';

interface ActImageProps {
    act: Act;
    size?: string;
    className?: string;
}

export const ActImage: React.FC<ActImageProps> = ({ act, size = '10', className }) => {

    return (
        <div className={cn(`w-${size} h-${size}`, className)}>
            {act?.image ? (
                <div className="w-full h-full bg-cover z-0"
                     style={{ backgroundImage: `url("${act.image}")` }}/>
            ) : (
                <div className="w-full h-full z-0 flex items-center justify-center text-gray-500 select-none">
                    <PersonStanding className="h-1/2 w-1/2"/>
                </div>
            )}
        </div>
    );
}
