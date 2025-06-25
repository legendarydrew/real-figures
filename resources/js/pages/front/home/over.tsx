import { Advert } from '@/components/mode/advert';
import AboutBanner from '@/components/front/about-banner';
import { SongBanner } from '@/components/mode/song-banner';
import { ActImage } from '@/components/mode/act-image';
import { Button } from '@/components/ui/button';
import FrontLayout from '@/layouts/front-layout';
import { Head, Link, usePage } from '@inertiajs/react';

const HomeContestOverPage: React.FC = () => {
    const { results } = usePage().props;

    return (
        <>
            <Head title="The Contest Is Over.">
                <meta name="description"
                      content="Discover the CATAWOL Records Song Contest — 32 Acts, one anthem, and your vote decides the winner. Follow the journey, cast your vote, and support music that makes a difference."/>
            </Head>

            <div className="bg-zinc-600 px-5 py-10 text-white">
                <div className="mx-auto flex max-w-4xl flex-col gap-5">
                    <div className="text-center">
                        <h1 className="display-text mb-3 text-4xl text-shadow-md">
                            The Results Are In &mdash; And We Couldn't Have Done It Without You.
                        </h1>
                        <p className="mx-auto mb-3 text-base md:w-3/4">
                            The votes are counted. The winners are announced. <b>The anthem has been chosen.</b>
                        </p>
                    </div>

                    <ul className="grid grid-cols-2 gap-5 lg:grid-cols-5">
                        {/* Winner(s)! */}
                        {results.winners.map((song) => (
                            <li key={song.id} className="display-text col-span-2 row-span-2 text-shadow-md">
                                <div className="relative w-full rounded-md bg-yellow-200/30 text-left leading-none">
                                    <ActImage act={song.act} size="full" />
                                    <p className="absolute top-0 p-5 text-xl text-yellow-300 uppercase">Winner</p>
                                    <div className="absolute bottom-0 w-full p-3 text-xl leading-tight lg:p-5">{song.act.name}</div>
                                </div>
                            </li>
                        ))}

                        {/* Runners-up! */}
                        {results.runners_up.map((song) => (
                            <li key={song.id} className="display-text col-span-1 row-span-1 text-shadow-md">
                                <div className="bg-secondary/30 relative w-full rounded-md text-left leading-none">
                                    <ActImage act={song.act} size="full" />
                                    <div className="absolute bottom-0 w-full p-3 text-base leading-tight">
                                        {song.act.name}
                                        <p className="text-sm text-indigo-200 uppercase">Runner-up</p>
                                    </div>
                                </div>
                            </li>
                        ))}
                    </ul>

                    {/* Other entered entries. */}
                    {results.others.length ? (
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                            {results.others.map((song) => (
                                <SongBanner key={song.id} song={song} />
                            ))}
                        </div>
                    ) : (
                        ''
                    )}

                    <div className="flex flex-col gap-1 text-center">
                        <p>
                            Whether your favourite song made it to the top or not, <b>you helped make this contest unforgettable.</b>
                        </p>
                        <p>Your support amplified voices, celebrated creativity, and helped shine a light on an important cause.</p>
                        <p>
                            From all of us at CATAWOL Records &mdash; <b>thank you.</b>
                        </p>

                        {/* Styling a Link as a Button. https://medium.com/@bryanmylee/aschild-in-react-svelte-vue-and-solid-for-render-delegation-645c73650ced */}
                        <Button asChild variant="secondary" className="mx-auto mt-3 px-10 py-3 text-base" size="lg">
                            <Link href={route('votes')}>Voting Breakdown</Link>
                        </Button>
                    </div>
                </div>
            </div>
            <Advert className="mx-auto text-center" height={240} />
            <AboutBanner />
        </>
    );
};

HomeContestOverPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default HomeContestOverPage;
