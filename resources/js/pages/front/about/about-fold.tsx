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
                    <p>The F.O.L.D describes an unofficial inner circle within the LEGO space, that
                        appears inclusive on the surface, but acts in exclusive and controlling ways.</p>

                    <div className="text-sm">
                        <p>While the broader LEGO space is known for creativity, collaboration and fun, the F.O.L.D
                            represents a quieter side of things: a group of individuals &ndash; ranging from prominent
                            fans to people with connections inside the company &ndash; that decides who is included,
                            supported, or sidelined.</p>
                        <p>Those who don’t align with their unspoken rules or preferred image often face exclusion,
                            subtle discrimination, or are pushed out entirely.
                        </p>

                        <p className="font-semibold">While the F.O.L.D is specific to the LEGO space, their pattern of behaviour is also common
                            across other hobby and interest groups.</p>

                        <p>The concept was introduced with SilentMode's 2024 <b>SCREAMix</b> project,
                            first displayed at the Great Western Brick Show.</p>
                    </div>
                </div>
            </CollapsibleContent>
        </Collapsible>
    );
};
