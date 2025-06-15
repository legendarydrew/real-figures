import { Nothing } from '@/components/nothing';
import { Card, CardContent, CardTitle } from '@/components/ui/card';

type DashboardSongsPlayedData = {
    songs: {
        title: string
        play_count: number;
    }[];
}

interface DashboardSongsPlayedProps {
    data: DashboardSongsPlayedData;
    className?: string;
}

export const DashboardSongsPlayed: React.FC<DashboardSongsPlayedProps> = ({ data, className }) => {

    return (
        <Card className={className}>
            <CardTitle className="display-text font-normal">Most played Songs <small>within the last day</small></CardTitle>
            <CardContent>
                {data.songs.length ? (
                    <table className="table w-full text-sm">
                        <tbody>
                        {data.songs.map((row) => (
                            <tr key={row.title}>
                                <th scope="row" className="font-bold text-left py-0.5">{row.title}</th>
                                <td className="text-right py-0.5 pl-2">{row.play_count.toLocaleString()}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                ) : (
                    <Nothing className="w-full h-full">
                        No information about Songs played.
                    </Nothing>
                )}
            </CardContent>
        </Card>
    );
};
