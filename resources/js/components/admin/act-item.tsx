import { Act } from '@/types';
import { Button } from '@/components/ui/button';
import { Edit, PersonStanding, Trash, UserPen } from 'lucide-react';
import React from 'react';
import { cn } from '@/lib/utils';

interface ActItemProps {
    act: Act
    editable?: boolean;
    onEdit?: () => void;
    onDelete?: () => void;
}

export const ActItem: React.FC<ActItemProps> = ({ act, editable, onEdit, onDelete, className, ...props }) => {

    const editHandler = (): void => {
        if (onEdit) {
            onEdit();
        }
    }

    const deleteHandler = (): void => {
        if (onDelete) {
            onDelete();
        }
    }

    const textClasses = (): string => {
        return `flex-grow text-lg leading-none font-bold text-left ${act.image ? 'text-white text-shadow-lg' : ''}`;
    }

    return (
        <div
            className={cn("relative flex rounded-md b-2 aspect-square w-full bg-gray-200 hover:bg-gray-300 items-center flex-col justify-end overflow-hidden select-none", className)}
            {...props}>

            {act.image ? (
                <div className="w-full h-full bg-cover z-0"
                     style={{ backgroundImage: `url("${act.image}")` }}/>
            ) : (
                <div className="w-full h-full z-0 flex items-center justify-center text-gray-500 select-none">
                    <PersonStanding className="h-1/2 w-1/2"/>
                </div>
            )}

            <div className="absolute bottom w-full py-2 px-3 flex justify-between items-center gap-1">
                <span className={textClasses()}>{act.name}</span>
                {act.has_profile && (
                    <span className="bg-green-700 text-white rounded-lg p-1.5 text-sm text-gray-500 text-shadow-lg"
                          title="Has a profile.">
                        <UserPen className="h-3 w-3"/>
                    </span>
                )}
                {editable && (
                    <>
                        <Button variant="secondary" size="icon" className="cursor-pointer"
                                onClick={editHandler}
                                title="Edit Act">
                            <Edit className="h-3 w-3"/>
                        </Button>
                        <Button variant="destructive" size="icon" className="cursor-pointer"
                                onClick={deleteHandler}
                                title="Delete Act">
                            <Trash className="h-3 w-3"/>
                        </Button>
                    </>
                )}
            </div>
        </div>
    );
};
