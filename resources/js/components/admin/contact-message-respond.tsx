import { ContactMessage } from '@/types';
import { LoadingButton } from '@/components/ui/loading-button';
import { Textarea } from '@/components/ui/textarea';
import { ChangeEvent, useState } from 'react';
import { Button } from '@/components/ui/button';

interface ContactMessageRespondProps {
    message: ContactMessage;
}

export const ContactMessageRespond: React.FC<ContactMessageRespondProps> = ({ message }) => {

    const [response, setResponse] = useState<string>('');
    const [isResponding, setIsResponding] = useState(false);
    const [isSending, setIsSending] = useState(false);

    const beginResponseHandler = (): void => {
        setResponse(`\n\n> ${message.body}`);
        setIsResponding(true);
    };

    const changeResponseHandler = (e: ChangeEvent): void => {
        setResponse(e.target.value);
    }

    const submitHandler = (e): void => {
        e.preventDefault();
    };

    return (
        <div className="my-2">
            {isResponding ? (
                <form onSubmit={submitHandler}>
                    <Textarea autoFocus rows="4" value={response} onChange={changeResponseHandler}
                              placeholder="Your response to the message..."/>
                    <div className="flex justify-end mt-2">
                        <LoadingButton type="submit" isLoading={isSending}>Send response</LoadingButton>
                        <Button type="button" variant="ghost" onClick={() => setIsResponding(false)}>Cancel</Button>
                    </div>
                </form>
            ) : (
                <Button onClick={beginResponseHandler}>Respond to message</Button>
            )}
        </div>
    );
};
