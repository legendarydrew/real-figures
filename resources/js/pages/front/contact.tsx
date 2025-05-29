import { Head, useForm } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';
import InputError from '@/components/input-error';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { LoadingButton } from '@/components/ui/loading-button';
import { Advert } from '@/components/advert';
import { ChangeEvent } from 'react';
import { TurnstileWidget } from '@/components/turnstile-widget';
import { useAnalytics } from '@/hooks/use-analytics';
import { Checkbox } from '@/components/ui/checkbox';
import { Alert } from '@/components/alert';

interface ContactPageProps {
    success: boolean;
}

const ContactPage: React.FC<ContactPageProps> = ({ success }) => {

    const { data, setData, errors, setError, post, processing } = useForm({
        name: '',
        email: '',
        body: '',
        subscribe: false,
        token: ''
    });

    const { trackEvent } = useAnalytics();

    const nameChangeHandler = (e: ChangeEvent): void => {
        setData('name', e.target.value);
        setError('name', '');
    };

    const emailChangeHandler = (e: ChangeEvent): void => {
        setData('email', e.target.value);
        setError('email', '');
    };

    const bodyChangeHandler = (e: ChangeEvent): void => {
        setData('body', e.target.value);
        setError('body', '');
    };

    const subscribeChangeHandler = (value: boolean): void => {
        setData('subscribe', value);
    };

    const verifyHandler = (token: string): void => {
        setData('token', token);
    };

    const submitHandler = (e: SubmitEvent): void => {
        e.preventDefault();

        if (data.token) {
            post('/api/messages', {
                only: ['success'],
                preserveUrl: true,
                onSuccess: () => {
                    trackEvent({ category: 'Action', action: 'Contact', nonInteraction: false });
                    if (data.subscribe) {
                        trackEvent({
                            category: 'Action',
                            action: 'Subscribe',
                            label: 'Contact form',
                            nonInteraction: false
                        });
                    }
                }
            });
        }
    };

    return (
        <>
            <Head title="Contact"/>

            <FrontContent>
                <Heading title="Get in touch!"/>

                <div className="flex flex-col lg:flex-row gap-5">

                    <div className="lg:w-2/5 content">
                        <p>Have a question about the contest? Want to know more about the voting process, submissions,
                            or Golden Buzzer? We’d love to hear from you!</p>
                        <p>Fill out the form and we'll get back to you as soon as we can.</p>
                        <p>Whether you're an artist, a voter, a supporter, or just curious &ndash; we’re all ears.</p>
                        <p className="italic">Music connects us... so don't be shy!</p>

                        <Advert className="mt-3 h-[90px] lg:h-[160px]"/>
                    </div>

                    <div className="lg:w-3/5">
                        {success ? (
                            <Alert type="success" message="Your message has been sent." />
                        ) : (
                            <form onSubmit={submitHandler} className="flex flex-col gap-3">

                                <div>
                                    <Label className="sr-only" htmlFor="contactName">Your name</Label>
                                    <Input id="contactName" placeholder="Your name" data={data.name}
                                           onChange={nameChangeHandler} disabled={processing}/>
                                    <InputError message={errors.name}/>
                                </div>

                                <div>
                                    <Label className="sr-only" htmlFor="contactName">Your email address</Label>
                                    <Input id="contactName" type="email" placeholder="Your email address"
                                           value={data.email}
                                           onChange={emailChangeHandler} disabled={processing}/>
                                    <InputError message={errors.email}/>
                                </div>

                                <div>
                                    <Label className="sr-only" htmlFor="contactName">Your message</Label>
                                    <Textarea id="contactName" rows={8} placeholder="Your message (min. 20 characters)"
                                              value={data.body}
                                              onChange={bodyChangeHandler} disabled={processing}/>
                                    <InputError message={errors.body}/>
                                </div>

                                <div className="flex gap-2 items-center">
                                    <Checkbox id="contactSubscribe" onCheckedChange={subscribeChangeHandler}/>
                                    <Label htmlFor="contactSubscribe">I'd like updates about the contest.</Label>
                                </div>

                                <TurnstileWidget onVerify={verifyHandler}/>

                                <div className="flex flex-col gap-3 lg:flex-row justify-between lg:items-center">
                                    <p className="text-sm text-center lg:text-left">Your details will not be shared with
                                        anyone.</p>
                                    <LoadingButton size="lg" type="submit" disabled={!data.token}
                                                   isLoading={processing}>Send
                                        Message</LoadingButton>
                                </div>
                            </form>
                        )}
                    </div>

                </div>

            </FrontContent>
        </>
    )
}

ContactPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default ContactPage;
