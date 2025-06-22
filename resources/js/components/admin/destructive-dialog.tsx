import React, { FC } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { LoadingButton } from '@/components/mode/loading-button';

interface DestructiveDialogProps {
    // Dialog properties.
    open: boolean;
    title?: string;
    processing: boolean;
    onOpenChange: () => void;
    onConfirm: () => void;
}

/**
 * Instead of reinventing the wheel (although it might be useful), here's a generic component
 * for displaying a confirmation dialog when we want to delete something.
 */
export const DestructiveDialog: FC<DestructiveDialogProps> = ({
                                                                  open,
                                                                  onOpenChange,
                                                                  onConfirm,
                                                                  processing,
                                                                  title,
                                                                  children
                                                              }) => {

    // Using slots to organise dialog content.
    // We can include a DialogTitle component within the component to set the title,
    // or pass a title property instead.
    // https://dev.to/neetigyachahar/what-is-the-react-slots-pattern-2ld9
    const titleElement = React.Children.toArray(children).find(child => child.type === DialogTitle);
    const otherChildElements = React.Children.toArray(children).filter((child) => child.type !== DialogTitle);

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent aria-describedby={undefined}>

                {titleElement ?? <DialogTitle>{title}</DialogTitle>}

                <div>
                    {otherChildElements}
                </div>

                <DialogFooter>
                    <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                    <LoadingButton variant="destructive" type="submit" onClick={onConfirm}
                                   isLoading={processing}>Yes</LoadingButton>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    )
}
