import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronDown, ChevronUp } from 'lucide-react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { useState } from 'react';

export const AboutFoldPanel: React.FC = ({ opened }) => {

    const [isOpen, setIsOpen] = useState<boolean>(false);

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
                <span className="display-text flex-grow text-left">What is the F.O.L.D?</span>
                {isOpen ? <ChevronUp/> : <ChevronDown/>}
            </CollapsibleTrigger>
            <CollapsibleContent
                className="content pt-3 pb-5 lg:pb-10 px-2 lg:px-5 flex flex-col md:flex-row gap-5 lg:gap-10">
                <PlaceholderPattern className="md:w-2/5 stroke-neutral-900/20"/>
                <div className="md:w-3/5">
                    <p>The F.O.L.D is a nebulous, highly-connected network of fans and
                        employees within the LEGO space, often referring to itself as a
                        "community".</p>
                    <div className="text-sm">
                        <p>In the public eye, members of the F.O.L.D appear to be welcoming,
                            friendly and inclusive. But with near absolute control over the hobby, it practices
                            discrimination, exclusion and isolation, promoting specific agendas
                            and elevating a small handful of people.</p>
                        <p>The concept of the F.O.L.D was introduced with SilentMode's <b>SCREAMix</b> project,
                            first displayed at the 2024 Great Western Brick Show.</p>
                    </div>
                </div>
            </CollapsibleContent>
        </Collapsible>
    );
};
