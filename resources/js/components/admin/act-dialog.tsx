import { ChangeEvent, FC, useEffect } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { router, useForm, usePage } from '@inertiajs/react';
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
    profile?: {
        description: string;
    } | null;
    image: string | null;
}

export const ActDialog: FC<ActDialogProps> = ({ open, onOpenChange, act }) => {

    const { data, setData, errors, setError, processing } = useForm<Required<ActForm>>({
        name: '',
        profile: {
            description: ''
        },
        image: ''
    });

    const { props } = usePage();

    useEffect(() => {
        setData({
            name: act?.name ?? '',
            profile: {
                description: act?.profile?.description ?? ''
            },
            image: act?.image
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
        setError('profile.description', '');
    };

    const changeImageHandler = (e) => {
        const file: File = e.target.files[0];
        // Convert the file to a base64 encoded string.
        // https://stackoverflow.com/a/53129416/4073160
        const reader = new FileReader();
        reader.onload = function () {
            setData('image', reader.result?.toString());
            setError('image', '');
        };
        reader.readAsDataURL(file);
        e.target.filename = undefined; // to allow selecting the same file more than once.
    };

    const removeImageHandler = () => {
        setData('image', '');
        setError('image', '');
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        // If the profile is empty, remove the whole thing.
        const formData = { ...data, page: props.acts?.meta.pagination.current_page };
        if (data.profile && Object.values(data.profile).every((v) => !(v || v.length))) {
            delete formData.profile;
        }

        if (isEditing()) {
            router.patch(route('acts.update', { id: act.id }), formData, {
                showProgress: true,
                onSuccess: () => {
                    Toaster.success(`Act "${act.name}" was updated.`);
                    onOpenChange();
                },
                preserveScroll: true
            });
        } else {
            router.post(route('acts.store'), formData, {
                showProgress: true,
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
                        <div className="flex-grow w-2/5">
                            <div className="mb-3">
                                <Label htmlFor="actName">Act's name</Label>
                                <Input id="actName" type="text" className="font-bold" value={data.name}
                                       onChange={changeNameHandler}/>
                                <InputError className="mt-2" message={errors.title}/>
                            </div>

                            <div className="mb-2 flex gap-2">
                                <div className="flex-grow flex-shrink-0">
                                    <Label>Act picture</Label>

                                    <div className="flex gap-1 mt-2">
                                        {/* The usual method of using a label styled as a button. */}
                                        <label
                                            className="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] h-9 px-4 py-2 has-[>svg]:px-3 bg-primary text-primary-foreground shadow-xs hover:bg-primary/90"
                                            htmlFor="actImage">{data.image ? 'Replace' : 'Add'}</label>
                                        <input id="actImage" type="file" accept="image/*" onChange={changeImageHandler}
                                               className="hidden" aria-describedby="actImageHelp"/>

                                        {data.image && (<Button variant="destructive" type="button"
                                                                onClick={removeImageHandler}>Remove</Button>)}
                                    </div>
                                    <p className="mt-1 text-xs" id="file_input_help">JPEG or PNG recommended.</p>
                                </div>
                                {data.image && (
                                    <div className="bg-gray-200 w-[12em] h-[10em] rounded-sm bg-cover"
                                         style={{ backgroundImage: `url(${data.image})` }}/>
                                )}
                            </div>
                            <InputError className="mt-2" message={errors.image}/>
                        </div>

                        {/* Right side */}
                        <div className="flex-shrink-0 w-3/5">
                            <span className="flex-grow">Profile <small>[optional]</small></span>
                            <div>
                                <Label htmlFor="actDescription">Description</Label>
                                <MarkdownEditor value={data.profile?.description}
                                                onChange={changeProfileDescriptionHandler}/>
                                <InputError className="mt-2" message={errors['profile.description']}/>
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
