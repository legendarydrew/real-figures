import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';
import { Nothing } from '@/components/nothing';
import HeadingSmall from '@/components/heading-small';
import { Donation } from '@/types';

interface DonationPageProps {
    donations: Donation[];
    buzzers: Donation[];
}

const ContactPage: React.FC<DonationPageProps> = ({ donations, buzzers }) => {

    return (
        <>
            <Head title="Donation Wall"/>

            <FrontContent>
                <Heading title="Donation Wall"/>

                <p>Thank you!</p>

                <div className="flex gap-5">
                    <div className="w-1/2">
                        <HeadingSmall title="Generous Donations"/>
                        {donations.length ? (
                            <ul>
                                {donations.map((donation) => (
                                    <li key={donation.id}>{donation.email}</li>
                                ))}
                            </ul>
                        ) : (
                            <Nothing>Be the first to make a donation!</Nothing>
                        )}
                    </div>

                    <div className="w-1/2">
                        <HeadingSmall title="Golden Buzzers"/>
                        {buzzers.length ? (
                            <ul>
                                {buzzers.map((donation) => (
                                    <li key={donation.id}>{donation.email}</li>
                                ))}
                            </ul>
                        ) : (
                            <Nothing>Will you be the first to hit a Golden Buzzer?</Nothing>
                        )}
                    </div>
                </div>

            </FrontContent>
        </>
    )
}

ContactPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default ContactPage;
