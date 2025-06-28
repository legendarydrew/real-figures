import { Nothing } from '@/components/mode/nothing';
import { Card, CardContent, CardTitle } from '@/components/ui/card';

type DashboardAnalyticsCountriesData = {
    index: number;
    country: string;
    views: number;
}[]

interface DashboardAnalyticsCountriesProps {
    data: DashboardAnalyticsCountriesData;
    className?: string;
}

export const DashboardAnalyticsCountries: React.FC<DashboardAnalyticsCountriesProps> = ({ data, className }) => {

    return (
        <Card className={className}>
            <CardTitle className="display-text font-normal">Top countries <small>within the last
                week</small></CardTitle>
            <CardContent>
                {data.length ? (
                    <table className="table w-full text-sm">
                        <tbody>
                        {data.map((row) => (
                            <tr key={row.index} className="hover:bg-gray-200">
                                <th scope="row" className="font-semibold leading-tight text-left px-2 py-0.5">
                                    {row.country}
                                </th>
                                <td className="text-right px-2 py-0.5 w-20">{row.views.toLocaleString()}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                ) : (
                    <Nothing className="w-full h-full">
                        No information about countries.
                    </Nothing>
                )}
            </CardContent>
        </Card>
    );
};
