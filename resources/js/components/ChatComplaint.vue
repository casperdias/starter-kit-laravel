<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Message, User } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { echo, useEcho } from '@laravel/echo-vue';
import axios from 'axios';
import { MessageCircleMore, Send } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import ChatBox from './ChatBox.vue';

const props = defineProps<{
    user: User;
}>();

const messages = ref<Message[]>([]);
useEcho<Message>('user-complaint.' + props.user.id, 'ChatSent', (message) => {
    messages.value.push(message);
});

const form = useForm({
    message: '',
});

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

onMounted(() => {
    axios.get(route('chat.get')).then((response) => {
        messages.value = response.data;
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
                </DialogHeader>
                <ChatBox :messages="messages" :user="user" />
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <Input
                        v-model="form.message"
                        type="text"
                        placeholder="Type your message..."
                        class="flex-1 rounded border px-2 py-1"
                        autocomplete="off"
                    />
                    <Button type="submit" :disabled="!form.message.trim()">
                        <Send />
                    </Button>
                </form>
            </div>
        </DialogContent>
    </Dialog>
</template>
