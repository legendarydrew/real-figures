import { Head, router, useForm } from '@inertiajs/react';
import React, { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { LoadingButton } from '@/components/mode/loading-button';
import { titleCase } from '@/lib/utils';
import { ExpandingTextarea } from '@/components/ui/textarea';
import { NewsPromptDialog } from '@/components/admin/news-prompt-dialog';
import { Alert } from '@/components/mode/alert';
import { AdminHeader } from '@/components/admin/admin-header';
import axios from 'axios';
import { Input } from '@/components/ui/input';
import InputError from '@/components/input-error';
import { PlusIcon, TrashIcon } from 'lucide-react';
import { NewsGeneratePayload } from '@/interfaces';
import { NewsActSelect } from '@/components/admin/news-act-select';
import { NewsPostSelect } from '@/components/admin/news-post-select';

/**
 * NEW APPROACH
 * Display these fields for each press release type:
 *
 * General: title, description, quote, highlights
 * Contest: nothing
 * Stage: select a Stage
 * Round: select a Round
 * Results: nothing
 * Act: select one or more Acts
 *
 * On confirming the prompt information, we send the same information to the generate endpoint.
 */

interface NewsGeneratePageProps {
    types: string[];
    acts?: { id: number, name: string; }[];
    rounds?: { id: number; title: string; }[];
    stages?: { id: number; title: string; status: string; }[];
    posts?: { id: number; title: string; published_at: string; }[];
}

export default function NewsGeneratePage({
                                             types,
                                             acts,
                                             rounds,
                                             stages,
                                             posts,
                                             news
                                         }: Readonly<NewsGeneratePageProps>) {

    const { data, setData } = useForm<NewsGeneratePayload>({
        type: undefined, // the type of News Post to create.
        title: "",
        prompt: "", // user-entered information to help OpenAI.
        quote: "",
        history: [],
        highlights: [],
        acts: [],
        stage: undefined
    });

    const [error, setError] = useState<string>();
    const [validation, setValidation] = useState();
    const [isPromptOpen, setIsPromptOpen] = useState<boolean>(false);
    const [isSaving, setIsSaving] = useState<boolean>(false);
    const [prompt, setPrompt] = useState<string>();
    const [stageName, setStageName] = useState<string>();
    const [roundName, setRoundName] = useState<string>();

    const cancelHandler = (): void => {
        router.visit(route('admin.news'));
    };

    const selectTypeHandler = (type: string): void => {
        setData((prev) => ({ ...prev, type }));

        const additionalInfo = ['posts'];
        switch (type) {
            case 'general':
            case 'contest':
                additionalInfo.push('news');
                break;
            case 'stage':
                additionalInfo.push('stages', 'news');
                break;
            case 'act':
                additionalInfo.push('acts', 'news');
                break;
            case 'round':
                additionalInfo.push('rounds', 'news');
                break;
        }

        router.reload({
            only: additionalInfo,
            showProgress: true,
            onSuccess: () => {
                setData((prev) => ({ ...prev, references: [] }));
            }
        });
    };

    const showBasicFields = (): boolean => {
        return data.type !== undefined && ['general', 'act'].includes(data.type);
    }

    const showActsField = (): boolean => {
        return data.type !== undefined && ['act'].includes(data.type);
    }

    const showStageField = (): boolean => {
        return data.type !== undefined && ['stage'].includes(data.type);
    }

    const showRoundField = (): boolean => {
        return data.type !== undefined && ['round'].includes(data.type);
    }

    const showHighlightsField = (): boolean => {
        return data.type !== undefined && ['general'].includes(data.type);
    }

    const showHistoryField = (): boolean => {
        return data.type !== undefined && !['results'].includes(data.type);
    }

    const titleHandler = (e): void => {
        setData((prev) => ({ ...prev, title: e.target.value }));
    };

    const promptHandler = (e): void => {
        setData((prev) => ({ ...prev, prompt: e.target.value }));
    };

    const quoteHandler = (e): void => {
        setData((prev) => ({ ...prev, quote: e.target.value }));
    };

    const addHighlightHandler = (): void => {
        setData((prev) => ({ ...prev, highlights: [...prev.highlights, ''] }));
    };

    const removeHightlightHandler = (index: number): void => {
        const highlights = [...data.highlights];
        highlights.splice(index, 1);
        setData((prev) => ({ ...prev, highlights }));
    };

    const highlightHandler = (e, index: number): void => {
        const highlights = [...data.highlights];
        highlights[index] = e.target.value;
        setData((prev) => ({ ...prev, highlights }));
    };

    const updateHistoryHandler = (history): void => {
        setData((prev) => ({ ...prev, history }));
    };

    const updateActHandler = (acts): void => {
        setData((prev) => ({ ...prev, acts }));
    };

    const selectStageHandler = (stage: number): void => {
        setData((prev) => ({ ...prev, stage }));
        const matchingStage = stages?.find((s) => s.id.toString() === stage);
        setStageName(matchingStage ? `${matchingStage.title} [${matchingStage.status}]` : undefined);
    };

    const selectRoundHandler = (round: number): void => {
        setData((prev) => ({ ...prev, round }));
        const matchingRound = rounds?.find((r) => r.id.toString() === round);
        setRoundName(matchingRound?.title ?? undefined);
    };

    const generatePromptHandler = (e): void => {
        e.preventDefault();

        if (isSaving) return;

        setIsSaving(true);
        setError(undefined);
        setValidation(undefined);
        axios.post(route('news.prompt'), data)
            .then((response) => {
                setPrompt(response.data.prompt);
                setIsPromptOpen(true);
            })
            .catch((err) => {
                if (err.response.status === 422) {
                    setValidation(err.response.data.errors);
                } else {
                    setError(err.response.data.message);
                }
            })
            .finally(() => {
                setIsSaving(false);
            });
    };

    const closePromptHandler = (): void => {
        setIsPromptOpen(false);
        setPrompt(undefined);
    }

    return (
        <AppLayout>
            <Head title="Generate News Post"/>

            <div className="admin-content">

                <AdminHeader title="Generate a News Post"/>

                <p>The following information will be used to generate a prompt for OpenAI to create a News Post.</p>

                <form className="flex-grow flex flex-col justify-start gap-4 px-8" onSubmit={generatePromptHandler}>

                    {/* Select the News Post type. */}
                    <div>
                        <Label htmlFor="postType">What kind of News Post should we create?</Label>
                        <Select id="postType" onValueChange={selectTypeHandler}>
                            <SelectTrigger>{data.type ? titleCase(data.type) : 'Select a type...'}</SelectTrigger>
                            <SelectContent>
                                {types.map((type) => (
                                    <SelectItem key={type} value={type}>{titleCase(type)}</SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError message={validation?.type}/>
                    </div>

                    {/* Depending on what was selected... */}

                    {showActsField() && (
                        <>
                            <NewsActSelect acts={acts} onChange={updateActHandler}/>
                            <InputError message={validation?.acts}/>
                        </>
                    )}

                    {showStageField() && (
                        <div>
                            <Label htmlFor="postStage">Which Stage?</Label>
                            <Select id="postStage" onValueChange={selectStageHandler}>
                                <SelectTrigger>{stageName ?? 'Select a Stage...'}</SelectTrigger>
                                <SelectContent>
                                    {stages?.map((stage) => (
                                        <SelectItem key={stage.id}
                                                    value={stage.id}>{stage.title} ({stage.status})</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <InputError message={validation?.stage}/>
                        </div>
                    )}

                    {showRoundField() && (
                        <div>
                            <Label htmlFor="postRound">Which Round?</Label>
                            <Select id="postRound" onValueChange={selectRoundHandler}>
                                <SelectTrigger>{roundName ?? 'Select a Round...'}</SelectTrigger>
                                <SelectContent>
                                    {rounds?.map((round) => (
                                        <SelectItem key={round.id}
                                                    value={round.id}>{round.title}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <InputError message={validation?.round}/>
                        </div>
                    )}


                    {/* Additional prompt. */}
                    {showBasicFields() && (
                        <>
                            <div>
                                <Label htmlFor="postTitle">Post title</Label>
                                <Input id="postTitle" placeholder="Suggested title" onChange={titleHandler}/>
                                <InputError message={validation?.title}/>
                            </div>
                            <div>
                                <Label htmlFor="postPrompt">Prompt</Label>
                                <ExpandingTextarea id="postPrompt"
                                                   placeholder="Information that could help the generator."
                                                   className="max-h-40"
                                                   onChange={promptHandler}/>
                                <InputError message={validation?.prompt}/>
                            </div>
                            <div>
                                <Label htmlFor="postQuote">Quote <span
                                    className="text-muted-foreground">(optional)</span></Label>
                                <ExpandingTextarea id="postQuote" className="max-h-20" onChange={quoteHandler}/>
                                <InputError message={validation?.quote}/>
                            </div>
                        </>
                    )}

                    {showHighlightsField() && (
                        <div>
                            <Label>Highlights <span className="text-muted-foreground">(optional)</span></Label>
                            <ul className="flex flex-col gap-2">
                                {data.highlights.map((highlight, i) => (
                                    <li key={i} className="flex gap-1 items-stretch">
                                        <Input value={highlight} onChange={(e) => highlightHandler(e, i)}/>
                                        <Button type="button" size="icon"
                                                onClick={() => removeHightlightHandler(i)}>
                                            <TrashIcon className="size-3"/>
                                        </Button>
                                    </li>
                                ))}
                            </ul>
                            <InputError message={validation?.highlights}/>
                            <Button type="button" size="sm" onClick={addHighlightHandler}>
                                <PlusIcon/> Add
                            </Button>
                        </div>
                    )}

                    {showHistoryField() && (
                        <>
                            <NewsPostSelect posts={news} onChange={updateHistoryHandler}/>
                            <InputError message={validation?.history}/>
                        </>
                    )}

                    <div
                        className="bg-white border-t-1 flex flex-wrap justify-between sticky bottom-0 mt-auto py-3 -mx-5 px-5">
                        {error && <Alert className="w-full" type="error" message={error}/>}

                        <Button variant="ghost" type="button" onClick={cancelHandler}>Cancel</Button>
                        <LoadingButton variant="secondary" disabled={!data.type} isLoading={isSaving}>
                            Create prompt...
                        </LoadingButton>
                    </div>
                </form>

                <NewsPromptDialog payload={data} prompt={prompt} open={isPromptOpen}
                                  onOpenChange={closePromptHandler}/>
            </div>
        </AppLayout>
    );
};
