import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronDown, ChevronUp } from 'lucide-react';
import { useState } from 'react';

export const AboutSongPanel: React.FC = ({ opened }) => {

    const [isOpen, setIsOpen] = useState(false);

    const toggleHandler = (): void => {
        if (!isOpen && opened) {
            opened();
        }
        setIsOpen(!isOpen);
    }

    return (
        <Collapsible className="border-b" open={isOpen} onOpenChange={toggleHandler}>
            <CollapsibleTrigger
                className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                <span className="display-text flex-grow text-left">About the Song</span>
                {isOpen ? <ChevronUp/> : <ChevronDown/>}
            </CollapsibleTrigger>
            <CollapsibleContent
                className="content pt-3 pb-5 lg:pb-10 px-2 lg:px-5 flex flex-col md:flex-row gap-5 lg:gap-10">
                <figure className="md:w-2/5">
                    <img className="w-full" src="img/bryknii.png" alt="Superstar Bryknii singing to a fan."/>
                </figure>
                <div className="md:w-3/5">
                    <p>
                        <b className="text-current">Real Figures Don't F.O.L.D</b> is a song <b
                        className="text-current">to those who have experienced
                        bullying</b>.</p>
                    <div className="text-sm">
                        <p>It was written from the perspective that <b>neither the bullies nor their enablers
                            are
                            listening or will listen</b>, and we can't expect them to change their ways.</p>
                        <p>There are <b>three meanings</b> behind the song:</p>
                        <ul className="list-disc mb-3 mx-5 flex flex-col gap-3">
                            <li>Encouraging targets of bullying to <b>be authentic
                                and true to themselves</b>;
                            </li>
                            <li>That "selling out" to become part of the "in" crowd (the F.O.L.D) is <b
                                className="text-muted-foreground">not an answer</b>;
                            </li>
                            <li>A reference to SilentMode's minifigure redesign, which
                                stands upright and does not bend at the hip.
                            </li>
                        </ul>
                    </div>
                </div>
            </CollapsibleContent>
        </Collapsible>
    )
};
