import { ContactMessage } from '@/types';
import { LoadingButton } from '@/components/mode/loading-button';
import { Textarea } from '@/components/ui/textarea';
import React, { ChangeEvent, useState } from 'react';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { RTToast } from '@/components/mode/toast-message';

interface ContactMessageRespondProps {
    message: ContactMessage;
}

export const ContactMessageRespond: React.FC<ContactMessageRespondProps> = ({ message }) => {

    const { data, setData, put, errors, setError, processing } = useForm();
    const [isResponding, setIsResponding] = useState(false);

    const beginResponseHandler = (): void => {
        setData('response', `\n\n> ${message.body}`);
        setIsResponding(true);
    };

    const changeResponseHandler = (e: ChangeEvent): void => {
        e.target.parentNode.dataset.clonedVal = e.target.value;
        setData('response', e.target.value);
        setError('response', '');
    }

    const submitHandler = (e): void => {
        e.preventDefault();
        put(route('messages.respond', { id: message.id }), {
            preserveUrl: true,
            preserveScroll: true,
            onSuccess: () => {
                setIsResponding(false);
                RTToast.success('Response was sent.');
            }
        });
    };

    return (
        <div className="my-2">
            {isResponding ? (
                <form onSubmit={submitHandler}>
                    <div className="textarea-expand">
                        <Textarea
                            autoFocus
                            onChange={changeResponseHandler}
                            value={data.response}
                            placeholder="Your response to the message..."
                            className="min-h-16"
                        />
                    </div>
                    <InputError message={errors.response}/>
                    <div className="flex justify-end gap-2 mt-2">
                        <Button type="button" variant="ghost" onClick={() => setIsResponding(false)}>Cancel</Button>
                        <LoadingButton variant="primary" type="submit" isLoading={processing}>Send
                            response</LoadingButton>
                    </div>
                </form>
            ) : (
                <Button onClick={beginResponseHandler}>Respond to message</Button>
            )}
        </div>
    );
};
