import { ChangeEvent, FC, useEffect, useRef } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm, usePage } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Song, Stage } from '@/types';
import toast from 'react-hot-toast';
import { RangeInput } from '@/components/ui/range-input';
import { Checkbox } from '@/components/ui/checkbox';

interface RoundAllocateDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
    stage?: Stage;
    songs?: Song[]
}

type RoundAllocateForm = {
    song_ids: number[];
    per_round: number;
    start_at?: string;
    duration: number;
}

export const RoundAllocateDialog: FC<RoundAllocateDialogProps> = ({ open, onOpenChange, stage, songs }) => {

    const { props } = usePage();

    const { data, setData, reset, post, errors, setError, processing } = useForm<Required<RoundAllocateForm>>({
        song_ids: [],
        per_round: 4,   // number of songs per round.
        start_at: '',
        duration: 7    // one week.
    });

    const minSongs = useRef<number>(props.roundConfig.minSongs);
    const maxSongs = useRef<number>(props.roundConfig.maxSongs);
    const maxDuration = useRef<number>(props.roundConfig.maxDuration);
    const minStartTime = useRef<string>(new Date().toISOString().slice(0, -8));

    useEffect(() => {
        reset('song_ids', 'per_round', 'start_at', 'duration');
        if (open) {
            if (!(songs && songs.length > minSongs.current)) {
                toast.error(`No Songs specified (minimum of ${minSongs.current} required).`);
                onOpenChange();
            } else {
                maxSongs.current = Math.min(songs.length, maxSongs.current, props.roundConfig.maxSongs);
            }
        }
    }, [open]);

    const changeStartsAtHandler = (e: ChangeEvent): void => {
        setData('start_at', e.target.value);
        setError('start_at', '');
    };

    const changeDurationHandler = (value: number): void => {
        setData('duration', value);
        setError('duration', '');
    };

    const changePerRoundHandler = (value: number): void => {
        setData('per_round', value);
        setError('per_round', '');
    };

    const addSongId = (songId: number): void => {
        setData('song_ids', [...new Set([...data.song_ids, songId])]);
        setError('song_ids', '');
    };

    const removeSongId = (songId: number): void => {
        setData('song_ids', data.song_ids.filter((id) => id !== songId));
        setError('song_ids', '');
    };

    const selectAllSongsHandler = (): void => {
        setData('song_ids', songs.map((song) => song.id));
        setError('song_ids', '');
    }

    const selectNoSongsHandler = (): void => {
        setData('song_ids', []);
        setError('song_ids', '');
    }

    const isSongSelected = (songId: number): boolean => {
        return data.song_ids.includes(songId);
    }

    const saveHandler = (e: SubmitEvent): void => {
        e.preventDefault();

        post(route('stages.allocate', { id: stage?.id }), {
            only: ['stages'],
            showProgress: true,
            onSuccess: () => {
                onOpenChange();
            }
        });
    };


    return (
        <Dialog open={open} onClose={onOpenChange}>
            <DialogContent className="lg:w-5xl lg:max-w-[900px]">
                <DialogTitle>Create Rounds for {stage?.title}</DialogTitle>
                <DialogDescription>
                    This will allocate Songs to new Rounds for this Stage.<br/>
                    Songs will be randomly allocated to each Round, based on the settings below.
                </DialogDescription>
                <form onSubmit={saveHandler}>
                    <div className="flex gap-4">

                        <div className="w-2/5">
                            <div className="mb-4">
                                <Label htmlFor="allocateStart">First Round start</Label>
                                <Input id="allocateStart" type="datetime-local" className="w-full"
                                       value={data.start_at}
                                       min={minStartTime.current} onChange={changeStartsAtHandler}/>
                                <InputError className="mt-2" message={errors.start_at}/>
                            </div>

                            <div className="flex gap-2 justify-between">
                                <div>
                                    <Label htmlFor="allocateDuration">Songs per Round</Label>

                                    <RangeInput id="allocateDuration" min={minSongs.current} max={maxSongs.current}
                                                value={data.per_round}
                                                onChange={changePerRoundHandler}/>
                                    <div className="text-sm font-bold text-center">
                                        {data.per_round} songs
                                    </div>
                                    <InputError className="mt-2" message={errors.per_round}/>
                                </div>

                                <div>
                                    <Label htmlFor="allocateDuration">Round duration</Label>

                                    <RangeInput id="allocateDuration" min="1" max={maxDuration.current}
                                                value={data.duration}
                                                onChange={changeDurationHandler}/>
                                    <div className="text-sm font-bold text-center">
                                        {data.duration} day(s)
                                    </div>
                                    <InputError className="mt-2" message={errors.duration}/>
                                </div>
                            </div>
                        </div>

                        <div className="w-3/5">
                            <div className="flex justify-between items-center">
                                <Label>Which Songs?</Label>
                                <div className="flex gap-1 mb-1">
                                    <Button size="sm" className="py-1 px-2 text-xs" type="button" variant="outline"
                                            onClick={selectAllSongsHandler}>All</Button>
                                    <Button size="sm" className="py-1 px-2 text-xs" type="button" variant="outline"
                                            onClick={selectNoSongsHandler}>None</Button>
                                </div>
                            </div>

                            <div className="border rounded-sm p-1 h-[40vh] min-h-[12rem] overflow-y-auto">
                                {songs.map((song) => (
                                    <label htmlFor={`song-${song.id}`} key={song.id}
                                           className="flex gap-2 text-xs items-center p-1 hover:bg-gray-100 select-none cursor-pointer">
                                        <Checkbox id={`song-${song.id}`} value={song.id}
                                                  checked={isSongSelected(song.id)}
                                                  onCheckedChange={(checked) => checked ? addSongId(song.id) : removeSongId(song.id)}/>
                                        <span className="">{song.act.name}</span>
                                        <span className="font-bold">{song.title}</span>
                                    </label>
                                ))}
                            </div>
                            <p className="text-xs m-2"><b>{data.song_ids.length}</b> Song(s) selected.</p>

                            <InputError className="mt-2" message={errors.song_ids}/>
                        </div>
                    </div>

                    <DialogFooter>
                        <Button variant="default" type="submit" disabled={processing}>Create Rounds</Button>
                        <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
