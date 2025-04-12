import { ChangeEvent, FC, useEffect } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Stage } from '@/types';

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
    stage?: Stage;
}

type StageForm = {
    // The structure of the form data.
    title: string;
    description: string;
}

export const StageDialog: FC<StageDialogProps> = ({ open, onOpenChange, stage }) => {
    // useForm() provides some very useful methods.
    const { data, setData, post, patch, errors, setError, processing } = useForm<Required<StageForm>>({
        title: '',
        description: ''
    });

    useEffect(() => {
        if (isEditing()) {
            // We're editing a Stage, so populate the form.
            setData({
                title: stage?.title,
                description: stage?.description
            });
        }
    }, [stage]);

    const isEditing = (): boolean => {
        return !!stage?.id;
    }

    const changeTitleHandler = (e: ChangeEvent) => {
        // We are using setData from useForm(), so we don't have to create separate states.
        setData('title', e.target.value);
        setError('title', '');
    };

    const changeDescriptionHandler = (e: ChangeEvent) => {
        setData('description', e.currentTarget.value);
        setError('description', '');
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        if (isEditing()) {
            // Updating an existing Stage.
            // (Getting into the habit of using patch instead of put: the latter implies that
            // the whole row is being updated.)
            patch(route('stages.update', { id: stage.id }), {
                onSuccess: onOpenChange,
                preserveScroll: true
            });
        } else {
            // Creating a new Stage.
            post(route('stages.create'), {
                onSuccess: onOpenChange,
                preserveScroll: true
            });
        }
        // NOTE: to use route(), the endpoints have to be named in the route definitions.
        // We can probably use url() as well.
    };


    return (
        <Dialog open={open} onClose={onOpenChange}>
            <DialogContent aria-describedby={undefined}>
                <DialogTitle>{isEditing() ? 'Update' : 'Create'} Stage</DialogTitle>
                <form onSubmit={saveHandler}>
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
                        <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                        <Button variant="default" type="submit" onClick={saveHandler}
                                disabled={processing}>Save</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
