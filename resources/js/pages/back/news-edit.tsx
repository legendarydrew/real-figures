import { NewsPost } from '@/types';
import { Head, router, useForm } from '@inertiajs/react';
import React, { ChangeEvent, useEffect, useRef, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { LoadingButton } from '@/components/mode/loading-button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/input-error';
import { MarkdownEditor } from '@/components/mode/markdown-editor';
import { Toaster } from '@/components/mode/toast-message';
import { Alert } from '@/components/mode/alert';
import { ConfirmDialog } from '@/components/admin/confirm-dialog';
import { DialogTitle } from '@/components/ui/dialog';

export default function NewsEditPage({ post }: Readonly<{ post: NewsPost }>) {

    const { data, setData, errors, setError } = useForm({
        id: undefined,
        title: '',
        content: '',
        publish: undefined
    });

    useEffect(() => {
        setData({
            id: post?.id,
            title: post?.title,
            content: post?.content,
            publish: undefined
        });
    }, [post]);

    const [isSaving, setIsSaving] = useState<boolean>(false);
    const [isPublishDialogOpen, setIsPublishDialogOpen] = useState<boolean>(false);

    const isEditing = useRef<boolean>(!!post?.id);
    const isPublished = useRef<boolean>(!!post?.published_at);

    const changeTitleHandler = (e: ChangeEvent) => {
        setData('title', e.target.value);
        setError('title', '');
    };

    const changeContentHandler = (value: string) => {
        setData('content', value); // a gotcha!
        setError('content', '');
    };

    const cancelHandler = (): void => {
        router.visit(route('admin.news'));
    };

    const publishHandler = (): void => {
        setIsPublishDialogOpen(true);
    };

    const confirmPublishHandler = (): void => {
        setIsPublishDialogOpen(false);
        saveHandler(undefined, true);
    }

    const saveHandler = (e?: SubmitEvent, publish?: boolean) => {
        if (e) {
            e.preventDefault();
        }

        if (isSaving) {
            return;
        }

        setIsSaving(true);
        if (isEditing.current) {
            router.put(route('news.update', { id: post.id }), { ...data, publish }, {
                showProgress: true,
                onSuccess: () => {
                    Toaster.success('News Post was updated.');
                },
                onFinish: () => {
                    setIsSaving(false)
                },
                preserveScroll: true
            });
        } else {
            router.post(route('news.store'), data, {
                showProgress: true,
                onSuccess: () => {
                    Toaster.success('News Post was created.');
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
            <Head title={`${isEditing.current ? 'Edit' : 'Create'} News Post`}/>

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">{`${isEditing.current ? 'Edit' : 'Create'} News Post`}</h1>
            </div>

            <form className="flex flex-col gap-3 px-5" onSubmit={saveHandler}>

                <div>
                    <Label htmlFor="postTitle">Title</Label>
                    <Input id="postTitle" type="text" className="font-bold text-lg" defaultValue={data.title}
                           onChange={changeTitleHandler}/>
                    <InputError className="mt-2" message={errors.title}/>
                </div>

                {isPublished.current && (
                    <Alert type="success">This post was published on <b>{post.published_at}</b>.</Alert>
                )}

                <div>
                    <Label htmlFor="postContent">Description</Label>
                    <MarkdownEditor className="h-[12rem]" value={data.content}
                                    onChange={changeContentHandler}/>
                    <InputError className="mt-2" message={errors.content}/>
                </div>

                <div className="bg-white border-t-1 flex justify-between sticky bottom-0 -mx-5 px-5 py-3">
                    <Button variant="ghost" type="button" size="lg" onClick={cancelHandler}>Cancel</Button>
                    <div className="toolbar">
                        <LoadingButton size="lg" isLoading={isSaving}>Save News Post</LoadingButton>
                        {(isEditing.current && !isPublished.current && !isSaving) && (
                            <Button variant="confirm" type="button" size="lg"
                                    onClick={publishHandler}>Publish</Button>)}
                    </div>
                </div>
            </form>

            <ConfirmDialog open={isPublishDialogOpen} onOpenChange={() => setIsPublishDialogOpen(false)}
                           onConfirm={confirmPublishHandler}>
                <DialogTitle>Publish News Post: "{post?.title}"</DialogTitle>

                <span className="italic">The press release will be made visible to the public.</span><br/>
                Are you sure you want to do this?</ConfirmDialog>

        </AppLayout>
    );
};
