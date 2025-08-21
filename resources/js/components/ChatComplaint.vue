<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Message, User } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { echo, useEchoPresence } from '@laravel/echo-vue';
import axios from 'axios';
import { MessageCircleMore, Send } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import ChatBox from './ChatBox.vue';
import { Textarea } from './ui/textarea';

const props = defineProps<{
    user: User;
}>();

const messages = ref<Message[]>([]);

const form = useForm({
    message: '',
});

const { channel } = useEchoPresence('user-complaint.' + props.user.id);

// Send Message
const sendMessage = () => {
    const socketId = echo().socketId();
    axios
        .post(
            route('chat.send'),
            {
                message: form.message,
            },
            {
                headers: {
                    'X-Socket-Id': socketId,
                },
            },
        )
        .then((response) => {
            const now = new Date();
            const formattedDate = new Intl.DateTimeFormat('en-GB', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZoneName: 'short',
            })
                .format(now)
                .replace(',', '');
            messages.value.push({
                id: response.data.id,
                user: props.user,
                message: form.message,
                taggedUser: null,
                created_at: formattedDate,
            });
            form.message = '';
        });
};

const adminOnline = ref<{ id: string | number; name: string; is_admin: boolean }[]>([]);

onMounted(() => {
    axios.get(route('chat.get')).then((response) => {
        messages.value = response.data;
    });
    channel()
        .listen('ChatSent', (message: Message) => {
            messages.value.push(message);
        })
        .here((users: { id: string | number; name: string; is_admin: boolean }[]) => {
            adminOnline.value = users.filter((user) => user.is_admin);
        })
        .joining((user: { id: string | number; name: string; is_admin: boolean }) => {
            if (user.is_admin) {
                adminOnline.value.push(user);
            }
        })
        .leaving((user: { id: string | number; name: string; is_admin: boolean }) => {
            adminOnline.value = adminOnline.value.filter((u) => u.id !== user.id);
        });
});

const open = ref(false);
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button class="fixed right-6 bottom-6 z-50" size="lg">
                <MessageCircleMore />
            </Button>
        </DialogTrigger>
        <DialogContent>
            <div class="flex h-96 flex-col space-y-4">
                <DialogHeader>
                    <DialogTitle>Chat with Admin</DialogTitle>
                    <DialogDescription> Ask your questions or report issues here. </DialogDescription>
                    <div class="flex items-center gap-2">
                        <div class="group relative flex h-3 w-3">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full"
                                :class="adminOnline.length > 0 ? 'bg-green-600' : 'bg-red-600'"
                            ></span>
                            <span class="inline-block h-3 w-3 rounded-full" :class="adminOnline.length > 0 ? 'bg-green-600' : 'bg-red-600'"></span>
                        </div>
                        <p class="text-xs font-semibold">
                            {{ adminOnline.length > 0 ? 'Online' : 'Offline' }}
                        </p>
                    </div>
                </DialogHeader>
                <ChatBox :messages="messages" :user="user" />
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <Textarea v-model="form.message" placeholder="Type your message..." class="flex-1 rounded border px-2 py-1" autocomplete="off" />
                    <Button type="submit" :disabled="!form.message.trim()">
                        <Send />
                    </Button>
                </form>
            </div>
        </DialogContent>
    </Dialog>
</template>
