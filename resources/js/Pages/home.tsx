import { Head } from '@inertiajs/react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import FrontLayout from '@/layouts/front-layout';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';

export default function Home() {

    return (
        <FrontLayout>
            <Head title="CATAWOL Records presents: Real Figures Don't F.O.L.D">
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-indigo-200 py-10 px-5">
                <div className="max-w-5xl mx-auto flex items-center gap-4">

                    <PlaceholderPattern className="stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                    <div className="w-1/2 ml-10">
                        <h1 className="mb-3 text-4xl font-medium text-shadow-md">
                            32 Acts.<br/>1 Anthem.
                        </h1>
                        <p className="text-base w-3/4">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                            Asperiores
                            commodi consequuntur deserunt eaque eius enim eos inventore.</p>
                    </div>

                </div>
            </div>

            <div className="bg-gray-500 text-white py-10 px-5">
                <div className="max-w-5xl mx-auto grid gap-4 md:grid-cols-2">

                    <div className="pr-10">
                        <Heading title="About the Contest"/>
                        <p className="mb-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab delectus
                            dolorum expedita facere
                            fugit illo in neque non perferendis, possimus quasi repellendus repudiandae soluta sunt,
                            suscipit, vel velit veritatis voluptatum?</p>
                        <Button type="button">More information</Button>
                    </div>

                    <div className="grid gap-2 md:grid-cols-2 md:grid-rows-2">
                        <PlaceholderPattern
                            className="w-full md:col-span-1 stroke-neutral-100/20 dark:stroke-neutral-100/20"/>
                        <PlaceholderPattern
                            className="w-full md:col-span-1 stroke-neutral-100/20 dark:stroke-neutral-100/20"/>
                        <PlaceholderPattern
                            className="w-full col-span-2 stroke-neutral-100/20 dark:stroke-neutral-100/20"/>
                    </div>

                </div>
            </div>
        </FrontLayout>
    );
}
