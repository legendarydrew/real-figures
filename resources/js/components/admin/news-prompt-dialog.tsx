import { FC, useEffect, useState } from 'react';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle
} from '@/components/ui/dialog';
import { LoadingButton } from '@/components/mode/loading-button';
import { Alert } from '@/components/mode/alert';
import { titleCase } from '@/lib/utils';
import { MicrochipIcon } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { NewsGeneratePayload } from '@/interfaces';
import axios from 'axios';
import { router } from '@inertiajs/react';

interface NewsPromptDialogProps {
    open: boolean;
    onOpenChange: () => void;
    payload: NewsGeneratePayload;
    prompt?: { [key: string]: string | string[] | null };
}

export const NewsPromptDialog: FC<NewsPromptDialogProps> = ({ open, onOpenChange, payload, reference_ids, prompt }) => {

    const [isGenerating, setIsGenerating] = useState<boolean>();
    const [error, setError] = useState<string>();

    useEffect(() => {
        setError('');
    }, [open]);

    const keyOutput = (value: any): string => {
        if (typeof value === 'object') {
            return value.length ? "\n" + value.map((v) => `- ${v}`).join("\n") :
                <em className="text-muted-foreground">none</em>;
        } else {
            return value ?? <em className="text-muted-foreground">none</em>;
        }
    }

    const saveHandler = () => {
        if (isGenerating) {
            return;
        }

        setIsGenerating(true);

        axios.post(route('news.generate'), payload)
            .then((response) => {
                // If successful, we should automatically go to the edit page for the News Post.
                router.visit(route('news.edit', { id: response.data.id }));
            })
            .catch((err) => {
                console.log(err.response.data.message);
                setError(err.response.data.message);
            })
            .finally(() => {
                setIsGenerating(false);
            });
    };


    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="md:w-5xl md:max-w-3xl lg:max-w-[900px]">
                <DialogTitle>
                    <div className="flex gap-1 items-baseline">
                        News Post AI prompt <small
                        className="text-muted-foreground">for {titleCase(payload.type!)}</small>
                    </div>
                </DialogTitle>
                <DialogDescription>
                    If you continue, this information will be sent to OpenAI for generating the press release.
                </DialogDescription>

                <form onSubmit={saveHandler}>
                    <div className="font-mono text-sm max-h-50 overflow-y-auto">
                        {prompt && Object.keys(prompt).map((key) => (
                            <p key={key} className="mb-2 whitespace-break-spaces">
                                <b>{key}: </b> {keyOutput(prompt[key])}
                            </p>
                        ))}
                    </div>

                    <DialogFooter className="flex-wrap">
                        {error && (<Alert className="w-full" type="error">{error}</Alert>)}

                        <DialogClose asChild>
                            <Button>Cancel</Button>
                        </DialogClose>

                        <LoadingButton variant="primary" type="submit" onClick={saveHandler}
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
