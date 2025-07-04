import { Nothing } from '@/components/mode/nothing';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import TextLink from '@/components/mode/text-link';

type DashboardAnalyticsPagesData = {
    index: number;
    title: string;
    url: string;
    views: number;
}[]

interface DashboardAnalyticsPagesProps {
    data: DashboardAnalyticsPagesData;
    className?: string;
}

export const DashboardAnalyticsPages: React.FC<DashboardAnalyticsPagesProps> = ({ data, className }) => {

    return (
        <Card className={className}>
            <CardTitle className="display-text font-normal">Most viewed pages <small>within the last
                week</small></CardTitle>
            <CardContent>
                {data.length ? (
                    <table className="table w-full text-sm">
                        <tbody>
                        {data.map((row) => (
                            <tr key={row.index} className="hover:bg-gray-200">
                                <th scope="row" className="font-semibold leading-tight text-left px-2 py-0.5">
                                    <TextLink href={row.url} target="_blank">{row.title}</TextLink><br/>
                                    <small className="truncate">{row.url}</small>
                                </th>
                                <td className="text-right px-2 py-0.5 w-20">{row.views.toLocaleString()}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                ) : (
                    <Nothing className="w-full h-full">
                        No information about pages viewed.
                    </Nothing>
                )}
            </CardContent>
        </Card>
    );
};
