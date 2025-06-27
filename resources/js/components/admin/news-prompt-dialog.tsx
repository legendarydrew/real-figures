import { ChangeEvent, FC, useEffect, useState } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { LoadingButton } from '@/components/mode/loading-button';
import { Textarea } from '@/components/ui/textarea';

interface NewsPromptDialogProps {
    open: boolean;
    onOpenChange: () => void;
    type?: string;
    reference_ids?: number[];
    prompt?: string;
}

export const NewsPromptDialog: FC<NewsPromptDialogProps> = ({ open, onOpenChange, type, reference_ids, prompt }) => {

    const [updatedPrompt, setUpdatedPrompt] = useState<string>();
    const [isGenerating, setIsGenerating] = useState<string>();

    useEffect(() => {
        setUpdatedPrompt(prompt);
    }, [prompt]);

    const changeHandler = (e: ChangeEvent) => {
        setUpdatedPrompt(e.target.value);
    };

    const saveHandler = () => {
    };

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="lg:w-5xl lg:max-w-[900px]">
                <DialogTitle>News Post AI prompt <small>for a {type}</small></DialogTitle>
                <DialogDescription>
                    This prompt will be used with OpenAI to generate the press release. You can make changes before it
                    is sent.
                </DialogDescription>

                <form onSubmit={saveHandler}>
                    <Textarea value={updatedPrompt} onChange={changeHandler} className="font-mono h-[50dvh]"/>

                    <DialogFooter>
                        <LoadingButton variant="default" type="submit" onClick={saveHandler}
                                       isLoading={isGenerating}>Generate News Post</LoadingButton>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
