import { FC } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Song } from '@/types';
import { useForm } from '@inertiajs/react';
import { Toaster } from '@/components/ui/toast-message';

interface DeleteSongDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
    song: Song;
}

export const DeleteSongDialog: FC<DeleteSongDialogProps> = ({ open, onOpenChange, song }) => {

    const { delete: destroy, processing } = useForm();
    const confirmHandler = () => {

        destroy(route('songs.destroy', { id: song.id }), {
            onSuccess: () => {
                Toaster.success("Song was deleted.");
                onOpenChange();
            },
            preserveScroll: true
        });
    };


    return (
        <Dialog open={open} onClose={onOpenChange}>
            <DialogContent aria-describedby={undefined}>
                <DialogTitle>{`Delete Song "${song?.title}" by ${song?.act.name}`}</DialogTitle>

                <p>
                    <span className="italic">This will remove the Song from all Rounds.</span><br/>
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
