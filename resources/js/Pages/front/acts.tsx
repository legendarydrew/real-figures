import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';
import { Act } from '@/types';
import { ActItem } from '@/components/act-item';

interface ActsPageProps {
    acts?: Act[];
}

const ActsPage: React.FC<ActsPageProps> = ({ acts }) => {

    const showActHandler = (act) => {
        console.log('show me', act.name);
    };

    return (
        <>
            <Head title="Acts"/>

            <FrontContent>
                <Heading title="Competing Acts"/>

                {acts?.length ? (
                    <div className="grid auto-rows-min gap-1 md:grid-cols-3 lg:grid-cols-4">
                        {acts.map((act: Act) => (
                            <ActItem key={act.id} act={act} className={act.has_profile ? 'cursor-pointer' : ''}
                                     onClick={() => showActHandler(act)}/>
                        ))}
                    </div>
                ) : (
                    <Nothing>No Acts have entered the contest!</Nothing>
                )
                }
            </FrontContent>
        </>
    );
};

ActsPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default ActsPage;
