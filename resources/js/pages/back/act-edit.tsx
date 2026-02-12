import { Act } from '@/types';
import { Head, router, useForm } from '@inertiajs/react';
import React, { ChangeEvent, useEffect, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { LoadingButton } from '@/components/mode/loading-button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/input-error';
import { MarkdownEditor } from '@/components/mode/markdown-editor';
import { RTToast } from '@/components/mode/toast-message';
import { Checkbox } from '@/components/ui/checkbox';
import HeadingSmall from '@/components/heading-small';
import { ActMetaMembers } from '@/components/admin/act-meta-members';
import { ActMetaNotes } from '@/components/admin/act-meta-notes';
import { ActMetaLanguages } from '@/components/admin/act-meta-languages';
import { ActMetaTraits } from '@/components/admin/act-meta-traits';
import { ActMetaGenres } from '@/components/admin/act-meta-genres';

export default function ActEdit({ act, genreList }: Readonly<{ act: Act, genreList: string[] }>) {

    const { data, setData, errors, setError } = useForm<Required<ActForm>>({
        name: '',
        slug: '',
        profile: {
            description: ''
        },
        is_fan_favourite: false,
        image: '',
        image_url: '',
        remove_image: false,
        meta: {
            members: [],
            notes: [],
            traits: []
        }
    });

    useEffect(() => {
        setData({
            name: act?.name ?? '',
            slug: act?.slug ?? '',
            profile: {
                description: act?.profile?.description ?? ''
            },
            is_fan_favourite: act?.meta.is_fan_favourite ?? 0,
            image_url: act?.image_url,
            remove_image: false,
            meta: {
                genres:act?.meta.genres ?? [],
                languages: act?.meta.languages ?? [],
                members: act?.meta.members ?? [],
                notes: act?.meta.notes ?? [],
                traits: act?.meta.traits ?? []
            }
        });
    }, [act]);

    const [isSaving, setIsSaving] = useState<boolean>(false);

    const isEditing = (): boolean => {
        return !!act?.id;
    }

    const changeNameHandler = (e: ChangeEvent) => {
        setData('name', e.target.value);
        setError('name', '');
    };

    const changeSlugHandler = (e: ChangeEvent) => {
        setData('slug', e.target.value);
        setError('slug', '');
    };

    const changeProfileDescriptionHandler = (value: string) => {
        setData('profile', { ...data.profile, description: value }); // a gotcha!
        setError('profile.description', '');
    };

    const changeImageHandler = (e) => {
        const file: File = e.target.files[0];
        // Convert the file to a base64 encoded string.
        // https://stackoverflow.com/a/53129416/4073160
        const reader: FileReader = new FileReader();
        reader.onload = function () {
            setData('image', file);
            setData('image_url', reader.result?.toString());
            setData('remove_image', false);
            setError('image', '');
        };
        reader.readAsDataURL(file);
        e.target.filename = undefined; // to allow selecting the same file more than once.
    };

    const removeImageHandler = () => {
        setData('image', '');
        setData('image_url', '');
        setData('remove_image', true);
        setError('image', '');
    };

    const updateMetaHandler = (column: string, e: any): void => {
        setData(`meta.${column}`, e);
    };

    const cancelHandler = (): void => {
        router.visit(route('admin.acts'));
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        if (isSaving) {
            return;
        }

        // If the profile is empty, remove the whole thing.
        const formData = { ...data };
        if (data.profile && Object.values(data.profile).every((v) => !(v ?? v.length))) {
            delete formData.profile;
        }

        // Remove empty meta information.
        formData.meta.languages = formData.meta.languages.filter((row) => row?.length);
        formData.meta.genres = formData.meta.genres.filter((row) => row?.length);
        formData.meta.members = formData.meta.members.filter((row) => row?.length);
        formData.meta.notes = formData.meta.notes.filter((row) => row?.length);
        formData.meta.traits = formData.meta.traits.filter((row) => row?.length);

        setIsSaving(true);
        if (isEditing()) {
            router.patch(route('acts.update', { id: act.id }), formData, {
                showProgress: true,
                onSuccess: () => {
                    RTToast.success(`Act "${act.name}" was updated.`);
                },
                onFinish: () => {
                    setIsSaving(false)
                },
                preserveScroll: true
            });
        } else {
            router.post(route('acts.store'), formData, {
                showProgress: true,
                onSuccess: () => {
                    RTToast.success(`Act "${data.name}" was created.`);
                },
                onFinish: () => {
                    setIsSaving(false)
                },
                preserveScroll: true
            });
        }
    };

    return (
        <AppLayout>
            <Head title="Create Act"/>

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">{isEditing() ? 'Edit Act' : 'Create Act'}</h1>
            </div>

            <form className="flex flex-col gap-3 px-5" onSubmit={saveHandler}>

                <div className="flex gap-10">

                    {/* Left side */}
                    <div className="w-2/5">
                        <div className="mb-3">
                            <Label htmlFor="actName">Act's name</Label>
                            <Input id="actName" type="text" className="font-bold text-lg" value={data.name}
                                   onChange={changeNameHandler}/>
                            <InputError className="mt-2" message={errors.name}/>
                        </div>

                        <div className="mb-3">
                            <Label htmlFor="actSlug">Slug</Label>
                            <Input id="actSlug" type="text" className="text-sm" value={data.slug}
                                   onChange={changeSlugHandler} placeholder="Generated from the Act name"/>
                            <InputError className="mt-2" message={errors.slug}/>
                        </div>

                        <div className="mb-2 flex gap-2">
                            <div className="flex-grow flex-shrink-0">
                                <Label>Act picture</Label>

                                <div className="flex gap-1 mt-2">
                                    {/* The usual method of using a label styled as a button. */}
                                    <label
                                        className="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] h-9 px-4 py-2 has-[>svg]:px-3 bg-primary text-primary-foreground shadow-xs hover:bg-primary/90"
                                        htmlFor="actImage">{data.image_url ? 'Replace' : 'Add'}</label>
                                    <input id="actImage" type="file" accept="image/*" onChange={changeImageHandler}
                                           className="hidden" aria-describedby="actImageHelp"/>

                                    {data.image_url && (<Button variant="destructive" type="button"
                                                            onClick={removeImageHandler}>Remove</Button>)}
                                </div>
                                <p className="mt-1 text-xs" id="file_input_help">JPEG or PNG recommended.</p>
                            </div>
                            {data.image_url && (
                                <div
                                    className="w-[12em] h-[10em] rounded-sm overflow-hidden bg-linear-to-bl from-indigo-300 to-indigo-700">
                                    <div className="w-full h-full rounded-sm bg-cover"
                                         style={{ backgroundImage: `url(${data.image_url})` }}/>
                                </div>
                            )}
                        </div>
                        <InputError className="mt-2" message={errors.image}/>
                    </div>

                    {/* Right side */}
                    <div className="flex-shrink-0 flex-grow">
                        <HeadingSmall title="Profile (optional)"/>

                        <div>
                            <Label htmlFor="actDescription">Description</Label>
                            <MarkdownEditor className="h-[12rem]" value={data.profile?.description}
                                            onChange={changeProfileDescriptionHandler}/>
                            <InputError className="mt-2" message={errors['profile.description']}/>
                        </div>
                    </div>

                </div>

                {/* Optional Meta information. */}
                <HeadingSmall title="Additional information (optional)"/>

                <div>
                    <label className="flex gap-2 items-center text-sm font-semibold">
                        <Checkbox checked={data.is_fan_favourite}
                                  onCheckedChange={(checked) => setData('is_fan_favourite', checked)}/>
                        Is fan favourite
                    </label>
                    <p className="pl-6 text-sm text-muted-foreground">A fan favourite is a popular Act that is
                        expected to win the Contest.</p>
                </div>

                <ActMetaGenres genres={data.meta.genres} genreList={genreList} onChange={(e) => updateMetaHandler('genres', e)}/>
                <ActMetaLanguages languages={data.meta.languages} onChange={(e) => updateMetaHandler('languages', e)}/>
                <ActMetaMembers members={data.meta.members} onChange={(e) => updateMetaHandler('members', e)}/>
                <ActMetaNotes notes={data.meta.notes} onChange={(e) => updateMetaHandler('notes', e)}/>
                <ActMetaTraits traits={data.meta.traits} onChange={(e) => updateMetaHandler('traits', e)}/>

                <div className="bg-white border-t-1 flex justify-between sticky bottom-0 py-3">
                    <Button variant="ghost" type="button" size="lg" onClick={cancelHandler}>Cancel</Button>
                    <LoadingButton size="lg" isLoading={isSaving}>Save Act</LoadingButton>
                </div>
            </form>

        </AppLayout>
    );
};
