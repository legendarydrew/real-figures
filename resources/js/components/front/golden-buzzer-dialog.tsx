import { ChangeEvent, useEffect, useRef, useState } from 'react';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { PaypalButton } from '@/components/mode/paypal-button';
import { Checkbox } from '@/components/ui/checkbox';
import { Textarea } from '@/components/ui/textarea';
import { Alert } from '@/components/mode/alert';
import ConfettiExplosion, { ConfettiProps } from 'react-confetti-explosion';
import { SongBanner } from '@/components/mode/song-banner';

/**
 * GOLDEN BUZZER DIALOG component
 * Very similar to the Donate dialog, except we have to know which Song is being rewarded,
 * and in which Round.
 * This dialog has to be opened from more than one place, so the respective Round and Song are
 * set and retrieved as dialogProps using the dialog hooks.
 * @constructor
 */
export const GoldenBuzzerDialog: React.FC = ({ stage, round, song }) => {

    const donation = {
        'default': {
            'general': 5,
            'golden_buzzer': 10
        },
        'minimum': {
            'general': 1,
            'golden_buzzer': 6
        },
        'options': [3, 5, 10, 15, 20, 50, 100],
        'currency': 'USD'
    };
    const donationOptions = useRef<number[]>(donation.options.filter((v) => v >= donation.minimum.golden_buzzer));
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
        setAmount(donation.default.golden_buzzer);
        setMessage('');
        setWasSuccessful(false);
        setFailed(false);
    }, []);

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
        globalThis.trackEvent({
            category: 'Golden Buzzer',
            action: 'Awarded Golden Buzzer',
            label: song.act.slug,
            value: amount
        });
    };

    const failureHandler = () => {
        setFailed(true);
    };

    return (wasSuccessful ? (
            <div
                className="golden-buzzer-success">
                <div className="golden-buzzer-success-inner">
                    <ConfettiExplosion {...confettiSettings} />
                    <h2 className="golden-buzzer-success-heading">Thank you very much!</h2>
                    <p>You've just awarded a Golden Buzzer
                        to <b>{song?.act.name} {song?.act.subtitle}</b> in <b>{round?.full_title}</b>.
                    </p>
                    <p>Remember to <b>cast votes</b> for your favourite Acts in this Round!</p>
                </div>
            </div>
        ) : (
            <div className="golden-buzzer-content">

                <div className="golden-buzzer-content-song">
                    <SongBanner song={song}/>
                    {stage?.goldenBuzzerPerks ? (
                        <p className="golden-buzzer-content-perks"
                           dangerouslySetInnerHTML={{ __html: stage.goldenBuzzerPerks }}/>
                    ) : ''}
                </div>
                <p className="golden-buzzer-content-important">
                    <b>IMPORTANT:</b> Golden Buzzers are honours toward your favourite Acts and
                    Songs &ndash; they <b>do not count toward scoring</b>.
                </p>

                <div
                    className="golden-buzzer-donate">
                    <div className="golden-buzzer-donate-content">

                        <Label htmlFor="donationAmount">I would like to donate</Label>

                        <div className="golden-buzzer-donate-options">
                            {donationOptions.current.map((value) => (
                                <Button key={value} variant="gold" type="button"
                                        onClick={() => setAmount(value)}>{value}</Button>
                            ))}
                            <div className="golden-buzzer-donate-amount">
                                <Input id="donationAmount" type="number" value={amount}
                                       min={donation.minimum.golden_buzzer}
                                       onChange={amountHandler}/>
                                <span className="golden-buzzer-donate-currency">{donation.currency}</span>
                            </div>
                        </div>
                    </div>

                    <p className="golden-buzzer-donate-min">
                        We're asking for a minimum
                        of <b>{donation.currency} {donation.minimum.golden_buzzer}</b> for Golden Buzzers.
                    </p>
                </div>

                <div className="golden-buzzer-donate-message">
                    <Label htmlFor={`donationMessage-${song.id}`}>A message for
                        SilentMode <small>(optional)</small></Label>
                    <Textarea id={`donationMessage-${song.id}`} value={message} onChange={messageHandler} rows={2}/>
                </div>

                {failed && (
                    <Alert type="error"
                           message="Something went wrong with processing your donation, please try again."/>
                )}

                <footer className="golden-buzzer-footer">
                    <div className="golden-buzzer-anonymous">
                        <Checkbox id="donationAnonymous" checked={isAnonymous} onCheckedChange={anonymousHandler}/>
                        <Label htmlFor="donationAnonymous">I would like to remain anonymous.</Label>
                    </div>

                    <PaypalButton approveEndpoint="api/golden-buzzer"
                                  amount={amount} minimumAmount={donation.minimum.golden_buzzer}
                                  currency={donation.currency}
                                  additionalData={{
                                      is_anonymous: isAnonymous,
                                      message,
                                      round_id: round?.id,
                                      song_id: song?.id
                                  }}
                                  description="Real Figures Don't F.O.L.D: Golden Buzzer"
                                  onProcessing={processingHandler}
                                  onSuccess={successHandler}
                                  onFailure={failureHandler}/>
                </footer>
            </div>
        )
    )
}
