import { FC } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { LoadingButton } from '@/components/ui/loading-button';

interface DestructiveDialogProps {
    // Dialog properties.
    open: boolean;
    title?: string; // TODO use a slot that takes DialogTitle.
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

    return (
        <Dialog open={open} onClose={onOpenChange}>
            <DialogContent aria-describedby={undefined}>

                <DialogTitle>{title}</DialogTitle>

                <div>
                    {children}
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
