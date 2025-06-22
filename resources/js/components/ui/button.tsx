import * as React from "react"
import { Slot } from "@radix-ui/react-slot"
import { cva, type VariantProps } from "class-variance-authority"

import { cn } from "@/lib/utils"

const buttonVariants = cva(
    "cursor-pointer inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive",
    {
        variants: {
            variant: {
                default: "display-text bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 focus-visible:ring-primary/20 dark:focus-visible:ring-primary/40",
                secondary: "display-text bg-secondary text-secondary-foreground shadow-xs hover:bg-secondary/80 focus-visible:ring-secondary/20 dark:focus-visible:ring-secondary/40",
                confirm: "display-text bg-green-700 text-green-50 shadow-xs hover:bg-green-700/90 focus-visible:ring-green-700/20 dark:focus-visible:ring-green-700/40",
                destructive: "display-text bg-destructive text-white shadow-xs hover:bg-destructive/90 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40",
                outline: "display-text border border-input bg-background shadow-xs hover:bg-accent hover:text-accent-foreground",
                gold: "display-text bg-amber-400 text-amber-900 shadow-xs hover:bg-amber-400/90 focus-visible:ring-amber-400/20 dark:focus-visible:ring-amber-900/40",
                checked: "display-text bg-indigo-700 text-white shadow-xs hover:bg-indigo-500/80 focus-visible:ring-indigo-700/20 dark:focus-visible:ring-indigo-500/40",
                ghost: "display-text hover:bg-accent hover:text-accent-foreground",
                link: "text-primary underline-offset-4 hover:underline"
            },
            size: {
                default: "h-9 px-4 py-2 has-[>svg]:px-3",
                sm: "h-8 rounded-md px-3 has-[>svg]:px-2.5",
                lg: "h-10 rounded-md px-6 has-[>svg]:px-4",
                icon: "size-9"
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
