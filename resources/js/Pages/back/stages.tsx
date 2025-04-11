import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';

export default function Stages(readonly {
    stages
}
:
{
    stages: any[]
}
)
{


    return (
        <AppLayout>
            <Head title="Stages"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow text-2xl">Stages</h1>
                <div className="flex gap-1">
                    <Button>Create Stage</Button>
                </div>
            </div>

            <div className="p-4">
                {stages.data.map((stage) => (
                    <Collapsible key={stage.id} className="mb-2">
                        <CollapsibleTrigger
                            className="flex gap-2 p-3 b-2 w-full bg-gray-200 hover:bg-gray-400 justify-between">
                            <span className="flex-grow text-left">{stage.title}</span>
                            <span>...</span>
                            <span>...</span>
                        </CollapsibleTrigger>
                        <CollapsibleContent className="p-3">
                            {stage.description}
                        </CollapsibleContent>
                    </Collapsible>
                ))}
            </div>
        </AppLayout>
    );
}
