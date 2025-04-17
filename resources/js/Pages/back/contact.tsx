import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';

export default function ContactMessagesPage() {

    return (
        <AppLayout>
            <Head title="Contact Messages"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow font-bold text-2xl">Contact Messages</h1>
            </div>

            <div className="nothing">
                No Contact Messages received.
            </div>
        </AppLayout>
    );
}
