import { User } from '.';

export interface News {
    id: string;
    title: string;
    type: string;
    content: string;
    author?: string;
    created_at: string;
    diff_created_at: string;
    updated_at: string;
}

export interface Conversation {
    id: string;
    type: 'private' | 'group';
    name: string;
    description: string | null;
    avatar: string | null;
    created_at: string;
    participants: Array<
        User & {
            role: string;
        }
    >;
    updated_at: string;
    messages?: Array<Chat>;
    last_message?: Chat;
    last_update: string;
}

export interface Chat {
    id: string;
    user?: User;
    conversation?: Conversation;
    message: string;
    type: string;
    metadata: JSON | null;
    updated_at: string;
}
