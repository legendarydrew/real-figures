import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronDown, ChevronUp } from 'lucide-react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { useState } from 'react';
import { Link } from '@inertiajs/react';
import { SocialIcon } from 'react-social-icons';

export const AboutSilentmodePanel: React.FC = ({ opened }) => {

    const [isOpen, setIsOpen] = useState(false);

    const toggleHandler = (): void => {
        if (!isOpen && opened) {
            opened();
        }
        setIsOpen(!isOpen);
    }

    return (
        <Collapsible open={isOpen} onOpenChange={toggleHandler}>
            <CollapsibleTrigger
                className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                <span className="display-text flex-grow text-left">About SilentMode</span>
                {isOpen ? <ChevronUp/> : <ChevronDown/>}
            </CollapsibleTrigger>
            <CollapsibleContent
                className="content pt-3 pb-5 lg:pb-10 px-2 lg:px-5 flex flex-col md:flex-row gap-5 lg:gap-10">
                <PlaceholderPattern className="md:w-2/5 stroke-neutral-900/20"/>
                <div className="md:w-3/5">
                    <p><b className="text-current">SilentMode is a Maker, Artist and LEGO Enthusiast</b> based in
                        London,
                        UK. He has been active within the LEGO space since 2010.</p>
                    <div className="text-sm">
                        <p>As well as designing and building his own models, he incorporates 3D printing,
                            coding and graphic design into his interest in the hobby.</p>
                        <p>He is passionate about addressing bullying, mental health and men's
                            issues &ndash; as a survivor.</p>
                        <p className="font-semibold">You can find SilentMode on social media <b>@silentmodetv</b>, as
                            well as on
                            his <Link className="underline" href="https://silentmode.tv" target="_blank">dedicated web
                                site.</Link></p>
                        <div className="flex justify-center gap-3">
                            <SocialIcon url="https://youtube.com/@silentmodetv"/>
                            <SocialIcon url="https://instagram.com/silentmodetv"/>
                            <SocialIcon url="https://x.com/silentmodetv"/>
                            <SocialIcon url="https://tiktok.com/silentmodetv"/>
                            <SocialIcon url="https://www.flickr.com/photos/drewmaughan/"/>
                        </div>
                    </div>
                </div>
            </CollapsibleContent>
        </Collapsible>
    );
};
