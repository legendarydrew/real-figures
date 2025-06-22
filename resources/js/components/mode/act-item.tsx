import { Act } from '@/types';
import { Button } from '@/components/ui/button';
import { Edit, Info, PersonStanding, Trash } from 'lucide-react';
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
        return `display-text flex-grow text-lg leading-none text-left ${act.image ? 'text-white outlined-text' : ''} ${editable && 'truncate'}`;
    }

    return (
        <div
            className={cn("relative flex rounded-md b-2 aspect-square w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 items-center flex-col justify-end overflow-hidden select-none", className)}
            {...props}>

            {act.image ? (
                <div className="w-full h-full bg-act-image z-0">
                    <div className="bg-cover w-full h-full" style={{ backgroundImage: `url(${act.image}`}}></div>
                </div>
            ) : (
                <div className="w-full h-full z-0 flex items-center justify-center text-gray-500 select-none">
                    <PersonStanding className="h-1/2 w-1/2"/>
                </div>
            )}

            {editable && (
                <div className="absolute top-2 right-2 toolbar">
                    <Button variant="secondary" size="icon" className="cursor-pointer"
                            onClick={editHandler}
                            title="Edit Act">
                        <Edit/>
                    </Button>
                    <Button variant="destructive" size="icon" className="cursor-pointer"
                            onClick={deleteHandler}
                            title="Delete Act">
                        <Trash/>
                    </Button>
                </div>
            )}

            <div
                className={cn("absolute bottom w-full flex justify-between items-center px-3 py-2")}>
                <span className={textClasses()}>{act.name}</span>
                {act.has_profile && (
                    <span className="bg-blue-700 text-white rounded-full text-shadow-lg"
                          title="Has a profile.">
                        <Info className="h-5 w-5"/>
                    </span>
                )}
            </div>
        </div>
    );
};
