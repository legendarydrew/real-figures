import { FC } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Act } from '@/types';
import { useForm } from '@inertiajs/react';
import { Toaster } from '@/components/ui/toast-message';

interface DeleteActDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
    act: Act;
}

export const DeleteActDialog: FC<DeleteActDialogProps> = ({ open, onOpenChange, act }) => {

    const { delete: destroy, processing } = useForm();
    const confirmHandler = () => {

        destroy(route('acts.destroy', { id: act.id }), {
            onSuccess: () => {
                Toaster.success(`"${act.name}" was deleted.`);
                onOpenChange();
            },
            preserveScroll: true
        });
    };


    return (
        <Dialog open={open} onClose={onOpenChange}>
            <DialogContent aria-describedby={undefined}>
                <DialogTitle>{`Delete Act "${act?.name}"`}</DialogTitle>

                <p>
                    <span className="italic">This will also delete Songs associated with this Act,
                        and remove them from existing Rounds.</span><br/>
                    Are you sure you want to do this?
                </p>

                <DialogFooter>
                    <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                    <Button variant="destructive" type="submit" onClick={confirmHandler}
                            disabled={processing}>Yes</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    )
}
