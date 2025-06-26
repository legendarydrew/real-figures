import { Head, router, useForm } from '@inertiajs/react';
import React, { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { LoadingButton } from '@/components/mode/loading-button';
import { titleCase } from '@/lib/utils';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';

interface NewsGeneratePageProps {
    types: string[];
    acts?: { id: number, name: string; }[];
    rounds?: { id: number; title: string; }[];
    stages?: { id: number; title: string; status: string; }[];
}

export default function NewsGeneratePage({ types, acts, rounds, stages }: Readonly<NewsGeneratePageProps>) {

    const { data, setData } = useForm({
        type: undefined, // the type of News Post to create.
        references: [], // ID(s) of the Stage/Round/Acts to refer to.
        previous: undefined,  // [optional] previous News Post to reference.
        prompt: "" // user-entered information to help OpenAI.
    });

    const [isSaving, setIsSaving] = useState<boolean>(false);

    const cancelHandler = (): void => {
        router.visit(route('admin.news'));
    };

    const selectTypeHandler = (type: string): void => {
        setData('type', type);

        let additionalInfo = [];
        switch (type) {
            case 'stage':
                additionalInfo = ['stages'];
                break;
            case 'act':
                additionalInfo = ['acts'];
                break;
            case 'round':
                additionalInfo = ['rounds'];
                break;
            default:
                additionalInfo = [];
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
        console.log('select reference', value);
        setData('references', [value]);
    }

    return (
        <AppLayout>
            <Head title="Generate News Post"/>

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">Generate a News Post</h1>
            </div>

            <form className="flex flex-col gap-3 px-5">

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
                            <SelectTrigger>{data.reference_id ?? 'Select a Round...'}</SelectTrigger>
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
                    <fieldset>
                        <legend className="font-normal text-sm mb-3">Select one or more Acts...</legend>
                        {acts.map((act) => (
                            <Label key={act.id} className="flex items-center gap-2">
                                <Checkbox value={act.id}/> {act.name}
                            </Label>
                        ))}
                    </fieldset>
                )}

                <div className="bg-white border-t-1 flex justify-between sticky bottom-0 py-3">
                    <Button variant="ghost" type="button" size="lg" onClick={cancelHandler}>Cancel</Button>
                    <LoadingButton size="lg" isLoading={isSaving}>Save Act</LoadingButton>
                </div>
            </form>

        </AppLayout>
    );
};
