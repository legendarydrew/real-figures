import { Head, router } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import Heading from '@/components/heading';
import HeadingSmall from '@/components/heading-small';
import { ActImage } from '@/components/ui/act-image';
import { CountdownTimer } from '@/components/ui/countdown-timer';
import ContestHeader from '@/components/front/contest-header';
import { Advert } from '@/components/advert';
import ContestOutline from '@/components/front/contest-outline';
import GoldenBuzzerBanner from '@/components/front/golden-buzzer-banner';
import AboutBanner from '@/components/front/about-banner';

const HomeCurrentRoundPage: React.FC = ({ stage, currentRound, previousRounds, countdown }) => {

    const countdownEndHandler = () => {
        router.reload();
    }

    return (
        <>
            <Head>
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-blue-100 py-10 px-5">
                <div className="max-w-5xl mx-auto">
                    {currentRound && (
                        <>
                            <div className="flex justify-between items-start">
                                <Heading title={currentRound.title} description={stage.description}/>
                                <div className="flex gap-1 items-center">
                                    <span className="text-sm">Voting ends in</span>
                                    <CountdownTimer timestamp={countdown} onEnd={countdownEndHandler}/>
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

            <ContestHeader/>
            <Advert className="mx-auto max-h-[12rem]"/>
            <ContestOutline/>
            <GoldenBuzzerBanner/>
            <AboutBanner/>
        </>
    );
};

HomeCurrentRoundPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default HomeCurrentRoundPage;
