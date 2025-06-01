import { ChangeEvent, FC, useEffect, useState } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { usePage } from '@inertiajs/react';
import { PaypalButton } from '@/components/paypal-button';
import { Checkbox } from '@/components/ui/checkbox';
import { Textarea } from '@/components/ui/textarea';
import { Alert } from '@/components/alert';
import { useDialog } from '@/context/dialog-context';
import { useAnalytics } from '@/hooks/use-analytics';
import ConfettiExplosion, { ConfettiProps } from 'react-confetti-explosion';
import { Act, Round, Song } from '@/types';
import { ActImage } from '@/components/ui/act-image';
import { LanguageFlag } from '@/components/language-flag';

interface GoldenBuzzerDialogProps {
    // Dialog properties.
    // This dialog has to be opened from more than one place, and we want to be able to set
    // the respective Round and Act.
    round: Round;
    act: Act;
    open: boolean;
    onOpenChange: () => void;
}

export const GOLDEN_BUZZER_DIALOG_NAME = 'golden-buzzer';

export const GoldenBuzzerDialog: FC<GoldenBuzzerDialogProps> = () => {

    const { openDialogName, closeDialog, dialogProps } = useDialog();
    const isOpen = openDialogName === GOLDEN_BUZZER_DIALOG_NAME;

    const { donation } = usePage().props;
    const { trackEvent } = useAnalytics();

    const [round, setRound] = useState<Round>();
    const [song, setSong] = useState<Song>();
    const [amount, setAmount] = useState<number>(donation.default.general);
    const [message, setMessage] = useState<string>('');
    const [isAnonymous, setIsAnonymous] = useState<boolean>(false);
    const [wasSuccessful, setWasSuccessful] = useState<boolean>(false);
    const [failed, setFailed] = useState<boolean>(false);

    const confettiSettings: ConfettiProps = {
        force: 0.9,
        duration: 8000,
        particleCount: 250,
        width: window.innerWidth,
        colors: [
            '#ffd700',
            '#eee8aa',
            '#daa520',
            '#da9100',
            '#fcc200',
            '#996515'
        ],
        zIndex: 100
    };

    useEffect(() => {
        if (isOpen) {
            setRound(dialogProps.round);
            setSong(dialogProps.song);
        }
    }, [isOpen]);

    const amountHandler = (e: ChangeEvent): void => {
        setAmount(parseFloat(e.target.value));
    };

    const messageHandler = (e: ChangeEvent): void => {
        setMessage(e.target.value);
    };

    const anonymousHandler = (state: boolean): void => {
        setIsAnonymous(state);
    };

    const processingHandler = () => {
        setWasSuccessful(false);
        setFailed(false);
    };

    const successHandler = () => {
        setWasSuccessful(true);
        trackEvent({ category: 'Action', action: 'Golden Buzzer', value: amount, nonInteraction: false });
    };

    const failureHandler = () => {
        setFailed(true);
    };

    useEffect(() => {
        if (open) {
            setAmount(donation.default.golden_buzzer);
            setMessage('');
            setWasSuccessful(false);
            setFailed(false);
        }
    }, [open]);

    return (
        <Dialog open={isOpen} onOpenChange={closeDialog}>
            <DialogContent className="lg:max-w-3xl bg-amber-300 dark:bg-yellow-800" aria-describedby={undefined}>
                <DialogTitle>Award a <span
                    className="text-amber-700 dark:text-amber-300">Golden Buzzer</span>!</DialogTitle>

                {song ? (
                    <div>
                        <div className="flex gap-2 items-center">
                            <ActImage act={song.act} size="20"/>
                            <div className="flex flex-col display-text leading-none">
                                <span>{song.act.name}</span>
                                <span className="text-sm flex gap-2 items-center">
                                    <LanguageFlag languageCode={song?.language}/>
                                    {song.title}
                                </span>
                            </div>
                        </div>
                        <p className="my-2">The Song will receive the same perks as a runner-up in this Stage.</p>
                        <p className="text-xs">
                            <b>IMPORTANT:</b> Golden Buzzers are for supporting your favourite Acts and Songs &ndash;
                            they <b>do not count toward scoring</b>.
                        </p>
                    </div>) : ''}

                {wasSuccessful ? (
                    <div
                        className="h-1/3 p-10 text-amber-600 font-semibold gap-3">
                        <div className="mx-auto relative">
                            <ConfettiExplosion {...confettiSettings} />
                        </div>
                        <div className="max-w-2/3 flex flex-col text-center items-center justify-center">
                            <p className="text-lg font-semibold">Thank you very much!</p>
                            <p>You've just awarded a Golden Buzzer
                                to <b>{song?.act.name}</b> in <b>{round?.full_title}</b>.
                            </p>
                            <p>Remember to cast your vote for your favourite Acts in this Round!</p>
                        </div>
                    </div>
                ) : (
                    <>
                        <div
                            className="flex flex-col lg:flex-row gap-3 justify-between items-center py-2 px-5 bg-amber-200 dark:bg-yellow-600 rounded-sm">
                            <Label htmlFor="donationAmount">I would like to donate</Label>

                            <div className="flex gap-1 items-center">
                                {donation.options.map((value) => (
                                    <Button className="max-sm:hidden" key={value} variant="secondary" type="button"
                                            onClick={() => setAmount(value)}>{value}</Button>
                                ))}
                                <div className="ml-2 flex items-center">
                                    <Input
                                        className="bg-white w-[5rem] border-amber-500 dark:border-amber-800 text-amber-800 dark:text-amber-900 font-semibold text-right text-lg"
                                        id="donationAmount" type="number" value={amount} min="1"
                                        onChange={amountHandler}/>
                                    <span
                                        className="p-1 font-semibold text-base">{donation.currency}</span>
                                </div>
                            </div>
                        </div>

                        <div className="flex-grow max-sm:hidden">
                            <Label className="mb-2" htmlFor="donationMessage">A message for SilentMode <small
                                className="font-normal">(optional)</small></Label>
                            <Textarea id="donationMessage" value={message} onChange={messageHandler} rows={2}/>
                        </div>

                        {failed && (
                            <Alert type="error"
                                   message="Something went wrong with processing your donation, please try again."/>
                        )}

                        <DialogFooter className="mt-0 items-center md:justify-between">
                            <div className="flex gap-2 items-center">
                                <Checkbox id="donationAnonymous" className="bg-white" checked={isAnonymous}
                                          onCheckedChange={anonymousHandler}/>
                                <Label htmlFor="donationAnonymous">I would like to remain anonymous.</Label>
                            </div>

                            <PaypalButton amount={amount} currency={donation.currency}
                                          additionalData={{ is_anonymous: isAnonymous, message }}
                                          description="Real Figures Don't F.O.L.D: Golden Buzzer"
                                          onProcessing={processingHandler}
                                          onSuccess={successHandler}
                                          onFailure={failureHandler}/>
                        </DialogFooter>
                    </>)}
            </DialogContent>
        </Dialog>
    )
}
