import { Head, Link, usePage } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import AboutBanner from '@/components/front/about-banner';
import { Advert } from '@/components/advert';
import { ActImage } from '@/components/ui/act-image';
import { SongBanner } from '@/components/song-banner';

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
                        <h1 className="display-text text-4xl text-shadow-md mb-3">
                            The Results Are In &mdash; And We Couldn't Have Done It Without You.
                        </h1>
                        <p className="text-base mb-3 md:w-3/4 mx-auto">
                            The votes are counted. The winners are announced. <b>The anthem has been chosen.</b>
                        </p>
                    </div>

                    <ul className="grid gap-5 grid-cols-2 lg:grid-cols-5">
                        {/* Winner(s)! */}
                        {results.winners.map((song) => (
                            <li key={song.id} className="display-text text-shadow-md col-span-2 row-span-2">
                                <div
                                    className="w-full text-left bg-yellow-200/30 rounded-md leading-none relative">
                                    <ActImage act={song.act} size="full"/>
                                    <p className="absolute top-0 uppercase p-5 text-xl text-yellow-300">Winner</p>
                                    <div
                                        className="p-3 lg:p-5 absolute bottom-0 w-full text-xl leading-tight">{song.act.name}</div>
                                </div>
                            </li>
                        ))}

                        {/* Runners-up! */}
                        {results.runners_up.map((song) => (
                            <li key={song.id} className="display-text text-shadow-md col-span-1 row-span-1">
                                <div
                                    className="w-full text-left bg-secondary/30 rounded-md leading-none relative">
                                    <ActImage act={song.act} size="full"/>
                                    <div className="p-3 absolute bottom-0 w-full text-base leading-tight">
                                        {song.act.name}
                                        <p className="uppercase text-sm text-indigo-200">Runner-up</p>
                                    </div>
                                </div>
                            </li>
                        ))}
                    </ul>

                    {/* Other entered entries. */}
                    {results.others.length ? (
                        <div className="grid gap-3 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                            {results.others.map((song) => (
                                <SongBanner key={song.id} song={song}/>
                            ))}
                        </div>
                    ) : ''}

                    <div className="flex flex-col gap-1 text-center">
                        <p>
                            Whether your favourite song made it to the top or not, <b>you helped make this contest
                            unforgettable.</b>
                        </p>
                        <p>
                            Your support amplified voices, celebrated creativity, and helped shine a light on an
                            important cause.
                        </p>
                        <p>
                            From all of us at CATAWOL Records &mdash; <b>thank you.</b>
                        </p>

                        <Link className="display-text py-3 px-5 rounded-md bg-indigo-200 hover:bg-indigo-300 text-indigo-900 mt-3 mx-auto" href={route('votes')}>Voting
                            Breakdown</Link>
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
