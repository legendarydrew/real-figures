import { Input } from '@/components/ui/input';
import { LoadingButton } from '@/components/ui/loading-button';
import { ChangeEvent, useState } from 'react';
import axios from 'axios';
import { Alert } from '@/components/alert';
import { useAnalytics } from '@/hooks/use-analytics';

export const SubscribeForm: React.FC = ({ className, ...props }) => {

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
                setHasError(error.response.data.message);
            })
            .finally(() => {
                setIsProcessing(false);
            });
    };

    return wasSuccessful ? (
        <Alert type="success">
            <b>Thanks for subscribing!</b> You're on the list, and a confirmation email has been sent.
        </Alert>
    ) : (
        <form onSubmit={submitHandler} className={className} {...props}>
            <div className="flex items-center">
                <Input className="bg-white dark:bg-white/20 dark:border-none rounded-r-none" type="email" value={email}
                       onChange={emailChangeHandler}
                       placeholder="Enter your email address..."/>
                <LoadingButton type="submit" className="rounded-l-none" isLoading={isProcessing}>I'm in!</LoadingButton>
            </div>
            {hasError ? <Alert type="error" message={hasError}/> : ''}
        </form>
    );
};
