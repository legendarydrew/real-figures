import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { Advert } from '@/components/advert';
import { router } from '@inertiajs/react';

export default function AboutBanner() {

    const moreInfoHandler = () => {
        router.visit('about');
    };

    return (
        <div className="bg-gray-200 py-10 px-5">
            <div className="max-w-5xl mx-auto grid gap-4 md:grid-cols-2">

                <div className="pr-10">
                    <Heading title="About the Contest"/>
                    <p className="mb-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab
                        delectus
                        dolorum expedita facere
                        fugit illo in neque non perferendis, possimus quasi repellendus repudiandae soluta
                        sunt,
                        suscipit, vel velit veritatis voluptatum?</p>
                    <Button type="button" className="cursor-pointer" onClick={moreInfoHandler}>More
                        information</Button>
                </div>

                <div className="grid gap-2 md:grid-cols-2 md:grid-rows-2">
                    <PlaceholderPattern
                        className="w-full md:col-span-1 stroke-neutral-900/20 dark:stroke-neutral-100/20"/>
                    <PlaceholderPattern
                        className="w-full md:col-span-1 stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                    <Advert className="w-full h-[12rem] col-span-2"/>
                </div>

            </div>
        </div>
    )
}
