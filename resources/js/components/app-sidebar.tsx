import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarTrigger
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { BarChart3Icon, Heart, LayoutGrid, Mail, Music, Network, NewspaperIcon, Star, User, User2 } from 'lucide-react';
import AppLogo from './mode/app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/admin',
        icon: LayoutGrid
    },
    {
        title: 'News',
        href: '/admin/news',
        icon: NewspaperIcon
    },
    {
        title: 'Analytics',
        href: '/admin/analytics',
        icon: BarChart3Icon
    },
    {
        title: 'Stages',
        href: '/admin/stages',
        icon: Network
    },
    {
        title: 'Acts',
        href: '/admin/acts',
        icon: User
    },
    {
        title: 'Songs',
        href: '/admin/songs',
        icon: Music
    },
    {
        title: 'Donations',
        href: '/admin/donations',
        icon: Heart
    },
    {
        title: 'Golden Buzzers',
        href: '/admin/golden-buzzers',
        icon: Star
    },
    {
        title: 'Subscribers',
        href: '/admin/subscribers',
        icon: User2
    },
    {
        title: 'Messages',
        href: '/admin/contact',
        icon: Mail
    }
];

const footerNavItems: NavItem[] = [];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem className="relative">
                        <SidebarMenuButton size="lg" asChild>
                            <a href="/" target="_blank">
                                <AppLogo/>
                            </a>
                        </SidebarMenuButton>
                        <SidebarTrigger className="w-auto px-2 h-8 absolute top-1/2 left-full -translate-y-1/2"/>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems}/>
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto"/>
                <NavUser/>
            </SidebarFooter>
        </Sidebar>
    );
}
