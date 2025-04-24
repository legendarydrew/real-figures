type DashboardSongsPlayedData = {
    songs: {
        title: string
        play_count: number;
    }[];
}

interface DashboardSongsPlayedProps {
    data: DashboardSongsPlayedData;
}

export const DashboardSongsPlayed: React.FC<DashboardSongsPlayedProps> = ({ data }) => {

    return (
        <section>
            <h2 className="font-bold mb-2">Most played Songs <small>within the last day</small></h2>
            <table className="table w-full text-sm">
                <tbody>
                {data.songs.map((row) => (
                    <tr key={row.title}>
                        <th scope="row" className="font-bold text-left py-0.5">{row.title}</th>
                        <td className="text-right py-0.5">{row.play_count.toLocaleString()} play(s)</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </section>
    );
};
