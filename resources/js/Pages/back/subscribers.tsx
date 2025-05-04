import AppLayout from '@/layouts/app-layout';
import { Head, WhenVisible } from '@inertiajs/react';
import React from 'react';
import { Nothing } from '@/components/nothing';

export default function Subscribers({ subscriberCount, subscribers, currentPage, hasMorePages }: Readonly<{
    subscriberCount,
    subscribers,
    currentPage,
    hasMorePages
}>) {

    return (
        <AppLayout>
            <Head title="Subscribers"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow font-bold text-2xl">Subscribers</h1>
            </div>

            {subscriberCount ? (
                <div className="overflow-y-auto max-h-[15rem] mx-3 relative">
                    <div className="p-2 text-xs flex gap-2 font-semibold sticky top-0 bg-white">
                        <div className="flex-grow">Email</div>
                        <div className="w-30 text-right">Created at</div>
                        <div className="w-30 text-right">Confirmed at</div>
                    </div>
                    <ul className="text-sm">
                        {subscribers.map((subscriber) => (
                            <li key={subscriber.id}
                                className="flex gap-2 py-1 px-2 hover:bg-gray-200 dark:bg-gray-800">
                                <div className="flex-grow truncate font-semibold">{subscriber.email}</div>
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
            ) : (
                <Nothing>There are no Subscribers.</Nothing>
            )}
        </AppLayout>
    );
}
