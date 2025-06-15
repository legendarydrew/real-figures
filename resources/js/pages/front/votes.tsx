import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';
import { Advert } from '@/components/advert';
import { AboutSilentmodePanel } from '@/pages/front/about/about-silentmode';

const VotesPage: React.FC = () => {

    return (
        <>
            <Head title="Breakdown of votes"/>

            <FrontContent>
                <Heading title="Breakdown of votes" className="mb-5"/>

                <div className="grid gap-5 lg:grid-cols-4">

                    <div className="col-span-3">
                        ...
                    </div>
                    <Advert className="col-span-1"/>
                </div>

                <AboutSilentmodePanel opened={() => trackOpenPanelHandler('About SilentMode')}/>

                <Advert className="mx-auto my-3 text-center" height={90}/>
            </FrontContent>
        </>
    );
};

AboutPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default AboutPage;
