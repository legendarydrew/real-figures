import { ChangeEvent, FC } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Stage } from '@/types';
import { Toaster } from '@/components/ui/toast-message';
import { LoadingButton } from '@/components/ui/loading-button';

interface StageWinnersDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
    stage?: Stage;
}

type StageWinnersForm = {
    runners_up: number;
}

export const StageWinnersDialog: FC<StageWinnersDialogProps> = ({ open, onOpenChange, stage }) => {
    const { data, setData, post, errors, setError, processing } = useForm<Required<StageWinnersForm>>({
        runners_up: 1
    });

    const changeRunnersUpHandler = (e: ChangeEvent) => {
        // We are using setData from useForm(), so we don't have to create separate states.
        setData('runners_up', e.target.value);
        setError('runners_up', '');
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        post(route('stages.winners', { id: stage.id }), {
            onSuccess: () => {
                Toaster.success(`Winning Songs for Stage "${stage.title}" were confirmed.`);
                onOpenChange();
            }
        });
    };


    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent>
                <DialogTitle>Confirm Stage Winners</DialogTitle>
                <DialogDescription>
                    This will confirm the winning Songs in each Round of this Stage.<br/>
                    You can optionally choose the highest-scoring runners-up across all Rounds.
                </DialogDescription>
                <form onSubmit={saveHandler}>
                    <div className="mb-3 flex justify-between items-center">
                        <Label htmlFor="chooseRunnersUp">Number of runners-up</Label>
                        <Input id="chooseRunnersUp" type="number" className="font-bold text-right w-[6em]"
                               value={data.runners_up}
                               min="1" max="10" onChange={changeRunnersUpHandler}/>
                        <InputError className="mt-2" message={errors.runners_up}/>
                    </div>

                    <DialogFooter>
                        <LoadingButton variant="default" type="submit" onClick={saveHandler}
                                       isLoading={processing}>Confirm</LoadingButton>
                        <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
