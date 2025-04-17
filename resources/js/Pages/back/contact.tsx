import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronDown, MessageCircleWarning } from 'lucide-react';
import { Checkbox } from '@/components/ui/checkbox';
import { ContactMessage, PaginatedResponse } from '@/types';

export default function ContactMessagesPage({ messages }: Readonly<{ messages: PaginatedResponse<ContactMessage> }>) {

    // TODO load more messages functionality.
    // TODO ability to delete messages.

    return (
        <AppLayout>
            <Head title="Contact Messages"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow font-bold text-2xl">Contact Messages</h1>
            </div>

            {messages.data ? messages.data.map((message) => (
                <Collapsible className="my-1 mx-2" key={message.id}>
                    <div className="flex gap-2 items-center p-2 w-full bg-teal-200 hover:bg-teal-300">
                        <Checkbox className="bg-white"/>
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
        </AppLayout>
    );
}
