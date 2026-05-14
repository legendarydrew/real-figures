import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import React, { useState } from 'react';
import { UploadIcon } from 'lucide-react';
import { AdminHeader } from '@/components/admin/admin-header';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { Alert } from '@/components/mode/alert';

interface Props {
    currentRound: string;
}

export default function DumbrickPage({ currentRound }: Readonly<Props>) {

    const [isUploading, setIsUploading] = useState<boolean>(false);

    const selectFileHandler = (e) => {
        if (isUploading) {
            return;
        }

        setIsUploading(true);
        const formData = new FormData();
        formData.append("data", e.target.files[0]);
        axios.post(route('dumbrick.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then((response) => {
                const voteCount = response.data.votes;
                RTToast.success(`${voteCount} Dumbrick ${voteCount > 1 ? 'votes' : 'vote'} cast.`);
            })
            .catch((err) => {
                RTToast.error(err.error.message);
            })
            .finally(() => {
                setIsUploading(false);
                e.target.filename = undefined; // to allow selecting the same file more than once.
            });
    };

    return (
        <AppLayout>
            <Head title="Project Dumbrick"/>

            <div className="admin-content">
                <AdminHeader title="Project Dumbrick"/>

                <p>Use data acquired from the Dumbrick to cast votes for the current Round in the Contest.</p>

                {currentRound ? (
                    // Upload a file.
                    <form>
                        <label className="button primary small" htmlFor="dbFile">
                            <UploadIcon/>
                            Upload Dumbrick data
                        </label>
                        <input id="dbFile" type="file" accept="text/plain, .dat" onChange={selectFileHandler}
                               className="hidden"/>
                    </form>
                ) : (
                    <Alert type="error" message="No current Round."/>
                )}
            </div>
        </AppLayout>
    );
}
