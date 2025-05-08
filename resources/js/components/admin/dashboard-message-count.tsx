import { Mail } from 'lucide-react';
import { router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

// https://www.geeksforgeeks.org/create-a-line-chart-using-recharts-in-reactjs/

interface DashboardMessageCountProps {
    message_count: number;
}

export const DashboardMessageCount: React.FC<DashboardMessageCountProps> = ({ message_count }) => {

    const viewHandler = () => {
        router.visit('admin.contact');
    };

    return message_count && (
        <div className="bg-amber-100 border border-amber-500 rounded-sm p-3 flex items-center gap-2">
            <Mail className="h-5"/>
            <div className="flex-grow">
                {message_count === 1 ? 'A message has been received.' : <><b>{message_count}</b> messages received.</>}
            </div>
            <Button type="button" size="sm" variant="secondary" onClick={viewHandler}>View</Button>
        </div>
    );
};
