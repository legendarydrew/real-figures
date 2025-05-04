import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;

    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;

    [key: string]: unknown; // This allows for additional properties...
}


export interface Stage {
    id?: number;
    title: string;
    description: string;
    status?: {
        text: string;
        choose_winners: boolean;
        has_started: boolean;
        has_ended: boolean;
        manual_vote: boolean;
    };
    rounds?: Round[];
    winners?: StageWinner[];
}

export interface Act {
    id?: number;
    name: string;
    has_profile: boolean;
    profile?: {
        description: string;
    };
    image?: string;
}

export interface Song {
    id?: number;
    act_id: number;
    title: string;
    language: string;
    play_count: number;
    act: {
        name: string;
        image?: string;
    };
    url: string;
}

export interface Round {
    id: number;
    title: string;
    starts_at: string;
    ends_at: string;
    songs?: Song[];
    vote_count: number;
}

export interface PaginatedResponse<T> {
    data: T[],
    meta: {
        pagination: {
            total: number;
            count: number;
            per_page: number;
            current_page: number;
            total_pages: number;
            links: { [key: string]: string };
        }
    }
}

export interface ContactMessage {
    id: number;
    name: string;
    email: string;
    ip?: string;
    body: string;
    sent_at: string;
    is_spam: boolean;
}

export interface ManualVoteRoundChoice {
    first: number;
    second: number;
    third: number;
}

export interface StageWinner {
    round: string;
    song: {
        title: string;
        act: Act;
    },
    is_winner: boolean;
}
