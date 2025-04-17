import { ChangeEvent } from 'react';

// adapted from https://tailwindflex.com/@nour-haider/simple-range-slider
interface RangeInputProps {
    min: number;
    max: number;
    value: number;
    onChange: (value: number) => void;
}

export const RangeInput: React.FC<RangeInputProps> = ({ min, max, onChange, value, ...props }) => {

    const changeHandler = (e: ChangeEvent): void => {
        if (onChange) {
            onChange(e.target.value);
        }
    };

    return (
        <input type="range" min={min} max={max} value={value} onChange={changeHandler}
               className="block w-full py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring"
               {...props} />
    )
}
