import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { CheckSquare, ChevronDown, MessageCircleWarning, Square } from 'lucide-react';
import { Checkbox } from '@/components/ui/checkbox';
import { ContactMessage, PaginatedResponse } from '@/types';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { DestructiveDialog } from '@/components/admin/destructive-dialog';
import toast from 'react-hot-toast';

export default function ContactMessagesPage({ messages }: Readonly<{ messages: PaginatedResponse<ContactMessage> }>) {

    // TODO load more messages functionality.
    // TODO ability to respond to messages.

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
        setSelectedIds(messages.data.map((message) => message.id));
    }

    const deselectAllHandler = (): void => {
        setSelectedIds([]);
    }

    const confirmDeleteHandler = (): void => {
        setIsConfirmingDelete(true);
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

            <div className="flex mb-3 p-4 items-center">
                <h1 className="flex-grow font-bold text-2xl mr-auto">Contact Messages</h1>

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

            {messages.data ? messages.data.map((message) => (
                <Collapsible className="my-1 mx-2" key={message.id}>
                    <div className="flex gap-2 items-center p-2 w-full bg-teal-200 hover:bg-teal-300">
                        <Checkbox className="bg-white" checked={selectedIds.includes(message.id)}
                                  onCheckedChange={(state) => state ? selectMessageHandler(message) : deselectMessageHandler(message)}/>
                        <CollapsibleTrigger className="flex gap-3 w-full items-center">
                            <span className="flex-grow text-left">
                                <span className="font-bold mr-2">{message.name}</span>
                                <span className="text-sm">&lt;{message.email}&gt;</span>
                            </span>
                            <span className="text-destructive-foreground"
                                  title="Message is considered to be spam.">{message.is_considered_spam ?
                                <MessageCircleWarning/> : ''}</span>
                            <time className="text-sm">{message.sent_at}</time>
                            <ChevronDown className="flex-shrink-0 h-6 w-6"/>
                        </CollapsibleTrigger>
                    </div>
                    <CollapsibleContent className="py-3 px-8 bg-teal-100/50">
                        <blockquote className="mb-2">{message.body}</blockquote>
                        <p className="text-xs">This message was sent from IP address {message.ip}.</p>
                    </CollapsibleContent>
                </Collapsible>
            )) : (
                <div className="nothing">
                    No Contact Messages received.
                </div>
            )}

            <DestructiveDialog title="Deleting Contact Messages" open={isConfirmingDelete} onConfirm={deleteHandler}
                               processing={processing}
                               onOpenChange={() => setIsConfirmingDelete(false)}>
                You are about to delete <b>{selectedIds.length.toLocaleString()} messages.</b><br/>
                Are you sure you want to do this?
            </DestructiveDialog>
        </AppLayout>
    );
}
