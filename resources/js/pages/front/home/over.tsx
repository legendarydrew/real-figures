import { Head, usePage } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import AboutBanner from '@/components/front/about-banner';
import { Advert } from '@/components/advert';
import Heading from '@/components/heading';
import { ActImage } from '@/components/ui/act-image';
import { LanguageFlag } from '@/components/language-flag';

const HomeContestOverPage: React.FC = () => {

    const { results } = usePage().props;

    return (
        <>
            <Head title="The Contest Is Over.">
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-zinc-600 text-white py-10 px-5">

                <div className="max-w-4xl mx-auto flex flex-col gap-5">
                    <div className="text-center">
                        <Heading title="Thank you for your support!"/>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Ab, aspernatur corporis cumque cupiditate expedita explicabo iure labore molestiae non
                            odio
                            officia pariatur quam quidem rerum saepe sapiente similique veritatis, voluptates.</p>
                    </div>

                    <ul className="grid gap-5 grid-cols-2 lg:grid-cols-5">
                        {/* Winner(s)! */}
                        {results.winners.map((song) => (
                            <li key={song.id} className="display-text text-shadow-md col-span-2 row-span-2">
                                <div className="w-full text-left bg-secondary/30 rounded-md leading-none relative cursor-pointer">
                                    <ActImage act={song.act} size="full"/>
                                    <p className="absolute top-0 uppercase p-5 text-xl text-yellow-300">Winner</p>
                                    <div className="p-3 lg:p-5 absolute bottom-0 w-full text-xl leading-tight">{song.act.name}</div>
                                </div>
                            </li>
                        ))}

                        {/* Runners-up! */}
                        {results.runners_up.map((song) => (
                            <li key={song.id} className="display-text text-shadow-md col-span-1 row-span-1">
                                <div
                                    className="w-full text-left bg-secondary/30 rounded-md leading-none relative cursor-pointer">
                                    <ActImage act={song.act} size="full"/>
                                    <div className="p-3 absolute bottom-0 w-full text-base leading-tight">
                                        {song.act.name}
                                        <p className="uppercase text-sm text-indigo-200">Runner-up</p>
                                    </div>
                                </div>
                            </li>
                        ))}
                    </ul>
                    <div>
                        {/* Display other entries here */}
                    </div>
                    <div className="text-center">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum labore mollitia nam nihil saepe
                            suscipit voluptas. Cum cumque dignissimos dolor dolorum eum, exercitationem laudantium,
                            magnam
                            optio sequi sit sunt, velit?</p>
                    </div>

                </div>
            </div>
            <Advert className="mx-auto text-center" height={240}/>
            <AboutBanner/>
        </>
    );
}


HomeContestOverPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default HomeContestOverPage;
