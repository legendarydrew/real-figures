import { FC } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Stage } from '@/types';
import { useForm } from '@inertiajs/react';

interface DeleteStageDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
    stage: Stage;
}

export const DeleteStageDialog: FC<DeleteStageDialogProps> = ({ open, onOpenChange, stage }) => {

    const { delete: destroy, processing } = useForm();  // delete is a JS/TS keyword.
    const confirmHandler = () => {

        destroy(route('stages.delete', { id: stage.id }), {
            onSuccess: onOpenChange,
            preserveScroll: true
        });
    };


    return (
        <Dialog open={open} onClose={onOpenChange}>
            <DialogContent aria-describedby={undefined}>
                <DialogTitle>{`Delete Stage "${stage?.title}"`}</DialogTitle>

                <p>
                    <span className="italic">This will also delete the Rounds associated with this Stage.</span><br/>
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
