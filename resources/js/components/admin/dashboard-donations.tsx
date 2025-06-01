import { Nothing } from '@/components/nothing';
import { Donation } from '@/types';
import { cn } from '@/lib/utils';

interface DashboardDonationsProps {
    data: {
        rows: Donation[];
        total: string; // amount with currency.
    }[];
}

export const DashboardDonations: React.FC<DashboardDonationsProps> = ({ data }) => {
    return (
        <div>
            <h2 className="font-bold mb-2">Donations received <small>last ten</small></h2>
            {data.rows.length ? (
                <table className="table w-full text-sm border-1">
                    <tfoot>
                    <tr className="border-t-2 text-base pt-2">
                        <th scope="row" colSpan="2" className="px-1 py-0.5 text-left">Total raised</th>
                        <td className="px-1 py-0.5 font-semibold text-right">{data.total}</td>
                    </tr>
                    </tfoot>
                    <tbody>
                    {data.rows.map((row) => (
                        <tr key={row.id} className="hover:bg-green-200/50">
                            <th scope="row" className={cn('px-1 py-0.5 text-left', row.is_anonymous ? 'italic font-normal' : 'font-bold')}>{row.name}</th>
                            <td className="px-1 py-0.5 text-right">{row.created_at}</td>
                            <td className="px-1 py-0.5 text-right">{row.amount}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            ) : (
                <Nothing className="border-1 w-full text-left">
                    No Donations received... yet!.
                </Nothing>
            )}
        </div>
    );
}
