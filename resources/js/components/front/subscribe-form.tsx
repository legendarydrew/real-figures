import { Input } from '@/components/ui/input';
import { LoadingButton } from '@/components/mode/loading-button';
import { ChangeEvent, useState } from 'react';
import axios from 'axios';
import { Alert } from '@/components/mode/alert';

export const SubscribeForm: React.FC = ({ className, ...props }) => {

    const [email, setEmail] = useState<string>('');
    const [isProcessing, setIsProcessing] = useState<boolean>(false);
    const [hasError, setHasError] = useState<string>('');
    const [wasSuccessful, setWasSuccessful] = useState<boolean>(false);

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

        axios.post('/api/subscribers', { email })
            .then(() => {
                setWasSuccessful(true);
                globalThis.trackEvent({ action: 'New subscriber', nonInteraction: false });
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
            <b>Thanks for subscribing!</b> A confirmation email has been sent.
        </Alert>
    ) : (
        <form onSubmit={submitHandler} className={className} {...props}>
            <div className="flex items-center">
                <label htmlFor="subscribeEmail" className="sr-only">Enter your email address...</label>
                <Input id="subscribeEmail" className="rounded-r-none" type="email"
                       value={email}
                       onChange={emailChangeHandler}
                       autoComplete="email"
                       placeholder="Enter your email address..."/>
                <LoadingButton type="submit" className="h-9 rounded-l-none" disabled={!email} isLoading={isProcessing}>I'm
                    in!</LoadingButton>
            </div>
            {hasError ? <Alert type="error" message={hasError}/> : ''}
        </form>
    );
};
