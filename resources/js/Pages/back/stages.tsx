import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { StageDialog } from '@/components/admin/stage-dialog';
import { useState } from 'react';
import { ChevronDown, Edit, Trash } from 'lucide-react';
import { Stage } from '@/types';
import { DeleteStageDialog } from '@/components/admin/delete-stage-dialog';
import { StageStatusTag } from '@/components/ui/stage-status-tag';

export default function Stages({ stages }: Readonly<{ stages: any[] }>) {

    const [currentStage, setCurrentStage] = useState<Stage>();
    const [isEditDialogOpen, setIsEditDialogOpen] = useState<boolean>(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);

    const editHandler = (stage: Stage) => {
        setCurrentStage(stage);
        setIsEditDialogOpen(true);
    }

    const deleteHandler = (stage: Stage) => {
        setCurrentStage(stage);
        setIsDeleteDialogOpen(true);
    }

    return (
        <AppLayout>
            <Head title="Stages"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow font-bold text-2xl">Stages</h1>
                <div className="flex gap-1">
                    <Button onClick={editHandler}>Create Stage</Button>
                </div>
            </div>

            <div className="p-4">
                {stages.data.map((stage) => (
                    <Collapsible key={stage.id} className="mb-2">
                        <div
                            className="flex gap-2 py-2 px-3 b-2 w-full bg-gray-200 hover:bg-gray-300 items-center justify-between">
                            <span className="flex-grow font-bold text-left">{stage.title}</span>
                            <StageStatusTag stage={stage}/>
                            <Button variant="secondary" className="p-3 cursor-pointer"
                                    onClick={() => editHandler(stage)}
                                    title="Edit Stage">
                                <Edit className="h-3 w-3"/>
                            </Button>
                            <Button variant="destructive" className="p-3 cursor-pointer"
                                    onClick={() => deleteHandler(stage)}
                                    title="Delete Stage">
                                <Trash className="h-3 w-3"/>
                            </Button>
                            <CollapsibleTrigger className="p-3 hover:bg-gray-400 cursor-pointer" title="Expand">
                                <ChevronDown className="h-3 w-3"/>
                            </CollapsibleTrigger>
                        </div>
                        <CollapsibleContent className="p-3">
                            {stage.description}
                        </CollapsibleContent>
                    </Collapsible>
                ))}
            </div>

            <StageDialog stage={currentStage} open={isEditDialogOpen} onOpenChange={() => setIsEditDialogOpen(false)}/>
            <DeleteStageDialog stage={currentStage} open={isDeleteDialogOpen}
                               onOpenChange={() => setIsDeleteDialogOpen(false)}/>
        </AppLayout>
    );
}
