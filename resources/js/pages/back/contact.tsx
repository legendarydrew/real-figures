import AppLayout from '@/layouts/app-layout';
import { Head, router, WhenVisible } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { CheckSquare, ChevronDown, MessageCircleWarning, Square } from 'lucide-react';
import { Checkbox } from '@/components/ui/checkbox';
import { ContactMessage } from '@/types';
import React, { useState } from 'react';
import { DestructiveDialog } from '@/components/admin/destructive-dialog';
import toast from 'react-hot-toast';
import { DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { ContactMessageRespond } from '@/components/admin/contact-message-respond';
import { Nothing } from '@/components/mode/nothing';
import axios from 'axios';
import { cn } from '@/lib/utils';

interface ContactMessagesPageProps {
    messages: ContactMessage[];
    isFirstPage: boolean;
    currentPage: number;
    hasMorePages: boolean;
}

export default function ContactMessagesPage({
                                                messages,
                                                currentPage,
                                                hasMorePages
                                            }: Readonly<ContactMessagesPageProps>) {

    const [selectedIds, setSelectedIds] = useState<number[]>([]);
    const [isConfirmingDelete, setIsConfirmingDelete] = useState<boolean>(false);
    const [processing, setProcessing] = useState<boolean>(false);

    const selectMessageHandler = (message: ContactMessage): void => {
        setSelectedIds([...new Set([...selectedIds, message.id])]);
    }

    const deselectMessageHandler = (message: ContactMessage): void => {
        setSelectedIds(selectedIds.filter((id) => id !== message.id));
    }

    const selectAllHandler = (): void => {
        setSelectedIds(messages.map((message) => message.id));
    }

    const deselectAllHandler = (): void => {
        setSelectedIds([]);
    }

    const confirmDeleteHandler = (): void => {
        setIsConfirmingDelete(true);
    };

    const markReadHandler = (message: ContactMessage): void => {
        if (!message.was_read) {
            axios.put(route('messages.read', { id: message.id }))
                .then(() => {
                    message.was_read = true;
                });
        }
    };

    const deleteHandler = () => {
        setProcessing(true);
        router.delete(route('messages.destroy'), {
            data: { message_ids: selectedIds },
            preserveUrl: true,
            preserveState: true,
            showProgress: true,
            onFinish: () => {
                setProcessing(false);
            },
            onSuccess: () => {
                deselectAllHandler();
                setIsConfirmingDelete(false);
                toast.success('Message(s) successfully deleted.');
            },
            onError: () => {
                toast.error('Could not delete the message(s).');
            }
        })
    };

    return (
        <AppLayout>
            <Head title="Contact Messages"/>

            <div className="flex mb-3 p-4 items-center sticky-top">
                <h1 className="display-text flex-grow text-2xl mr-auto">Contact Messages</h1>

                <div className="flex gap-1">
                    <Button variant="outline" type="button" onClick={selectAllHandler}>
                        <CheckSquare/>
                    </Button>
                    <Button variant="outline" type="button" onClick={deselectAllHandler}>
                        <Square/>
                    </Button>
                    <Button variant="destructive" type="button" onClick={confirmDeleteHandler}
                            disabled={!selectedIds.length}>Delete
                        messages</Button>
                </div>
            </div>

            {messages?.length ? (
                <>
                    {messages.map((message) => (
                        <Collapsible className="my-1 mx-4" key={message.id}
                                     onOpenChange={() => markReadHandler(message)}>
                            <div
                                className={cn("flex gap-2 items-center p-2 w-full", message.was_read ? "bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-800" : "bg-teal-200 hover:bg-teal-300 dark:bg-teal-700 dark:hover:bg-teal-800")}>
                                <Checkbox className="bg-white" checked={selectedIds.includes(message.id)}
                                          onCheckedChange={(state) => state ? selectMessageHandler(message) : deselectMessageHandler(message)}/>
                                <CollapsibleTrigger className="flex gap-3 w-full items-center">
                                    <span className="flex-grow text-left">
                                        <span className="font-bold mr-2">{message.name}</span>
                                        <span className="text-sm">&lt;{message.email}&gt;</span>
                                    </span>
                                    <span className="text-destructive-foreground"
                                          title="Message is considered to be spam.">{message.is_spam ?
                                        <MessageCircleWarning/> : ''}</span>
                                    <time className="text-sm">{message.sent_at}</time>
                                    <ChevronDown className="flex-shrink-0 h-6 w-6"/>
                                </CollapsibleTrigger>
                            </div>
                            <CollapsibleContent
                                className={cn("py-3 px-8", message.was_read ? "bg-gray-100/50" : "bg-teal-100/50")}>
                                <blockquote className="mb-2">{message.body}</blockquote>
                                {message.ip &&
                                    <p className="text-xs">This message was sent from IP address {message.ip}.</p>}
                                <ContactMessageRespond message={message}/>
                            </CollapsibleContent>
                        </Collapsible>
                    ))}
                    {hasMorePages ? (
                        <WhenVisible always params={{
                            data: { page: currentPage + 1 },
                            only: ['messages'],
                            reset: ['currentPage', 'hasMorePages'],
                            preserveUrl: true
                        }}/>) : ''}
                </>
            ) : (
                <Nothing>
                    No Contact Messages received.
                </Nothing>
            )}

            <DestructiveDialog open={isConfirmingDelete} onConfirm={deleteHandler}
                               processing={processing}
                               onOpenChange={() => setIsConfirmingDelete(false)}>
                <DialogTitle>Deleting Contact Messages</DialogTitle>
                {selectedIds.length === 1 ? (
                    <>
                        Are you sure you want to delete this message?
                    </>
                ) : (
                    <>
                        You are about to delete <b>{selectedIds.length.toLocaleString()} messages.</b><br/>
                        Are you sure you want to do this?
                    </>
                )}
            </DestructiveDialog>
        </AppLayout>
    );
}
