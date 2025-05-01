import { Head, router } from '@inertiajs/react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import FrontLayout from '@/layouts/front-layout';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import HeadingSmall from '@/components/heading-small';
import { ActImage } from '@/components/ui/act-image';
import { CountdownTimer } from '@/components/ui/countdown-timer';

export default function HomeRound({ stage, currentRound, previousRounds, countdown }) {

    const moreInfoHander = () => {
        router.visit('about');
    };

    return (
        <FrontLayout>
            <Head>
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-indigo-200 py-10 px-5">
                <div className="max-w-5xl mx-auto">
                    {currentRound && (
                        <>
                            <div className="flex justify-between">
                                <Heading title={currentRound.title} description={stage.description}/>
                                <div>
                                    Voting ends in <CountdownTimer timestamp={countdown}/>
                                </div>
                            </div>
                            <ul className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                {currentRound.songs.map((song) => (
                                    <li className="bg-secondary/30 rounded-md leading-none relative" key={song.id}>
                                        <ActImage act={song.act} size="full"/>
                                        <div className="p-5 absolute bottom-0">
                                            <div className="text-lg font-semibold">{song.act.name}</div>
                                            <div className="text-base font-semibold">{song.title}</div>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        </>
                    )}
                    {previousRounds.length ? (
                        <>
                            <Heading title="Previous Rounds"/>
                            {previousRounds.map((round) => (
                                <div key={round.id} className="mb-2">
                                    <HeadingSmall title={round.title}/>
                                    <ul className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                        {round.songs.map((song) => (
                                            <li className="bg-secondary/15 rounded-md leading-none relative"
                                                key={song.id}>
                                                <ActImage act={song.act} size="full"/>
                                                <div className="p-5 absolute bottom-0">
                                                    <div className="text-base font-semibold">{song.act.name}</div>
                                                    <div className="text-sm font-semibold">{song.title}</div>
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            ))}
                        </>
                    ) : ''}
                </div>
            </div>

            <div className="bg-indigo-200 py-10 px-5">
                <div className="max-w-5xl mx-auto flex items-center gap-4">

                    <PlaceholderPattern className="stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                    <div className="w-1/2 ml-10">
                        <h1 className="mb-3 text-4xl font-medium text-shadow-md">
                            32 Acts.<br/>1 Anthem.
                        </h1>
                        <p className="text-base w-3/4">
                            We're raising awareness about bullying through music - and <b>you</b> help pick the
                            winner!
                        </p>
                    </div>

                </div>
            </div>

            <div className="bg-gray-500 text-white py-10 px-5">
                <div className="max-w-5xl mx-auto grid gap-5 md:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <HeadingSmall title="Stage 1: Knockout Rounds"/>
                        <ul className="list-disc text-sm mx-3 mb-3">
                            <li>Acts submit Songs based on special lyrics.</li>
                            <li>8 Rounds, 4 Songs each.</li>
                            <li>Vote for your <b>top 3 favourites</b> in each Round.</li>
                            <li>8 Round winners + 2 top runners-up = 10 finalists!</li>
                        </ul>
                    </div>

                    <div>
                        <HeadingSmall title="Stage 2: Finals"/>
                        <ul className="list-disc text-sm mx-3 mb-3">
                            <li>Finalists update their Songs with a <b>new second verse</b>.</li>
                            <li>Vote again for your <b>top 3 favourites</b>.</li>
                            <li>1 Grand Winner + 3 Runners-up will be crowned!</li>
                        </ul>
                    </div>

                    <div>
                        <HeadingSmall title="🏆 Prizes"/>
                        <ul className="list-disc text-sm mx-3 mb-3">
                            <li>Winning Acts become <b>3D-printed figures</b>.</li>
                            <li>The Grand Winner's Act gets a <b>custom LEGO minifigure!</b></li>
                            <li>The Winning Song becomes our <b>official anthem</b>.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div className="bg-yellow-700 text-white py-10 px-5">
                <div className="max-w-5xl mx-auto flex items-center gap-4">

                    <PlaceholderPattern className="stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                    <div className="w-1/2 ml-10">
                        <h2 className="text-2xl font-semibold mb-2">Golden Buzzer</h2>
                        <p>Love a Song? <b>Golden Buzzer it</b> with a donation to give it extra honours (without
                            affecting the vote count)!</p>
                    </div>
                </div>
            </div>

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
                        <Button type="button" className="cursor-pointer" onClick={moreInfoHander}>More
                            information</Button>
                    </div>

                    <div className="grid gap-2 md:grid-cols-2 md:grid-rows-2">
                        <PlaceholderPattern
                            className="w-full md:col-span-1 stroke-neutral-900/20 dark:stroke-neutral-100/20"/>
                        <PlaceholderPattern
                            className="w-full md:col-span-1 stroke-neutral-900/20 dark:stroke-neutral-100/20"/>
                        <PlaceholderPattern
                            className="w-full col-span-2 stroke-neutral-600/20 dark:stroke-neutral-100/20"/>
                    </div>

                </div>
            </div>
        </FrontLayout>
    );
}
