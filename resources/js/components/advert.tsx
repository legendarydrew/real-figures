/**
 * ADVERT component
 * This uses a third-party component to aid in displaying AdSense banners on the site, using settings
 * configured in Laravel.
 * As usual, there is virtually NO documentation for this component - however we can see the parameters
 * by looking at the component definition.
 * The adTest property can reportedly be used to tell Google that interaction with the banner should not
 * count.
 */
import { cn } from '@/lib/utils';

export const Advert: React.FC = ({ className }) => {

    // see AppServiceProvider.php.
    const { adsense } = usePage().props;

    return (
        <div className={cn(className, 'overflow-hidden')}>
            <Adsense adTest={adsense.testing} client={adsense.client_id} slot={adsense.slot_id}
                     responsive={true}
                     format="fluid" style={{ display: 'block' }}/>
        </div>
    )
}
import { usePage } from '@inertiajs/react';

import { Adsense } from '@ctrl/react-adsense';
