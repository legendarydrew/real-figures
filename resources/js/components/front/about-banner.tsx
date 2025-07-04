import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Link, router } from '@inertiajs/react';
import { SubscribePanel } from '@/components/front/subscribe-panel';
import { BrickTherapyLink } from '@/components/front/brick-therapy-link';
import { Advert } from '@/components/mode/advert';

export default function AboutBanner() {

    const moreInfoHandler = () => {
        router.visit('about');
    };

    return (
        <div className="bg-gray-200 dark:bg-gray-700 p-5 md:py-10">
            <div className="max-w-5xl mx-auto grid gap-4 grid-cols-1 md:grid-cols-2">

                <div className="md:pr-10">
                    <figure className="mb-5 overflow-hidden max-h-[12rem] md:max-h-[20rem]">
                        <img className="w-full" src="/img/sigfig-and-ceo.jpg"
                             alt="The CEO of CATAWOL Records and the MODE Family's Sigfig joining forces."/>
                    </figure>

                    <Heading title="About the Contest"/>
                    <p className="mb-3">
                        With our roster of talented Acts, CATAWOL Records and the MODE Family join forces
                        to raise awareness of bullying within hobby spaces, through the power of music.
                    </p>
                    <Button type="button" size="lg" className="cursor-pointer" onClick={moreInfoHandler}>More
                        information</Button>
                </div>

                <div className="grid grid-cols-2 gap-3 md:grid md:gap-2 md:grid-cols-3 md:grid-rows-2">
                    <SubscribePanel className="w-full col-span-2 md:col-span-3 row-span-1"/>

                    <Advert height={120} className="row-span-1 col-span-2 md:col-span-3"/>

                    <Link
                        className="flex flex-col justify-end bg-green-700 hover:bg-green-800 text-white font-semibold p-3 col-span-2 md:col-span-1 h-10 min-h-[6rem] md:h-auto rounded-sm leading-none"
                        href={route('donations')}>
                        Donor Wall
                    </Link>

                    <Link
                        className="flex flex-col justify-end bg-red-500 hover:bg-red-600 text-white font-semibold p-3 col-span-1 rounded-sm leading-none"
                        href="https://youtube.com/@silentmodetv" target="_blank">
                        SilentMode
                        <br/>
                        <span className="text-sm">on YouTube</span>
                    </Link>

                    <BrickTherapyLink className="rounded-sm over overflow-hidden col-span-1"/>
                </div>

            </div>
        </div>
    )
}
