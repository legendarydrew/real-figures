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

                <table className="data-table">
                    <caption>Collapse sections opened</caption>
                    <thead>
                    <tr>
                        <th>Page</th>
                        <th>Section</th>
                        <th>Count</th>
                    </tr>
                    </thead>
                    <tbody>
                    {data.length ? data.map((row, index) => (
                        <tr key={index}>
                            <td colSpan="3">{JSON.stringify(row)}</td>
                        </tr>
                    )) : (
                        <tr><td colSpan="3" className="nothing">No data recorded.</td></tr>
                    )}
                    </tbody>
                </table>
                <div>{ data }</div>
            </div>
        </AppLayout>
    );
}
