import { ChangeEvent, FC, useEffect } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { MarkdownEditor } from '@/components/ui/markdown-editor';
import { LoadingButton } from '@/components/ui/loading-button';
import { useDialog } from '@/context/dialog-context';
import { cn } from '@/lib/utils';

type SubscriberPostForm = {
    title: string;
    body: string;
}
export const SUBSCRIBER_POST_DIALOG_NAME = 'subscriber-post';

export const SubscriberPostDialog: FC<SubscriberPostDialogProps> = () => {

    const { openDialogName, closeDialog } = useDialog();

    const {
        data,
        setData,
        reset,
        errors,
        setError,
        post,
        processing
    } = useForm<Required<SubscriberPostForm>>({
        title: '',
        body: ''
    });

    const isOpen = openDialogName === SUBSCRIBER_POST_DIALOG_NAME;

    useEffect(() => {
        reset();
    }, [isOpen]);

    const changeTitleHandler = (e: ChangeEvent) => {
        setData('title', e.target.value);
        setError('title', '');
    };

    const changeBodyHandler = (value: string) => {
        setData('body', value);
        setError('body', '');
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        post(route('subscribers.post'), {
            showProgress: true,

            onSuccess: (page) => {
                console.log(page);
                closeDialog();
            },
            preserveScroll: true
        });
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
                        <InputError className="mt-2" message={errors.title}/>
                    </div>

                    <div>
                        <Label className="sr-only" htmlFor="actBody">Post contents</Label>
                        <MarkdownEditor value={data.body}
                                        placeholder="Tell your Subscribers what's going on! (100 characters minimum.)"
                                        disabled={processing}
                                        onChange={changeBodyHandler}/>
                        <InputError className="mt-2" message={errors['body']}/>
                    </div>

                    <DialogFooter>
                        <div className={cn('flex-grow text-sm', data['body'].length < 100 ? 'font-semibold' : '')}>
                            {data['body'].length.toLocaleString()} / 100
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
