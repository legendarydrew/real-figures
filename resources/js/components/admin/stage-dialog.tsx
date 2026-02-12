import { ChangeEvent, FC, useEffect } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Stage } from '@/types';
import { RTToast } from '@/components/mode/toast-message';
import { MarkdownEditor } from '@/components/mode/markdown-editor';
import { LoadingButton } from '@/components/mode/loading-button';

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
    golden_buzzer_perks?: string;
}

export const StageDialog: FC<StageDialogProps> = ({ open, onOpenChange, stage }) => {
    // useForm() provides some very useful methods.
    const { data, setData, post, patch, errors, setError, processing } = useForm<Required<StageForm>>({
        title: '',
        description: '',
        golden_buzzer_perks: ''
    });

    useEffect(() => {
        // We're editing a Stage, so populate the form.
        setData({
            title: isEditing() ? stage?.title : '',
            description: isEditing() ? stage?.description : '',
            golden_buzzer_perks: isEditing() ? stage?.golden_buzzer_perks : ''
        });
    }, [stage]);

    const isEditing = (): boolean => {
        return !!stage?.id;
    }

    const changeTitleHandler = (e: ChangeEvent) => {
        // We are using setData from useForm(), so we don't have to create separate states.
        setData('title', e.target.value);
        setError('title', '');
    };

    const changeDescriptionHandler = (value: string) => {
        setData('description', value);
        setError('description', '');
    };

    const changeGoldenBuzzerPerksHandler = (value: string) => {
        setData('golden_buzzer_perks', value);
        setError('golden_buzzer_perks', '');
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        if (isEditing()) {
            // Updating an existing Stage.
            // (Getting into the habit of using patch instead of put: the latter implies that
            // the whole row is being updated.)
            patch(route('stages.update', { id: stage.id }), {
                onSuccess: () => {
                    RTToast.success(`"${stage.title}" was updated.`);
                    onOpenChange();
                },
                preserveScroll: true
            });
        } else {
            // Creating a new Stage.
            post(route('stages.store'), {
                onSuccess: () => {
                    RTToast.success(`"${data.title}" was created.`);
                    onOpenChange();
                },
                preserveScroll: true
            });
        }
        // NOTE: to use route(), the endpoints have to be named in the route definitions.
        // We can probably use url() as well.
    };


    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent aria-describedby={undefined} className="lg:max-w-4xl">
                <DialogTitle>{isEditing() ? 'Update' : 'Create'} Stage</DialogTitle>
                <form onSubmit={saveHandler}>
                    <div className="mb-2">
                        <Label htmlFor="stageName">Stage name</Label>
                        <Input id="stageName" type="text" className="font-bold" value={data.title}
                               onChange={changeTitleHandler}/>
                        <InputError className="mt-2" message={errors.title}/>
                    </div>

                    <div className="flex flex-col lg:flex-row gap-5">
                        <div className="lg:w-1/2 lg:max-w-1/2">
                            <Label htmlFor="stageDescription">Description</Label>
                            <MarkdownEditor value={data.description} onChange={changeDescriptionHandler}/>
                            <InputError className="mt-2" message={errors.description}/>
                        </div>

                        <div className="lg:w-1/2 lg:max-w-1/2">
                            <Label htmlFor="stageGoldenBuzzerPerks">Golden Buzzer Perks</Label>
                            <MarkdownEditor value={data.golden_buzzer_perks} onChange={changeGoldenBuzzerPerksHandler}
                                            placeholder="optional"/>
                            <InputError className="mt-2" message={errors.golden_buzzer_perks}/>
                        </div>
                    </div>

                    <DialogFooter>
                        <LoadingButton variant="default" type="submit" onClick={saveHandler}
                                       isLoading={processing}>Save</LoadingButton>
                        <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
