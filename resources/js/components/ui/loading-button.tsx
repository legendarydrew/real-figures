// components/ui/button.tsx
import * as React from "react";
import { Slot, Slottable } from "@radix-ui/react-slot";
import { type VariantProps } from "class-variance-authority";
import { Loader2 } from "lucide-react";
import { cn } from "@/lib/utils";
import { buttonVariants } from '@/components/ui/button';

// Adapted from https://github.com/shadcn-ui/ui/issues/3117#issuecomment-2131108582

export interface LoadingButtonProps
    extends React.ButtonHTMLAttributes<HTMLButtonElement>,
        VariantProps<typeof buttonVariants> {
    asChild?: boolean;
    isLoading?: boolean;
}

const LoadingButton = React.forwardRef<HTMLButtonElement, LoadingButtonProps>(
    (
        {
            className,
            isLoading = false,
            children,
            disabled,
            variant,
            size,
            asChild = false,
            ...props
        },
        ref
    ) => {
        const Comp = asChild ? Slot : "button";
        return (
            <Comp
                className={cn(buttonVariants({ variant, size, className }))}
                ref={ref}
                disabled={isLoading || disabled}
                {...props}
            >
                {isLoading && (
                    <Loader2 className="absolute h-5 w-5 animate-spin"/>
                )}
                <Slottable>
                    <span className={isLoading ? 'invisible px-1' : ''}>{children}</span>
                </Slottable>
            </Comp>
        );
    }
);
LoadingButton.displayName = "Button";

export { LoadingButton };
