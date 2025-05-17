import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { Advert } from '@/components/advert';
import { Link, router } from '@inertiajs/react';
import { SubscribePanel } from '@/components/front/subscribe-panel';

export default function AboutBanner() {

    const moreInfoHandler = () => {
        router.visit('about');
    };

    return (
        <div className="bg-gray-200 p-5 md:py-10">
            <div className="max-w-5xl mx-auto grid gap-4 grid-cols-1 md:grid-cols-2">

                <div className="pr-10">
                    <Heading title="About the Contest"/>
                    <p className="mb-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab
                        delectus dolorum expedita facere fugit illo in neque non perferendis, possimus quasi repellendus
                        repudiandae soluta
                        sunt, suscipit, vel velit veritatis voluptatum?</p>
                    <Button type="button" size="lg" className="cursor-pointer" onClick={moreInfoHandler}>More
                        information</Button>
                </div>

                <div className="flex flex-col gap-3 md:grid md:gap-2 md:grid-cols-2 md:grid-rows-2">
                    <SubscribePanel className="w-full col-span-1 md:col-span-2"/>

                    <div className="bg-green-500 p-3 col-span-1 h-10 md:h-auto rounded-sm">
                        <Link href={route('donations')}>Donor Wall</Link>
                    </div>

                    <PlaceholderPattern
                        className="w-full col-span-1 stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                    <Advert className="w-full h-[12rem] col-span-2"/>
                </div>

            </div>
        </div>
    )
}
