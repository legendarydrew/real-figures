import { AppContent } from '@/components/app-content';
import { AppShell } from '@/components/app-shell';
import { AppSidebar } from '@/components/app-sidebar';
import { type BreadcrumbItem } from '@/types';
import { type PropsWithChildren } from 'react';
import { Toaster } from 'react-hot-toast';

export default function AppSidebarLayout({ children }: PropsWithChildren<{
    breadcrumbs?: BreadcrumbItem[]
}>) {
    return (
        <AppShell variant="sidebar">
            <Toaster position="bottom-left"/>
            <AppSidebar/>
            <AppContent variant="sidebar">
                {children}
            </AppContent>
        </AppShell>
    );
}
