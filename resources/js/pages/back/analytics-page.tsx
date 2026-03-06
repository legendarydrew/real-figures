import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import { useEffect, useState } from "react";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Analytics',
        href: '/analytics'
    }
];

export default function AnalyticsPage() {


    const [data, setData] = useState([]);

    useEffect(() => {
        fetch("/api/analytics/collapse")
            .then(res => res.json())
            .then(setData);
    }, []);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Analytics"/>
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

                <Heading title="Analytics"/>

                <p>It begins...</p>

                <div>{ data }</div>
            </div>
        </AppLayout>
    );
}
