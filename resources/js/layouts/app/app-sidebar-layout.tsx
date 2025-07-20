import { AppContent } from '@/components/app-content';
import { AppShell } from '@/components/app-shell';
import { AppSidebar } from '@/components/app-sidebar';
import { type BreadcrumbItem } from '@/types';
import { type PropsWithChildren } from 'react';
import { Toaster } from 'react-hot-toast';
import { DialogProvider } from '@/context/dialog-context';

export default function AppSidebarLayout({ children }: PropsWithChildren<{
    breadcrumbs?: BreadcrumbItem[]
}>) {
    return (
        <DialogProvider>
            <AppShell variant="sidebar">
                <Toaster position="bottom-center"/>
                <AppSidebar/>
                <AppContent variant="sidebar">
                    {children}
                </AppContent>
            </AppShell>
        </DialogProvider>
    );
}
