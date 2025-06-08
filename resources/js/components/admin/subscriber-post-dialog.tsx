import { ChangeEvent, FC, useEffect, useState } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/input-error';
import { MarkdownEditor } from '@/components/ui/markdown-editor';
import { LoadingButton } from '@/components/ui/loading-button';
import { useDialog } from '@/context/dialog-context';
import { cn } from '@/lib/utils';
import axios from 'axios';
import { Toaster } from '@/components/ui/toast-message';

type SubscriberPostForm = {
    title: string;
    body: string;
}
export const SUBSCRIBER_POST_DIALOG_NAME = 'subscriber-post';

interface SubscriberPostDialogProps {
    onCreated: () => {};
}
export const SubscriberPostDialog: FC<SubscriberPostDialogProps> = ({ onCreated }) => {

    const { openDialogName, closeDialog } = useDialog();

    const [data, setData] = useState<SubscriberPostForm>({ title: '', body: ''});
    const [errors, setErrors] = useState<{ [key: string]: string } | null>(null);
    const [processing, setProcessing] = useState<boolean>(false);


    const isOpen = openDialogName === SUBSCRIBER_POST_DIALOG_NAME;

    useEffect(() => {
        if (isOpen) {
            setErrors({ });
        };
    }, [isOpen]);

    const changeTitleHandler = (e: ChangeEvent) => {
        setData((prev) => ({ ...prev, title: e.target.value }));
        setErrors((prev) => ({ ...prev, title: '' }));
    };

    const changeBodyHandler = (body: string) => {
        setData((prev) => ({ ...prev, body }));
        setErrors((prev) => ({ ...prev, body: '' }));
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        if (!processing) {
            setProcessing(true);
            axios.post(route('subscribers.post'), data)
                .then((response) => {
                    Toaster.success(response.data.subscribers ?
                        `Post has been sent to ${response.data.subscribers} subscriber(s).` :
                        "Post has been saved."
                    );
                    setData({ title: '', body: '' });
                    closeDialog();
                    if (onCreated) {
                        onCreated();
                    }
                })
                .catch((error) => {
                    if (error?.status === 422) {
                        setErrors(error.response.data.errors);
                    }
                })
                .finally(() => {
                    setProcessing(false);
                });

        }
    };


    return (
        <Dialog open={isOpen} onOpenChange={closeDialog}>
            <DialogContent className="lg:w-5xl lg:max-w-[900px]" aria-describedby={undefined}>
                <DialogTitle>Send Subscriber Post</DialogTitle>
                <form onSubmit={saveHandler} className="flex flex-col gap-3">
                    <div>
                        <Label htmlFor="postTitle">Title / Subject</Label>
                        <Input id="postTitle" type="text" className="font-bold" value={data.title}
                               disabled={processing}
                               onChange={changeTitleHandler}/>
                        <InputError className="mt-2" message={errors?.title}/>
                    </div>

                    <div>
                        <Label className="sr-only" htmlFor="actBody">Post contents</Label>
                        <MarkdownEditor value={data.body}
                                        placeholder="Tell your Subscribers what's going on! (100 characters minimum.)"
                                        disabled={processing}
                                        onChange={changeBodyHandler}/>
                        <InputError className="mt-2" message={errors?.body}/>
                    </div>

                    <DialogFooter>
                        <div className={cn('flex-grow text-sm', data['body']?.length < 100 ? 'font-semibold' : '')}>
                            {data['body']?.length.toLocaleString()} / 100
                        </div>
                        <LoadingButton variant="default" type="submit" onClick={saveHandler}
                                       isLoading={processing}>Send Update</LoadingButton>
                        <Button variant="ghost" type="button" onClick={closeDialog}>Cancel</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
