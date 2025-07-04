import { Nothing } from '@/components/mode/nothing';
import { Donation } from '@/types';
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { Star } from 'lucide-react';
import { Card, CardContent, CardTitle } from '@/components/ui/card';

interface DashboardDonationsProps {
    data: {
        rows: Donation[];
        total: string; // amount with currency.
    }[];
    className?: string;
}

export const DashboardDonations: React.FC<DashboardDonationsProps> = ({ data, className }) => {
    return (
        <Card className={className}>
            <CardTitle className="display-text font-normal">Donations received <small>last ten</small></CardTitle>
            <CardContent>
                {data.rows.length ? (
                    <table className="dashboard-table">
                        <tfoot>
                        <tr className="border-t-2 border-green-600 dark:border-green-300 text-base pt-2">
                            <th scope="row" colSpan="2" className="px-1 py-0.5 text-left">Total raised</th>
                            <td className="px-1 py-0.5 font-semibold text-right">{data.total}</td>
                        </tr>
                        </tfoot>
                        <tbody>
                        {data.rows.map((row) => (
                            <tr key={row.id} className="hover:bg-green-200/50">
                                <th scope="row"
                                    className={cn('px-1 py-0.5 text-left', row.is_anonymous ? 'italic font-normal' : 'font-bold')}>{row.name}</th>
                                <td className="px-1 py-0.5 text-right text-xs">{row.created_at}</td>
                                <td className="px-1 py-0.5 text-right">{row.amount}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                ) : (
                    <Nothing className="w-full">
                        No direct Donations received... yet!.
                    </Nothing>
                )}
                {/* Link to the Golden Buzzers page, if there are any. */}
                {data.golden_buzzers ? (
                    <Link
                        className="flex items-center gap-2 rounded-sm text-sm bg-yellow-600/50 hover:bg-yellow-600/90 p-3 mt-2 leading-none"
                        href={route('admin.golden-buzzers')}>
                        <Star className="h-5"/>
                        <span>
                    <b>{data.golden_buzzers.toLocaleString()} Golden {data.golden_buzzers === 1 ? 'Buzzer' : 'Buzzers'}</b> {data.golden_buzzers === 1 ? 'was' : 'were'} hit!
                    </span>
                    </Link>
                ) : ''}
            </CardContent>
        </Card>
    );
}
