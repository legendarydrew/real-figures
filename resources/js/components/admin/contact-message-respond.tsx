import { ContactMessage } from '@/types';
import { LoadingButton } from '@/components/mode/loading-button';
import { Textarea } from '@/components/ui/textarea';
import { ChangeEvent, useState } from 'react';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/react';
import toast from 'react-hot-toast';
import InputError from '@/components/input-error';

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
                toast.success('Response was sent.');
            }
        });
    };

    return (
        <div className="my-2">
            {isResponding ? (
                <form onSubmit={submitHandler}>
                    <Textarea autoFocus rows="4" value={data.response} onChange={changeResponseHandler}
                              placeholder="Your response to the message..."/>
                    <InputError message={errors.response}/>
                    <div className="flex justify-end mt-2">
                        <LoadingButton type="submit" isLoading={processing}>Send response</LoadingButton>
                        <Button type="button" variant="ghost" onClick={() => setIsResponding(false)}>Cancel</Button>
                    </div>
                </form>
            ) : (
                <Button onClick={beginResponseHandler}>Respond to message</Button>
            )}
        </div>
    );
};
