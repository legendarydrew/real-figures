import { ChangeEvent, FC, useEffect } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Song } from '@/types';
import { Toaster } from '@/components/ui/toast-message';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import { LanguageCodes } from '@/lib/language-codes';
import { LanguageFlag } from '@/components/language-flag';
import { LoadingButton } from '@/components/ui/loading-button';

interface SongDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
    song?: Song;
    acts: { id: number, name: string }[];
}

type SongForm = {
    act_id: number | '';
    title: string;
}

export const SongDialog: FC<SongDialogProps> = ({ open, onOpenChange, song, acts }) => {
    // useForm() provides some very useful methods.
    const { data, setData, post, patch, errors, setError, clearErrors, processing } = useForm<Required<SongForm>>({
        act_id: '',
        title: '',
        language: 'en',
        url: ''
    });

    useEffect(() => {
        setData({
            title: song?.title ?? '',
            act_id: song?.act_id ?? '',
            language: song?.language ?? 'en',
            url: song?.url ?? ''
        });
        clearErrors();
    }, [song]);

    const isEditing = (): boolean => {
        return !!song?.id;
    }

    const changeTitleHandler = (e: ChangeEvent) => {
        // We are using setData from useForm(), so we don't have to create separate states.
        setData('title', e.target.value);
        setError('title', '');
    };

    const changeActHandler = (value: string) => {
        setData('act_id', parseInt(value));
        setError('act_id', '');
    };

    const changeLanguageHandler = (value: string) => {
        setData('language', value);
        setError('language', '');
    };

    const changeUrlHandler = (e: ChangeEvent) => {
        setData('url', e.target.value);
        setError('url', '');
    };

    const getMatchingActName = (): string | null => {
        if (data.act_id) {
            return acts.find((act) => act.id === data.act_id)?.name ?? null
        }
    };

    const saveHandler = (e: SubmitEvent) => {
        e.preventDefault();

        if (isEditing()) {
            patch(route('songs.update', { id: song.id }), {
                onSuccess: () => {
                    Toaster.success("Song was updated.");
                    onOpenChange();
                },
                preserveScroll: true
            });
        } else {
            post(route('songs.store'), {
                onSuccess: () => {
                    Toaster.success("Song was created.");
                    onOpenChange();
                },
                preserveScroll: true
            });
        }
    };


    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent aria-describedby={undefined}>
                <DialogTitle>{isEditing() ? 'Update' : 'Create'} Song</DialogTitle>
                <form onSubmit={saveHandler}>
                    <div className="mb-2">
                        <Label htmlFor="songAct">Act</Label>

                        {/* Stylised select component - again, no documentation. */}
                        <Select id="songAct" value={data.act_id} onValueChange={changeActHandler}>
                            <SelectTrigger>{getMatchingActName() ?? 'Select an Act'}</SelectTrigger>
                            <SelectContent>
                                {acts.map((act) => (
                                    <SelectItem key={act.id} value={act.id}>{act.name}</SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError className="mt-2" message={errors.act_id}/>
                    </div>

                    <div className="mb-2">
                        <Label htmlFor="songTitle">Song Title</Label>
                        <Input id="songTitle" type="text" className="font-bold" value={data.title}
                               onChange={changeTitleHandler}/>
                        <InputError className="mt-2" message={errors.title}/>
                    </div>

                    <div className="mb-2">
                        <Label htmlFor="songLanguage">Song Language</Label>

                        <Select id="songLanguage" value={data.language} onValueChange={changeLanguageHandler}>
                            <SelectTrigger>
                                {data.language && (<LanguageFlag languageCode={data.language}/>)}
                                <span className="flex-grow text-left px-2">
                                {data.language ? LanguageCodes[data.language] : 'Select a language'}
                                </span>
                            </SelectTrigger>
                            <SelectContent>
                                {Object.keys(LanguageCodes).map((languageCode) => (
                                    <SelectItem key={languageCode} value={languageCode}>
                                        <LanguageFlag languageCode={languageCode}/>
                                        {LanguageCodes[languageCode]}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError className="mt-2" message={errors.act_id}/>
                    </div>

                    <div className="mb-2">
                        <Label htmlFor="songUrl">URL</Label>
                        <Input id="songUrl" type="text" value={data.url}
                               placeholder="YouTube video embed URL"
                               onChange={changeUrlHandler}/>
                        <InputError className="mt-2" message={errors.url}/>
                    </div>


                    <DialogFooter>
                        <LoadingButton variant="default" type="submit" onClick={saveHandler}
                                       isLoading={processing}>Save</LoadingButton>
                        <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
