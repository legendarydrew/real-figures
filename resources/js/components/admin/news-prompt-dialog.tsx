import { ChangeEvent, FC, useCallback, useEffect, useState } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { LoadingButton } from '@/components/mode/loading-button';
import { Textarea } from '@/components/ui/textarea';
import { router } from '@inertiajs/react';
import { Alert } from '@/components/mode/alert';
import { titleCase } from '@/lib/utils';

interface NewsPromptDialogProps {
    open: boolean;
    onOpenChange: () => void;
    type?: string;
    reference_ids?: number[];
    prompt?: string;
}

export const NewsPromptDialog: FC<NewsPromptDialogProps> = ({ open, onOpenChange, type, reference_ids, prompt }) => {
    const [updatedPrompt, setUpdatedPrompt] = useState<string>();
    const [isGenerating, setIsGenerating] = useState<boolean>();
    const [error, setError] = useState<string>();

    useEffect(() => {
        setUpdatedPrompt(prompt);
    }, [prompt]);

    const changeHandler = (e: ChangeEvent) => {
        setUpdatedPrompt(e.target.value);
    };

    const canGenerate = useCallback(() => {
        return updatedPrompt?.length && type;
    }, [updatedPrompt, type]);

    const saveHandler = () => {
        if (!canGenerate || isGenerating) return;

        setIsGenerating(true);

        router.post(route('news.generate'), { type, references: reference_ids, prompt }, {
            preserveUrl: true,
            onError: (response) => {
                console.log(response);
            },
            onFinish: () => {
                setIsGenerating(false);
            }
        });

        // If successful, we should automatically go to the edit page for the News Post.
    };

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="lg:w-5xl lg:max-w-[900px]">
                <DialogTitle>News Post AI prompt <small>for {titleCase(type)}</small></DialogTitle>
                <DialogDescription>
                    This prompt will be used with OpenAI to generate the press release. You can make changes before it
                    is sent.
                </DialogDescription>

                <form onSubmit={saveHandler}>
                    <Textarea value={updatedPrompt} onChange={changeHandler} className="font-mono h-[50dvh]"/>

                    <DialogFooter className="flex-wrap">
                        {error && (<Alert className="w-full" type="error">{error}</Alert>)}
                        <LoadingButton variant="default" type="submit" onClick={saveHandler}
                                       disabled={!canGenerate}
                                       isLoading={isGenerating}>Generate News Post</LoadingButton>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
