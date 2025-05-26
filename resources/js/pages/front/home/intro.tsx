import { Head } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import ContestHeader from '@/components/front/contest-header';
import { Advert } from '@/components/advert';
import ContestOutline from '@/components/front/contest-outline';
import GoldenBuzzerBanner from '@/components/front/golden-buzzer-banner';
import AboutBanner from '@/components/front/about-banner';

const IntroPage: React.FC = () => {

    return (
        <>
            <Head>
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <ContestHeader/>
            <Advert className="mx-auto h-[60px] md:h-[90px] text-center"/>
            <ContestOutline/>
            <GoldenBuzzerBanner/>
            <AboutBanner/>
        </>
    );
};

IntroPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default IntroPage;
