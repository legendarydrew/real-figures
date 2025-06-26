import { Head, router, useForm } from '@inertiajs/react';
import React, { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { LoadingButton } from '@/components/mode/loading-button';
import { titleCase } from '@/lib/utils';

export default function NewsGeneratePage({ types }: Readonly<{ types }>) {

    const { data, setData } = useForm({
        type: undefined,
        title: '',
        content: ''
    });

    const [isSaving, setIsSaving] = useState<boolean>(false);

    const cancelHandler = (): void => {
        router.visit(route('admin.news'));
    };

    const selectTypeHandler = (type: string): void => {
        console.log('selectTypeHandler', type);
        setData('type', type);
    };

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

                <div className="bg-white border-t-1 flex justify-between sticky bottom-0 py-3">
                    <Button variant="ghost" type="button" size="lg" onClick={cancelHandler}>Cancel</Button>
                    <LoadingButton size="lg" isLoading={isSaving}>Save Act</LoadingButton>
                </div>
            </form>

        </AppLayout>
    );
};
