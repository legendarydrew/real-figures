import { FC, useCallback, useEffect, useState } from 'react';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle
} from '@/components/ui/dialog';
import { LoadingButton } from '@/components/mode/loading-button';
import { router } from '@inertiajs/react';
import { Alert } from '@/components/mode/alert';
import { titleCase } from '@/lib/utils';
import { MicrochipIcon } from 'lucide-react';
import { Button } from '@/components/ui/button';

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

    const keyOutput = (value: any): string => {
        if (typeof value === 'object') {
            return value.length ? "\n" + value.map((v) => `- ${v}`).join("\n") : <em className="text-muted-foreground">none</em>;
        } else {
            return value ?? <em className="text-muted-foreground">none</em>;
        }
    }

    const canGenerate = useCallback(() => {
        return updatedPrompt?.length && type;
    }, [updatedPrompt, type]);

    const saveHandler = () => {
        if (!canGenerate || isGenerating) return;

        setIsGenerating(true);

        router.post(route('news.generate'), { type, references: reference_ids, prompt }, {
            preserveUrl: true,
            onError: (response) => {
                setError(response);
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
                <DialogTitle>
                    <div className="flex gap-1 items-baseline">
                        News Post AI prompt <small className="text-muted-foreground">for {titleCase(type)}</small>
                    </div>
                </DialogTitle>
                <DialogDescription>
                    If you continue, this information will be sent to OpenAI for generating the press release.
                </DialogDescription>

                <form onSubmit={saveHandler}>
                    <div className="font-mono text-sm max-h-50 overflow-y-auto">
                        { prompt && Object.keys(prompt).map((key) => (
                            <p key={key} className="mb-2 whitespace-pre-line">
                                <b>{key}: </b> {keyOutput(prompt[key])}
                            </p>
                        )) }
                    </div>

                    <DialogFooter className="flex-wrap">
                        {error && (<Alert className="w-full" type="error">{error}</Alert>)}

                        <DialogClose asChild>
                            <Button>Cancel</Button>
                        </DialogClose>

                        <LoadingButton variant="primary" type="submit" onClick={saveHandler}
                                       disabled={!canGenerate}
                                       isLoading={isGenerating}>
                            <MicrochipIcon/>
                            Generate News Post
                        </LoadingButton>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
