import AppLayout from '@/layouts/app-layout';
import { Head, router, WhenVisible } from '@inertiajs/react';
import React, { ChangeEvent, useEffect, useState } from 'react';
import { Nothing } from '@/components/nothing';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { CheckSquare, MailIcon, Square } from 'lucide-react';
import { Subscriber } from '@/types';
import { Checkbox } from '@/components/ui/checkbox';
import { DestructiveDialog } from '@/components/admin/destructive-dialog';
import { DialogTitle } from '@/components/ui/dialog';
import toast from 'react-hot-toast';
import { Label } from '@/components/ui/label';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { SUBSCRIBER_POST_DIALOG_NAME, SubscriberPostDialog } from '@/components/admin/subscriber-post-dialog';
import { useDialog } from '@/context/dialog-context';

interface SubscribersPageProps {
    subscriberCount: number;
    subscribers: Subscriber[];
    currentPage: number;
    hasMorePages: boolean;
}

const SubscribersPage: React.FC<SubscribersPageProps> = ({
                                                             subscriberCount,
                                                             subscribers,
                                                             currentPage,
                                                             hasMorePages
                                                         }: Readonly<SubscribersPageProps>) => {

    const { openDialog } = useDialog();
    const [selectedIds, setSelectedIds] = useState<number[]>([]);
    const [isConfirmingDelete, setIsConfirmingDelete] = useState<boolean>(false);
    const [processing, setProcessing] = useState<boolean>(false);

    const [filter, setFilter] = useState({ email: '' });

    const beginPostHandler = (): void => {
        console.log('post!');
        openDialog(SUBSCRIBER_POST_DIALOG_NAME);
    };

    const filterEmailHandler = (e: ChangeEvent): void => {
        setFilter({ ...filter, email: e.target.value });
    };

    const selectSubscriberHandler = (subscriber: Subscriber): void => {
        setSelectedIds([...new Set([...selectedIds, subscriber.id])]);
    }

    const deselectSubscriberHandler = (subscriber: Subscriber): void => {
        setSelectedIds(selectedIds.filter((id) => id !== subscriber.id));
    }

    const selectAllHandler = (): void => {
        setSelectedIds(subscribers.map((subscriber) => subscriber.id));
    }

    const deselectAllHandler = (): void => {
        setSelectedIds([]);
    }

    const confirmDeleteHandler = (): void => {
        setIsConfirmingDelete(true);
    };

    const deleteHandler = () => {
        setProcessing(true);
        router.delete(route('subscribers.destroy'), {
            data: { subscriber_ids: selectedIds },
            preserveUrl: true,
            preserveState: true,
            showProgress: true,
            onFinish: () => {
                setProcessing(false);
            },
            onSuccess: () => {
                deselectAllHandler();
                setIsConfirmingDelete(false);
                toast.success('Subscribers were removed.');
            },
            onError: () => {
                toast.error('Could not remove the subscriber(s).');
            }
        })
    };

    // A debouncing technique for updating results based on the filter.
    // https://www.freecodecamp.org/news/debouncing-explained/
    useEffect(() => {
        const getData = setTimeout(() => {
            router.reload({
                data: {
                    page: 1,
                    filter
                },
                preserveUrl: true
            });
        }, 1000);

        return () => clearTimeout(getData);
    }, [filter]);


    return (
        <>
            <Head title="Subscribers"/>

            <div className="flex mb-3 p-4 items-center sticky-top">
                <h1 className="display-text flex-grow text-2xl mr-auto">Subscribers</h1>
                {subscribers.length ? (
                    <div className="toolbar">
                        <Button type="button" onClick={beginPostHandler}>
                            <MailIcon/> Post Update
                        </Button>
                    </div>
                ) : ''}
            </div>

            {subscriberCount ? (
                <Collapsible>
                    <CollapsibleTrigger
                        className="block text-left p-2 mb-3 mx-4 cursor-pointer hover:bg-gray-100/10">Subscribers</CollapsibleTrigger>
                    <CollapsibleContent>
                        <>
                            <div className="flex mb-3 px-4 gap-3">
                                <Input type="search" className="flex-grow" value={filter.email}
                                       onChange={filterEmailHandler} placeholder="Filter by email"/>
                                <div className="toolbar">
                                    <Button variant="outline" type="button" onClick={selectAllHandler}>
                                        <CheckSquare/>
                                    </Button>
                                    <Button variant="outline" type="button" onClick={deselectAllHandler}>
                                        <Square/>
                                    </Button>
                                    <Button variant="destructive" type="button" onClick={confirmDeleteHandler}
                                            disabled={!selectedIds.length}>Remove Subscribers</Button>
                                </div>
                            </div>

                            <div className="overflow-y-auto max-h-[15rem] px-4 relative">
                                <div
                                    className="p-2 text-xs flex gap-2 font-semibold sticky top-0 bg-white dark:bg-gray-800">
                                    <div className="flex-grow">Email</div>
                                    <div className="w-30 text-right">Created at</div>
                                    <div className="w-30 text-right">Confirmed at</div>
                                </div>
                                <ul className="text-sm">
                                    {subscribers.map((subscriber) => (
                                        <li key={subscriber.id}
                                            className="flex items-center gap-2 py-1 px-2 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 select-none">
                                            <Checkbox id={'subscriber-' + subscriber.id} className="bg-white"
                                                      checked={selectedIds.includes(subscriber.id)}
                                                      onCheckedChange={(state) => state ? selectSubscriberHandler(subscriber) : deselectSubscriberHandler(subscriber)}/>
                                            <Label htmlFor={'subscriber-' + subscriber.id}
                                                   className="flex-grow truncate font-semibold">{subscriber.email}</Label>
                                            <div className="w-30 text-right">{subscriber.created_at}</div>
                                            <div className="w-30 text-right">{subscriber.updated_at}</div>
                                        </li>
                                    ))}
                                    {hasMorePages ? (
                                        <WhenVisible always params={{
                                            data: { page: currentPage + 1 },
                                            only: ['subscribers'],
                                            reset: ['currentPage', 'hasMorePages'],
                                            preserveUrl: true
                                        }}/>) : ''}
                                </ul>
                            </div>
                        </>

                    </CollapsibleContent>
                </Collapsible>
            ) : (
                <Nothing>There are no Subscribers.</Nothing>
            )}

            <SubscriberPostDialog/>
            <DestructiveDialog open={isConfirmingDelete} onConfirm={deleteHandler}
                               processing={processing}
                               onOpenChange={() => setIsConfirmingDelete(false)}>
                <DialogTitle>Removing Subscribers</DialogTitle>
                You are about to
                remove <b>{selectedIds.length.toLocaleString()} {selectedIds.length === 1 ? 'subscriber' : 'subscribers'}.</b><br/>
                Are you sure you want to do this?
            </DestructiveDialog>

        </>
    );
}

SubscribersPage.layout = (page) => <AppLayout>{page}</AppLayout>;
export default SubscribersPage;
