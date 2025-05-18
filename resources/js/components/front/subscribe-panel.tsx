import HeadingSmall from '@/components/heading-small';
import { Input } from '@/components/ui/input';
import { LoadingButton } from '@/components/ui/loading-button';
import { ChangeEvent, useState } from 'react';
import axios from 'axios';
import { MailCheck } from 'lucide-react';
import { Alert } from '@/components/alert';
import { useAnalytics } from '@/hooks/use-analytics';

export const SubscribePanel: React.FC = ({ ...props }) => {

    const [email, setEmail] = useState<string>('');
    const [isProcessing, setIsProcessing] = useState<boolean>(false);
    const [hasError, setHasError] = useState<string>('');
    const [wasSuccessful, setWasSuccessful] = useState<boolean>(false);

    const { trackEvent } = useAnalytics();

    const emailChangeHandler = (e: ChangeEvent<HTMLInputElement>) => {
        setEmail(e.currentTarget.value);
        setHasError('');
    }

    const submitHandler = (e: SubmitEvent): void => {
        e.preventDefault();
        if (isProcessing) {
            return;
        }

        setHasError('');
        setIsProcessing(true);

        axios.post(route('subscribe'), { email })
            .then(() => {
                setWasSuccessful(true);
                trackEvent({ category: 'Action', action: 'Subscribe', label: 'Panel', nonInteraction: false });
            })
            .catch((error) => {
                console.log(error);
                setHasError(error.response.data);
            })
            .finally(() => {
                setIsProcessing(false);
            });
    };

    return (
        <form onSubmit={submitHandler} className="rounded-md p-3" {...props}>
            <HeadingSmall title="Subscribe for updates!"/>
            <p className="my-3 text-sm">Stay updated about the contest's progress, and be informed about when it's time
                to cast your votes! Your
                details will not be used for anything else.</p>
            {wasSuccessful ? (
                <div className="flex items-center gap-3 p-3 text-sm">
                    <MailCheck/>
                    <p>
                        <b>Thank you!</b> A confirmation email has been sent.
                    </p>
                </div>
            ) : (
                <div className="flex items-center">
                    <Input className="bg-white rounded-r-none" type="email" value={email} onChange={emailChangeHandler}
                           placeholder="Enter your email address..."/>
                    <LoadingButton type="submit" className="rounded-l-none" isLoading={isProcessing}>I'm
                        in!</LoadingButton>
                </div>
            )}
            {hasError && <Alert type="error" message={hasError}/>}
        </form>
    )
}
