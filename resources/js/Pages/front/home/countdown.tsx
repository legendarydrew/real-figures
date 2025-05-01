import { Head, router } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import Heading from '@/components/heading';
import { CountdownTimer } from '@/components/ui/countdown-timer';
import { Advert } from '@/components/advert';
import ContestOutline from '@/components/front/contest-outline';
import GoldenBuzzerBanner from '@/components/front/golden-buzzer-banner';
import AboutBanner from '@/components/front/about-banner';
import ContestHeader from '@/components/front/contest-header';

const HomeRound: React.FC = ({ stage, countdown }) => {

    const countdownEndHandler = () => {
        router.reload({
            fresh: true
        });
    }

    return (
        <>
            <Head>
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-blue-100 py-10 px-5">
                <div className="max-w-5xl mx-auto">

                    <Heading title={stage.title} description={stage.description}/>
                    <div className="flex flex-col gap-1 items-center">
                        <CountdownTimer size="large" timestamp={countdown} onEnd={countdownEndHandler}/>
                        <span className="text-sm font-semibold">before voting begins!</span>
                    </div>

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

HomeRound.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default HomeRound;
