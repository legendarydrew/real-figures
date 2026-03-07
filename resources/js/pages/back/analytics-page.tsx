import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import { useEffect, useState } from "react";
import { LoaderCircleIcon } from 'lucide-react';
import { RTToast } from '@/components/mode/toast-message';
import axios from 'axios';
import { Bar, BarChart, Tooltip, XAxis, YAxis } from 'recharts';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Analytics',
        href: '/analytics'
    }
];

export default function AnalyticsPage() {

    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [chartData, setChartData] = useState();

    useEffect(() => {
        axios.get("/api/analytics/collapse")
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }, []);

    const fixedColors = {
        faq: "#ef4444",
        examples: "#3b82f6",
        intro: "#10b981",
        Other: "#9ca3af"
    };

    function getColor(key) {
        return fixedColors[key] ?? stringToColor(key);
    }

    function stringToColor(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        const hue = Math.abs(hash % 360);
        return `hsl(${hue}, 65%, 55%)`;
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Analytics"/>
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

                <Heading title="Analytics"/>

                {isLoading && <LoaderCircleIcon/>}

                {chartData && (
                    <BarChart
                        style={{ width: '100%', maxHeight: '300px', aspectRatio: 1.618 }}
                        responsive
                        data={chartData.data}
                    >
                        <XAxis dataKey="date"/>
                        <YAxis/>
                        <Tooltip/>

                        {chartData.keys.map(key => (
                            <Bar
                                key={key}
                                dataKey={key}
                                stackId="sections"
                                fill={getColor(key)}
                            />
                        ))}
                    </BarChart>
                )}
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
                    {chartData ? chartData.table.map((row, index) => (
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
