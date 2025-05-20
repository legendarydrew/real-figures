import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
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
                    <PlaceholderPattern
                        className="w-full h-[10rem] mb-5 stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                    <Heading title="About the Contest"/>
                    <p className="mb-3">
                        With our roster of talented Acts, CATAWOL Records and the MODE Family join forces
                        to raise awareness of bullying within hobby spaces, through the power of music.
                    </p>
                    <Button type="button" size="lg" className="cursor-pointer" onClick={moreInfoHandler}>More
                        information</Button>
                </div>

                <div className="flex flex-col gap-3 md:grid md:gap-2 md:grid-cols-2 md:grid-rows-2">
                    <SubscribePanel className="w-full col-span-1 md:col-span-2"/>

                    <Link
                        className="flex flex-col justify-end bg-green-700 hover:bg-green-800 text-white font-semibold p-3 col-span-1 h-10 md:h-auto rounded-sm leading-none"
                        href={route('donations')}>
                        Donor Wall
                    </Link>

                    <Link
                        className="flex flex-col justify-end bg-red-500 hover:bg-red-600 text-white font-semibold p-3 col-span-1 h-10 md:h-auto rounded-sm leading-none"
                        href="https://youtube.com/@silentmodetv" target="_blank">
                        SilentMode
                        <br/>
                        <span className="text-sm">on YouTube</span>
                    </Link>
                </div>

            </div>
        </div>
    )
}
