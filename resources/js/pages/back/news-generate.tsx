import { Head, router, useForm } from '@inertiajs/react';
import React, { useMemo, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { LoadingButton } from '@/components/mode/loading-button';
import { titleCase } from '@/lib/utils';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import { Textarea } from '@/components/ui/textarea';
import axios from 'axios';
import { NewsPromptDialog } from '@/components/admin/news-prompt-dialog';
import { Alert } from '@/components/mode/alert';

interface NewsGeneratePageProps {
    types: string[];
    acts?: { id: number, name: string; }[];
    rounds?: { id: number; title: string; }[];
    stages?: { id: number; title: string; status: string; }[];
    posts?: { id: number; title: string; published_at: string; }[];
}

export default function NewsGeneratePage({ types, acts, rounds, stages, posts }: Readonly<NewsGeneratePageProps>) {

    const { data, setData } = useForm({
        type: undefined, // the type of News Post to create.
        references: [], // ID(s) of the Stage/Round/Acts to refer to.
        previous: undefined,  // [optional] previous News Post to reference.
        prompt: "" // user-entered information to help OpenAI.
    });

    const [error, setError] = useState<string>();
    const [isPromptOpen, setIsPromptOpen] = useState<boolean>(false);
    const [isSaving, setIsSaving] = useState<boolean>(false);
    const [prompt, setPrompt] = useState<string>();

    const selectedRound = useMemo((): never => {
        if (data.references.length) {
            return rounds?.find((round) => round.id == data.references[0]);
        }
    }, [rounds, data.references]);

    const cancelHandler = (): void => {
        router.visit(route('admin.news'));
    };

    const selectTypeHandler = (type: string): void => {
        setData('type', type);

        const additionalInfo = ['posts'];
        switch (type) {
            case 'stage':
                additionalInfo.push('stages');
                break;
            case 'act':
                additionalInfo.push('acts');
                break;
            case 'round':
                additionalInfo.push('rounds');
                break;
        }

        router.reload({
            only: additionalInfo,
            showProgress: true,
            onSuccess: () => {
                setData('references', []);
            }
        });
    };

    const selectSingleReferenceHandler = (value: number): void => {
        setData('references', [value]);
    };

    const selectActHandler = (actId: number, state: boolean): void => {
        let updatedIds = data.references;
        if (state) {
            updatedIds.push(actId);
        } else {
            updatedIds = updatedIds.filter((id) => id !== actId);
        }
        setData('references', [...new Set(updatedIds)]);
    };

    const selectPreviousHandler = (value: number): void => {
        setData('previous', value);
    };

    const generatePromptHandler = (e): void => {
        e.preventDefault();

        if (isSaving) return;

        setIsSaving(true);
        setError(undefined);
        axios.post(route('news.prompt'), data)
            .then((response) => {
                setPrompt(response.data.prompt);
                setIsPromptOpen(true);
            })
            .catch((response) => {
                setError(response.response.data.message);
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

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">Generate a News Post</h1>
            </div>

            <form className="flex-grow flex flex-col justify-between gap-5 px-5" onSubmit={generatePromptHandler}>

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
                </div>

                {/* A list of Stages (if available). */}
                {(data.type === 'stage' && stages) && (
                    <div>
                        <Label className="sr-only" htmlFor="postReference">Select a Stage</Label>
                        <Select id="postReference" onValueChange={selectSingleReferenceHandler}>
                            <SelectTrigger>{data.reference_id ?? 'Select a Stage...'}</SelectTrigger>
                            <SelectContent>
                                {stages.map((stage) => (
                                    <SelectItem key={stage.id} value={stage.id}>
                                        {stage.title} <Badge>{stage.status}</Badge>
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                )}

                {/* A list of Rounds (if available). */}
                {(data.type === 'round' && rounds) && (
                    <div>
                        <Label className="sr-only" htmlFor="postReference">Select a Round</Label>
                        <Select id="postReference" onValueChange={selectSingleReferenceHandler}>
                            <SelectTrigger>{selectedRound?.title ?? 'Select a Round...'}</SelectTrigger>
                            <SelectContent>
                                {rounds.map((round) => (
                                    <SelectItem key={round.id} value={round.id}>
                                        {round.title}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                )}

                {/* A list of Acts (if available). */}
                {/* We would like to be able to select one or more Acts. */}
                {(data.type === 'act' && acts) && (
                    <fieldset className="grid gap-3 grid-cols-1 md:grid-cols-3 lg:grid-cols-6">
                        <legend className="font-normal text-sm mb-3">Select one or more Acts...</legend>
                        {acts.map((act) => (
                            <Label key={act.id} className="flex items-center gap-2">
                                <Checkbox value={act.id}
                                          onCheckedChange={(state) => selectActHandler(act.id, state)}/> {act.name}
                            </Label>
                        ))}
                    </fieldset>
                )}

                {/* A list of existing published News Posts. */}
                {posts && (
                    <div>
                        <Label htmlFor="postPrevious">Refer to previous News Post (optional)</Label>
                        <Select id="postPrevious" onValueChange={selectPreviousHandler} disabled={!posts.length}>
                            <SelectTrigger>{data.previous ?? <i>none</i>}</SelectTrigger>
                            <SelectContent>
                                <SelectItem value={undefined}>
                                    <i>none</i>
                                </SelectItem>
                                {posts?.map((post) => (
                                    <SelectItem key={post.id} value={post.id}>
                                        {post.published_at} &mdash; {post.title}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                )}

                {/* Additional prompt. */}
                {data.type && (
                    <div>
                        <Label htmlFor="postPrompt">Additional prompt</Label>
                        <Textarea id="postPrompt" placeholder="Information that could help the generator." rows={6}
                                  onChange={(e) => setData('prompt', e.target.value)}/>
                    </div>
                )}

                <div className="bg-white border-t-1 flex flex-wrap justify-between sticky bottom-0 py-3 -mx-5 px-5">
                    {error && <Alert className="w-full" type="error" message={error}/>}

                    <Button variant="ghost" type="button" size="lg" onClick={cancelHandler}>Cancel</Button>
                    <LoadingButton size="lg" disabled={!data.type} isLoading={isSaving}>Generate News
                        Post</LoadingButton>
                </div>
            </form>

            <NewsPromptDialog prompt={prompt} type={titleCase(data.type)} open={isPromptOpen}
                              onOpenChange={closePromptHandler}/>

        </AppLayout>
    );
};
