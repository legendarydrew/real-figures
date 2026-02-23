import * as React from "react"
import { Slot } from "@radix-ui/react-slot"
import { cva, type VariantProps } from "class-variance-authority"

import { cn } from "@/lib/utils"

const buttonVariants = cva(
    "button",
    {
        variants: {
            variant: {
                default: "",
                primary: "primary",
                secondary: "secondary",
                confirm: "display-text bg-green-700 text-green-50 shadow-xs hover:bg-green-700/90 focus-visible:ring-green-700/20 dark:focus-visible:ring-green-700/40",
                destructive: "display-text bg-destructive text-white shadow-xs hover:bg-destructive/90 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40",
                outline: "display-text border border-input bg-background shadow-xs hover:bg-accent hover:text-accent-foreground",
                gold: "gold",
                checked: "display-text bg-indigo-700 text-white shadow-xs hover:bg-indigo-500/80 focus-visible:ring-indigo-700/20 dark:focus-visible:ring-indigo-500/40",
                ghost: "display-text hover:bg-accent hover:text-accent-foreground",
                link: "text-indigo-700 underline-offset-4 hover:underline"
            },
            size: {
                default: "",
                sm: "small",
                lg: "large",
                icon: "icon"
            }
        },
        defaultVariants: {
            variant: "default",
            size: "default"
        }
    }
)

function Button({
                    className,
                    variant,
                    size,
                    asChild = false,
                    ...props
                }: React.ComponentProps<"button"> &
    VariantProps<typeof buttonVariants> & {
    asChild?: boolean
}) {
    const Comp = asChild ? Slot : "button"

    return (
        <Comp
            data-slot="button"
            className={cn(buttonVariants({ variant, size, className }))}
            {...props}
        />
    )
}

export { Button, buttonVariants }
