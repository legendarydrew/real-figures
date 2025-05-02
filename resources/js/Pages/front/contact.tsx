import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';

const ContactPage: React.FC = () => {

    return (
        <>
            <Head title="Contact"/>

            <FrontContent>
                <Heading title="Contact Us"/>
                <p>Lakers in 🖐!</p>
            </FrontContent>
        </>
    )
}

ContactPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default ContactPage;
