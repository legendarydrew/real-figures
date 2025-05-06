import { Button } from '@/components/ui/button';
import { XIcon } from 'lucide-react';
import { useEffect, useState } from 'react';
import { cn } from '@/lib/utils';

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
        <div
            className={cn("fixed flex gap-3 justify-between top-[2rem] z-10 max-w-3xl bg-green-100 w-1/2 rounded-md text-base font-semibold left-1/2 -translate-x-1/2 transition duration-1000",
                fadingOut ? "opacity-0" : "opacity-100")}>
            <div className="px-3 py-1.5">{displayMessage}</div>
            <Button variant="icon" type="button" onClick={removeMessage}>
                <XIcon/>
            </Button>
        </div>
    )
}
