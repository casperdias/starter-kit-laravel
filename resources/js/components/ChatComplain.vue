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

const props = defineProps<{
    user: User;
}>();

const messages = ref<Message[]>([]);
useEcho<Message>('user-complain.' + props.user.id, 'ChatSent', (message) => {
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
            messages.value.push({
                id: response.data.id,
                user: props.user,
                message: form.message,
                taggedUser: null,
                created_at: new Date().toISOString(),
            });
            form.message = '';
        });
};

onMounted(() => {
    axios.get(route('chat.get')).then((response) => {
        messages.value = response.data;
    });
});
</script>

<template>
    <Dialog>
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
                <div class="flex-1 space-y-3 overflow-y-auto rounded border bg-sidebar px-3 py-2 text-xs">
                    <!-- Message List -->
                    <div v-for="(msg, idx) in messages" :key="idx" class="space-y-1">
                        <p class="font-bold">
                            {{ msg.user.name }}
                        </p>
                        <div class="rounded bg-background p-2">
                            {{ msg.message }}
                        </div>
                    </div>
                </div>
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
