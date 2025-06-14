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
    showActs: boolean;
    sidebarOpen: boolean;
    flash: {
        message: string;
        track: {
            category: string;
            label: string;
            action?: string;
        };
    };

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
    golden_buzzer_perks?: string;
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
    profileContent?: {
        description: string;
    };
    image?: string;
    meta: {
        is_fan_favourite: boolean;
        members: {
            name: string;
            role: string;
        }[];
    };
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
    video_id?: string;
}

export interface Round {
    id: number;
    title: string;
    full_title: string;
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
    was_read: boolean;
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

export interface Subscriber {
    id: number;
    email: string;
    created_at: string;
    updated_at: string;
}

export interface SubscriberPost {
    id: number;
    title: string;
    sent_count: number;
    body?: string;
    created_at: string;
}

export interface Donation {
    id: number;
    name: string;
    amount?: string;
    created_at: string;
    is_anonymous: boolean;
}

export interface GoldenBuzzer {
    id: number;
    name: string;
    created_at: string;
    is_anonymous: boolean;
    amount: string;
    round: string;
    song: {
        title: string;
        language: string;
        act_id: number;
        act: {
            name: string;
            image: string;
        }
    };
}

export interface GoldenBuzzerBreakdown {
    rounds: {
        round_id: number;
        round_title: string;
        amount_raised: string;
    }[];
    songs: {
        song: Song,
        buzzer_count: number;
        amount_raised: string;
    }[];
}

export interface RoundVoteBreakdown {
    id: number;
    title: string;  // The Round's full title.
    vote_count: number;
    songs: {
        song: Song,
        score: number;
        first_choice_votes: number;
        second_choice_votes: number;
        third_choice_votes: number;
    }[];
}
