import { Conversation, User } from '@/types';

export const conversationInfo = (data: Conversation, me: User) => {
    if (data.type == 'group') {
        return {
            avatar: data.avatar,
            name: data.name,
            last_message: data.last_message?.message ?? '-',
            last_update: data.last_message?.updated_at ?? data.created_at,
            members: data.participants.map((member) => member.name),
        };
    } else {
        const otherUser = data.participants.find((user) => user.id !== me.id);

        return {
            avatar: otherUser?.avatar,
            name: otherUser?.name,
            last_message: data.last_message?.message ?? '-',
            last_update: data.last_message?.updated_at ?? data.created_at,
            members: data.participants.map((member) => member.name),
        };
    }
};
