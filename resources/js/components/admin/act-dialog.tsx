import { ChangeEvent, FC, useEffect } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Act } from '@/types';
import { Toaster } from '@/components/ui/toast-message';
import { MarkdownEditor } from '@/components/ui/markdown-editor';

interface ActDialogProps {
    open: boolean;
    onOpenChange: () => void;
    act?: Act;
}

type ActForm = {
    name: string;
    profile: {
        description: string;
    } | null;
}

export const ActDialog: FC<ActDialogProps> = ({ open, onOpenChange, act }) => {

    const { data, setData, post, patch, errors, setError, processing } = useForm<Required<ActForm>>({
        name: '',
        profile: {
            description: ''
        }
    });

    useEffect(() => {
        setData({
            name: act?.name ?? '',
            profile: {
                description: act?.profile?.description ?? ''
            }
        });
    }, [act]);

    const isEditing = (): boolean => {
        return !!act?.id;
    }

    const changeNameHandler = (e: ChangeEvent) => {
        setData('name', e.target.value);
        setError('name', '');
    };

    const changeProfileDescriptionHandler = (value: string) => {
        setData('profile', { ...data.profile, description: value }); // a gotcha!
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        // If the profile is empty, remove the whole thing.
        if (data.profile && Object.values(data.profile).every((v) => !(v || v.length))) {
            setData('profile', null);
        }

        if (isEditing()) {
            patch(route('acts.update', { id: act.id }), {
                onSuccess: () => {
                    Toaster.success(`Act "${act.name}" was updated.`);
                    onOpenChange();
                },
                preserveScroll: true
            });
        } else {
            post(route('acts.store'), {
                onSuccess: () => {
                    Toaster.success(`Act "${data.name}" was created.`);
                    onOpenChange();
                },
                preserveScroll: true
            });
        }
    };


    return (
        <Dialog open={open} onClose={onOpenChange}>
            <DialogContent className="lg:w-5xl lg:max-w-[900px]" aria-describedby={undefined}>
                <DialogTitle>{isEditing() ? 'Update' : 'Create'} Act</DialogTitle>
                <form onSubmit={saveHandler}>
                    <div className="flex gap-4">

                        {/* Left side */}
                        <div className="flex-grow">
                            <div className="mb-2">
                                <Label htmlFor="actName">Act's name</Label>
                                <Input id="actName" type="text" className="font-bold" value={data.name}
                                       onChange={changeNameHandler}/>
                                <InputError className="mt-2" message={errors.title}/>
                            </div>
                        </div>

                        {/* Right side */}
                        <div className="flex-shrink-0 w-1/2">
                            <span className="flex-grow">Profile <small>[optional]</small></span>
                            <div>
                                <Label htmlFor="actDescription">Description</Label>
                                <MarkdownEditor value={data.profile.description}
                                                onChange={changeProfileDescriptionHandler}/>
                                <InputError className="mt-2" message={errors.description}/>
                            </div>
                        </div>

                    </div>


                    <DialogFooter>
                        <Button variant="default" type="submit" onClick={saveHandler}
                                disabled={processing}>Save</Button>
                        <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
