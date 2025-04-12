import { ChangeEvent, FC } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import Heading from '@/components/heading';
import { Textarea } from '@/components/ui/textarea';
import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';

/**
 * STAGE admin page
 *
 * This was the first admin page I developed with this starter kit.
 * As usual, there was absolutely no documentation included with the generated code -
 * however I was able to use and adapt what I picked up in my hive-task project.
 */

interface StageDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
    stageId?: number;
}

type StageForm = {
    // The structure of the form data.
    title: string;
    description?: string;
}

export const StageDialog: FC<StageDialogProps> = ({ open, onOpenChange, stageId }) => {
    // const { auth } = usePage<SharedData>().props;

    const { data, setData, post, patch, errors, processing } = useForm<Required<StageForm>>({
        title: '',
        description: ''
    })

    const changeTitleHandler = (e: ChangeEvent) => {
        // We are using setData from useForm(), so we don't have to create separate states.
        setData('title', e.target.value);
    };

    const changeDescriptionHandler = (e: ChangeEvent) => {
        setData('description', e.currentTarget.value);
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        console.log('Save!', data);

        if (stageId) {
            // Updating an existing Stage.
            // (Getting into the habit of using patch instead of put: the latter implies that
            // the whole row is being updated.)
            patch(route('api.stage.update'), {
                onSuccess: onOpenChange,
                preserveScroll: true
            });
        } else {
            // Creating a new Stage.
            post(route('api.stage.create'), {
                onSuccess: onOpenChange,
                preserveScroll: true
            });
        }
    };


    return (
        <Dialog open={open} onClose={onOpenChange}>
            <DialogTitle>{stageId ? 'Update' : 'Create'} Stage</DialogTitle>
            <DialogContent aria-describedby={undefined}>
                <form onSubmit={saveHandler}>
                    <Heading title={stageId ? 'Update Stage' : 'Create Stage'}/>

                    <div className="mb-2">
                        <Label htmlFor="stageName">Stage name</Label>
                        <Input id="stageName" type="text" className="font-bold" value={data.title}
                               onChange={changeTitleHandler}/>
                        <InputError className="mt-2" message={errors.title}/>
                    </div>

                    <div>
                        <Label htmlFor="stageDescription">Description</Label>
                        <Textarea id="stageDescription" rows="4" value={data.description}
                                  onChange={changeDescriptionHandler}/>
                        <InputError className="mt-2" message={errors.description}/>
                    </div>

                    <DialogFooter>
                        <Button variant="ghost" onClick={onOpenChange}>Cancel</Button>
                        <Button variant="default" type="submit" onClick={saveHandler}
                                disabled={processing}>Save</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
