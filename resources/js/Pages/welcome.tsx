import { Head } from '@inertiajs/react';
import { FrontHeader } from '@/components/front/front-header';
import { FrontFooter } from '@/components/front/front-footer';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { FrontContent } from '@/components/front/front-content';

export default function Welcome() {

    return (
        <>
            <Head title="CATAWOL Records presents: Real Figures Don't F.O.L.D">
                {/* Any tags to go in the <head> section to here. */}
            </Head>
            <div
                className="flex h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] lg:justify-center dark:bg-[#0a0a0a]">

                <FrontHeader/>
                <FrontContent>
                    <h1 className="mb-1 text-2xl font-medium">
                        Real Figures Don't F.O.L.D
                    </h1>
                    <p className="text-lg mb-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad
                        consequuntur eveniet,
                        perferendis
                        praesentium quasi veniam. Aperiam architecto autem debitis dolor, illum optio pariatur
                        praesentium sit ullam vel! Est, modi, sit.</p>

                    <PlaceholderPattern
                        className="w-1/3 h-[12rem] mb-3 stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                    <p className="mb-3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad assumenda, ea
                        eligendi ipsam
                        laboriosam maxime modi nesciunt perferendis placeat provident quo tempora unde. Ab,
                        accusantium autem minus perferendis qui quidem.</p>

                    <p className="mb-3">Eligendi harum incidunt inventore
                        placeat possimus quasi quis sint unde voluptate? Accusamus asperiores beatae culpa debitis
                        dignissimos magnam perferendis, tempora velit. At atque dicta dolor, iste numquam possimus
                        quod
                        sunt.</p>

                    <p className="mb-3">A ab accusamus architecto commodi consequuntur corporis cum dignissimos dolores
                        eligendi explicabo fugiat illum, incidunt iusto minus mollitia natus numquam omnis quidem
                        recusandae repellat sapiente similique sit ut vitae voluptas.</p>
                </FrontContent>
                <FrontFooter/>
            </div>
        </>
    );
}
