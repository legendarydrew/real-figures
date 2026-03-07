import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import { useEffect, useState } from "react";
import { RTToast } from '@/components/mode/toast-message';
import axios from 'axios';
import { CollapseOpenAnalytics } from '@/components/analytics/collapse-open';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Analytics',
        href: '/analytics'
    }
];

export default function AnalyticsPage() {

    const [isLoading, setIsLoading] = useState({ collapse: true });
    const [chartData, setChartData] = useState({});

    useEffect(() => {
        axios.get("/api/analytics/collapse")
            .then((res) => {
                setChartData((prev) => ({...prev, collapse: res.data }));
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading((prev) => ({ ...prev, collapse: false}));
            });
    }, []);


    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Analytics"/>
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

                <Heading title="Analytics"/>

                <CollapseOpenAnalytics isLoading={isLoading.collapse} chartData={chartData.collapse} />
            </div>
        </AppLayout>
    );
}
