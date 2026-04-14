import { ChangeEvent, FC, useEffect } from 'react';
import { Dialog, DialogContent, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Song } from '@/types';
import { RTToast } from '@/components/mode/toast-message';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import { LanguageFlag } from '@/components/mode/language-flag';
import { LoadingButton } from '@/components/mode/loading-button';
import { useLanguages } from '@/context/language-context';
import { SongUrls } from '@/components/admin/song-urls';

interface SongDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
    song?: Song;
    acts: { id: number, name: string, subtitle?: string }[];
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
        urls: []
    });

    const { languageList, matchingLanguage } = useLanguages();

    useEffect(() => {
        setData({
            title: song?.title ?? '',
            act_id: song?.act_id ?? '',
            language: song?.language ?? 'en',
            urls: song?.urls ?? []
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
        setData('act_id', Number.parseInt(value));
        setError('act_id', '');
    };

    const changeLanguageHandler = (value: string) => {
        setData('language', value);
        setError('language', '');
    };

    const updateUrlsHandler = (value: string[]) => {
        setData('urls', value);
        setError('urls', '');
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
                    RTToast.success("Song was updated.");
                    onOpenChange();
                },
                preserveScroll: true
            });
        } else {
            post(route('songs.store'), {
                onSuccess: () => {
                    RTToast.success("Song was created.");
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
                                    <SelectItem key={act.id} value={act.id}>{act.name} {act.subtitle}</SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError message={errors.act_id}/>
                    </div>

                    <div className="mb-2">
                        <Label htmlFor="songTitle">Song Title</Label>
                        <Input id="songTitle" type="text" className="font-bold" value={data.title}
                               onChange={changeTitleHandler}/>
                        <InputError message={errors.title}/>
                    </div>

                    <div className="mb-2">
                        <Label htmlFor="songLanguage">Song Language</Label>

                        <Select id="songLanguage" value={data.language} onValueChange={changeLanguageHandler}>
                            <SelectTrigger>
                                {data.language && (<LanguageFlag languageCode={data.language}/>)}
                                <span className="flex-grow text-left px-2">
                                {data.language ? matchingLanguage(data.language)?.name : 'Select a language'}
                                </span>
                            </SelectTrigger>
                            <SelectContent>
                                {languageList.current.map((language) => (
                                    <SelectItem key={language.code} value={language.code}>
                                        <LanguageFlag languageCode={language.code}/>
                                        {language.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError message={errors.act_id}/>
                    </div>

                    <div className="mb-2">
                        <Label>Video URLs</Label>
                        <SongUrls urls={data.urls} onChange={updateUrlsHandler}/>
                        <InputError message={errors.urls}/>
                    </div>


                    <DialogFooter>
                        <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                        <LoadingButton variant="primary" type="submit" onClick={saveHandler}
                                       isLoading={processing}>Save</LoadingButton>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
