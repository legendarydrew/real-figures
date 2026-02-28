import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { LoadingButton } from '@/components/mode/loading-button';
import { ChangeEvent, useState } from 'react';
import { TurnstileWidget } from '@/components/mode/turnstile-widget';
import { Checkbox } from '@/components/ui/checkbox';
import { Alert } from '@/components/mode/alert';
import axios from 'axios';

const ContactForm: React.FC = () => {

    const { data, setData, errors, setError } = useForm({
        name: '',
        email: '',
        body: '',
        subscribe: false,
        token: ''
    });

    const [success, setSuccess] = useState<boolean>(false);
    const [processing, setProcessing] = useState<boolean>(false);
    const [failed, setFailed] = useState<boolean>(false);

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

        if (data.token && !processing) {
            setFailed(false);
            setProcessing(true);
            axios.post('/api/messages', data)
                .then(() => {
                    globalThis.trackEvent({
                        action: 'Message sent',
                        label: data.subscribe ? 'Subscribed' : 'Not subscribed',
                        nonInteraction: false
                    });
                    setSuccess(true);
                })
                .catch((response) => {
                    if (response.response.status === 422) {
                        const errors = response.response.data.errors;
                        Object.keys(errors).forEach((key: never) => {
                            setError(key, errors[key]);
                        });
                    } else {
                        setFailed(true);
                    }
                })
                .finally(() => {
                    setProcessing(false);
                });
        }
    };

    return (
        success ? (
            <Alert type="success" message="Your message has been sent."/>
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
                    <Textarea id="contactName" rows={8}
                              placeholder="Your message (min. 20 characters)"
                              value={data.body}
                              onChange={bodyChangeHandler} disabled={processing}/>
                    <InputError message={errors.body}/>
                </div>

                <div className="flex gap-2 items-center">
                    <Checkbox id="contactSubscribe" onCheckedChange={subscribeChangeHandler}/>
                    <Label htmlFor="contactSubscribe">I'd like updates about the contest.</Label>
                </div>

                <TurnstileWidget onVerify={verifyHandler}/>

                {failed ? (<Alert type="error" message="Your message could not be sent, please try again."/>) : ''}

                <LoadingButton size="lg" variant="primary" type="submit" disabled={!data.token} isLoading={processing}>
                    Send Message
                </LoadingButton>

                <p className="text-xs text-center lg:text-left">Your details will not be shared with anyone.</p>
            </form>
        )
    );
}

export default ContactForm;
