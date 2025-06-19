import { Button } from '@/components/ui/button';
import { XIcon } from 'lucide-react';
import { useEffect, useState } from 'react';
import { cn } from '@/lib/utils';
import { Alert } from '@/components/mode/alert';

export const FlashMessage: React.FC<{ message: string }> = ({ message }) => {

    const [displayMessage, setDisplayMessage] = useState<string>(message);
    const [closeTimeout, setCloseTimeout] = useState<number>(null);
    const [fadingOut, setFadingOut] = useState<boolean>(false);

    useEffect(() => {
        clearTimeout(closeTimeout);
        setDisplayMessage(message);
        setFadingOut(false);
        setCloseTimeout(setTimeout(removeMessage, 5000))
    }, [message]);

    const removeMessage = () => {
        setFadingOut(true);
        setTimeout(() => setDisplayMessage(''), 1000);
    }

    return displayMessage && (
        <Alert
            className={cn("fixed pl-5 top-[2rem] z-10 max-w-3xl w-1/2 rounded-md drop-shadow-md text-base font-semibold left-1/2 -translate-x-1/2 transition duration-1000",
                fadingOut ? "opacity-0" : "opacity-100")} message={displayMessage}>
            <Button variant="ghost" size="icon" type="button" onClick={removeMessage}>
                <XIcon/>
            </Button>
        </Alert>
    )
}
