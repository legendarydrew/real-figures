import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import { useEffect, useRef, useState } from "react";
import { LoaderCircleIcon } from 'lucide-react';
import { RTToast } from '@/components/mode/toast-message';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Analytics',
        href: '/analytics'
    }
];

export default function AnalyticsPage() {

    const [isLoading, setIsLoading] = useState<boolean>(true);
    const data = useRef();

    useEffect(() => {
        axios.get("/api/analytics/collapse")
            .then((res) => {
                data.current = res.data;
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }, []);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Analytics"/>
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

                <Heading title="Analytics"/>

                { isLoading && <LoaderCircleIcon />}

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
                    {data.current?.length ? data.current.map((row, index) => (
                        <tr key={index}>
                            <td>{row.page}</td>
                            <td>{row.section}</td>
                            <td>{row.count}</td>
                        </tr>
                    )) : (
                        <tr><td colSpan="3" className="nothing">No data recorded.</td></tr>
                    )}
                    </tbody>
                </table>
            </div>
        </AppLayout>
    );
}
