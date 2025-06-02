import HeadingSmall from '@/components/heading-small';
import {SubscribeForm} from '@/components/front/subscribe-form';
import { cn } from '@/lib/utils';

export const SubscribePanel: React.FC = ({ className }) => {

    return (
        <div className={cn("rounded-md p-3", className)}>
            <HeadingSmall title="Subscribe for updates!"/>
            <p className="my-3 text-sm">Stay updated about the contest's progress, and be informed about when it's time
                to cast your votes! Your details will not be used for anything else.</p>
            <SubscribeForm/>
        </div>
    )
}
