import { Head, router, useForm } from '@inertiajs/react';
import React, { ChangeEvent, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { LoadingButton } from '@/components/mode/loading-button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/input-error';
import { MarkdownEditor } from '@/components/mode/markdown-editor';
import { RTToast } from '@/components/mode/toast-message';
import { AdminHeader } from '@/components/admin/admin-header';
import axios from 'axios';

type Form = {
    title: string;
    body: string
}

export default function SubscribersPostPage({ subscriberCount }: Readonly<{ subscriberCount: number }>) {

    const { data, setData, errors, setError } = useForm<Readonly<Form>>({
        title: '',
        body: ''
    });

    const [processing, setProcessing] = useState<boolean>(false);

    const changeTitleHandler = (e: ChangeEvent): void => {
        setData('title', e.target.value);
        setError('title', '');
    };

    const changeBodyHandler = (value: string): void => {
        setData('body', value);
        setError('body', '');
    };

    const cancelHandler = (): void => {
        router.visit(route('admin.subscribers'));
    }

    const saveHandler = (e: SubmitEvent): void => {
        e.preventDefault();

        if (!processing) {
            setProcessing(true);
            axios.post(route('subscribers.post'), data)
                .then((response) => {
                    RTToast.success(response.data.subscribers ?
                        `Post has been sent to ${response.data.subscribers} subscriber(s).` :
                        "Post has been saved."
                    );
                    setData({ title: '', body: '' });
                    router.visit(route('admin.subscribers'));
                })
                .catch((error) => {
                    if (error?.status === 422) {
                        setError(error.response.data.errors);
                    }
                })
                .finally(() => {
                    setProcessing(false);
                });
        }
    };

    return (
        <AppLayout>
            <Head title="New Subscribers Post"/>

            <div className="admin-content">

                <AdminHeader title="New Subscribers Post"/>
                {!!subscriberCount && (
                    <p className="text-muted-foreground">This will be sent
                        to <b>{subscriberCount}</b> {subscriberCount > 1 ? 'subscribers' : 'subscriber'}.</p>
                )}

                <form onSubmit={saveHandler} className="flex flex-col gap-3">
                    <div>
                        <Label htmlFor="postTitle">Title / Subject</Label>
                        <Input id="postTitle" type="text" className="font-bold" value={data.title}
                               disabled={processing}
                               onChange={changeTitleHandler}/>
                        <InputError message={errors?.title}/>
                    </div>

                    <div>
                        <Label className="sr-only" htmlFor="actBody">Post contents</Label>
                        <MarkdownEditor value={data.body} id="actBody" className="h-40"
                                        placeholder="Tell your Subscribers what's going on! (100 characters minimum.)"
                                        disabled={processing}
                                        onChange={changeBodyHandler}/>
                        <InputError message={errors?.body}/>
                    </div>

                    <div className="bg-white border-t-1 flex justify-between sticky bottom-0 py-3">
                        <Button variant="ghost" type="button" size="lg" onClick={cancelHandler}>Cancel</Button>
                        <LoadingButton variant="primary" size="lg" isLoading={processing}>Send Post</LoadingButton>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
};
