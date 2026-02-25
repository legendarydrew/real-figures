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
                confirm: "confirm",
                destructive: "destructive",
                outline: "outline",
                gold: "gold",
                checked: "checked",
                ghost: "ghost",
                link: "link"
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
