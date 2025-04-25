import { Head } from '@inertiajs/react';
import { FrontHeader } from '@/components/front/front-header';
import { FrontFooter } from '@/components/front/front-footer';

export default function Welcome() {

    return (
        <>
            <Head title="CATAWOL Records presents: Real Figures Don't F.O.L.D">
                {/* Any tags to go in the <head> section to here. */}
            </Head>
            <div
                className="flex min-h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] lg:justify-center dark:bg-[#0a0a0a]">

                <FrontHeader/>
                <main className="flex-grow w-full">
                    <div className="h-full overflow-y-auto">
                        <h1 className="mb-1 font-medium">
                            Real Figures Don't F.O.L.D
                        </h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad consequuntur eveniet,
                            perferendis
                            praesentium quasi veniam. Aperiam architecto autem debitis dolor, illum optio pariatur
                            praesentium sit ullam vel! Est, modi, sit.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad assumenda, ea eligendi ipsam
                            laboriosam maxime modi nesciunt perferendis placeat provident quo tempora unde. Ab,
                            accusantium autem minus perferendis qui quidem.</p>
                        <p>Eligendi harum incidunt inventore
                            placeat possimus quasi quis sint unde voluptate? Accusamus asperiores beatae culpa debitis
                            dignissimos magnam perferendis, tempora velit. At atque dicta dolor, iste numquam possimus
                            quod
                            sunt.</p>
                        <p>A ab accusamus architecto commodi consequuntur corporis cum dignissimos dolores
                            eligendi explicabo fugiat illum, incidunt iusto minus mollitia natus numquam omnis quidem
                            recusandae repellat sapiente similique sit ut vitae voluptas.</p>
                    </div>
                </main>

                <FrontFooter/>
            </div>
        </>
    );
}
