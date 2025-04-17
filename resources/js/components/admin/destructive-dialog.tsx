import { FC } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/react';

interface DestructiveDialogProps {
    // Dialog properties.
    open: boolean;
    title?: string; // TODO use a slot that takes DialogTitle.
    onOpenChange: () => void;
    onConfirm: () => void;
}

/**
 * Instead of reinventing the wheel (although it might be useful), here's a generic component
 * for displaying a confirmation dialog when we want to delete something.
 */
export const DestructiveDialog: FC<DestructiveDialogProps> = ({ open, onOpenChange, onConfirm, title, children }) => {

    const { processing } = useForm();

    return (
        <Dialog open={open} onClose={onOpenChange}>
            <DialogContent aria-describedby={undefined}>

                <DialogTitle>{title}</DialogTitle>

                <div>
                    {children}
                </div>

                <DialogFooter>
                    <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                    <Button variant="destructive" type="submit" onClick={onConfirm}
                            disabled={processing}>Yes</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    )
}
