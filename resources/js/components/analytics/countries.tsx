import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { Pie, PieChart, PieSectorShapeProps, Sector } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';

interface Props {
    days?: number;
}

export const CountriesAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [chartData, setChartData] = useState();

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        if (isLoading) {
            return;
        }

        setIsLoading(true);
        axios.get("/api/analytics/countries", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const stringToColor = (str: string) => {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        const hue = Math.abs(hash % 360);
        return `hsl(${hue}, 65%, 55%)`;
    }

    const pieSector = (props: PieSectorShapeProps) => {
        return <Sector {...props} fill={stringToColor(chartData.continents[props.index].continent)}/>;
    };

    return (
        <section id="analyticsCountries" className="analytics-section">
            <h2 className="analytics-section-title">Countries</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <div className="grid lg:grid-cols-3 gap-8 items-start">

                        <PieChart style={{ width: '100%', aspectRatio: 1 }} responsive>
                            <Pie
                                dataKey="views"
                                data={chartData.continents}
                                fill="#8884d8"
                                shape={pieSector}
                            />
                        </PieChart>

                        <table className="data-table lg:col-span-2">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col" className="text-left">Country</th>
                                <th scope="col" className="text-left">Continent</th>
                                <th scope="col" className="text-right">Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            {chartData?.data.length ? chartData.data.map((row, index) => (
                                <tr key={index}>
                                    <th scope="row">
                                        <span className={`flag flag:${row.flag}`}></span>
                                    </th>
                                    <th className="text-left" scope="row">{row.country}</th>
                                    <td className="text-left" scope="row">{row.continent}</td>
                                    <td className="text-right">{row.views}</td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan="4" className="nothing">No data recorded.</td>
                                </tr>
                            )}
                            </tbody>
                        </table>
                    </div>
                )}
            </LoadingOverlay>
        </section>
    )
}
