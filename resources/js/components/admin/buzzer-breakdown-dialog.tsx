import { Dialog, DialogContent, DialogTitle } from '@/components/ui/dialog';
import { GoldenBuzzerBreakdown } from '@/types';
import { SongBanner } from '@/components/song-banner';

interface BuzzerBreakdownDialogProps {
    open: boolean;
    onOpenChange: () => void;
    data?: GoldenBuzzerBreakdown;
}

export const BuzzerBreakdownDialog: React.FC<BuzzerBreakdownDialogProps> = ({ data, open, onOpenChange }) => {

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="lg:w-5xl lg:max-w-[900px]" aria-describedby={undefined}>
                <DialogTitle>Golden Buzzer breakdown</DialogTitle>

                <div className="overflow-y-auto max-h-[60dvh] flex flex-col gap-5">
                    <div>
                        <h2 className="display-text">By Round</h2>
                        <table className="dashboard-table">
                            <thead>
                            <tr>
                                <th scope="col" className="text-left">Stage / Round</th>
                                <th scope="col" className="text-right">Amount raised</th>
                            </tr>
                            </thead>
                            <tbody>
                            {data?.rounds.map((row) => (
                                <tr key={row.round_id}>
                                    <th scope="row" className="text-left">{row.round_title}</th>
                                    <td className="text-right">{row.amount_raised}</td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <h2 className="display-text">By Song</h2>
                        <table className="dashboard-table">
                            <thead>
                            <tr>
                                <th scope="col" className="text-left">Song</th>
                                <th scope="col" className="text-right">Buzzers</th>
                                <th scope="col" className="text-right">Amount raised</th>
                            </tr>
                            </thead>
                            <tbody>
                            {data?.songs.map((row) => (
                                <tr key={row.song.id}>
                                    <th scope="row" className="text-left">
                                        <SongBanner song={row.song}/>
                                    </th>
                                    <td className="text-right">{row.buzzer_count.toLocaleString()}</td>
                                    <td className="text-right">{row.amount_raised}</td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    </div>
                </div>

            </DialogContent>
        </Dialog>
    );

};
