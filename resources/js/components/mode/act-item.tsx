import { Act } from '@/types';
import { Button } from '@/components/ui/button';
import { Edit, Info, Trash } from 'lucide-react';
import React from 'react';
import { cn } from '@/lib/utils';

/**
 * ActItem
 * A component for the back office to represent an Act. It includes edit and delete buttons.
 */
interface ActItemProps {
    act: Act
    onEdit?: () => void;
    onDelete?: () => void;
}

export const ActItem: React.FC<ActItemProps> = ({ act, onEdit, onDelete, className, ...props }) => {

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

    const itemClasses = (): string => {
        return cn('act-item',
            act.image && 'has-image');
    };

    return (
        <div className={itemClasses()} {...props}>

            {/* Background image. */}
            <div className="act-image size-full">
                {act.image ? (
                    <div className="act-image-bg" style={{ backgroundImage: `url(${act.image}` }}/>
                ) : (
                    <div className="act-image-ph">
                        <svg>
                            <use href="/img/catawol-icon.svg" height="100%" width="100%"></use>
                        </svg>
                    </div>
                )}
            </div>

            {/* Management buttons. */}
            <div className="act-item-toolbar">
                {act.has_profile && (
                    <span className="act-item-toolbar-profile" title="Has a profile.">
                        <Info/>
                    </span>
                )}

                <div className="toolbar ml-auto">
                    <Button variant="secondary" size="icon" className="text-sm"
                            onClick={editHandler}
                            title="Edit Act">
                        <Edit className="size-4"/>
                    </Button>
                    <Button variant="destructive" size="icon" className="text-sm"
                            onClick={deleteHandler}
                            title="Delete Act">
                        <Trash className="size-4"/>
                    </Button>
                </div>

            </div>

            {/* Act name. */}
            <div className="act-item-text">
                {act.name}&nbsp;
                {act.subtitle && (<small>{act.subtitle}</small>)}
            </div>
        </div>);
};
