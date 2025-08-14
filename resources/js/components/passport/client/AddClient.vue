<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle, Plus, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const page = usePage();

const form = useForm({
    name: '',
    redirect: [''],
    confidential: false,
    device_flow: true,
});

const dialog = ref(false);

const addRedirectUrl = () => {
    form.redirect.push('');
};

const removeRedirectUrl = (index: number) => {
    if (form.redirect.length > 1) {
        form.redirect.splice(index, 1);
    }
};

const copyToClipboard = async (text: string, label: string) => {
    try {
        await navigator.clipboard.writeText(text);
        toast.success(`${label} copied to clipboard!`, {
            closeButton: true,
        });
    } catch {
        toast.error(`Failed to copy ${label.toLowerCase()}`, {
            closeButton: true,
        });
    }
};

const submit = () => {
    form.post(route('passport-clients.store'), {
        onSuccess: () => {
            form.reset();
            dialog.value = false;

            // Extract ID and Secret from the response or flash message
            const flashMessage = page.props.flash.message || '';
            const idMatch = flashMessage.match(/ID:\s*([^\s\n]+)/);
            const secretMatch = flashMessage.match(/Secret:\s*([^\s\n]+)/);

            const clientId = idMatch ? idMatch[1] : '';
            const clientSecret = secretMatch ? secretMatch[1] : '';

            toast.success('Client created successfully', {
                description: 'Your OAuth client has been created. Click to copy credentials.',
                closeButton: true,
                action:
                    clientId && clientSecret
                        ? {
                              label: 'Copy',
                              onClick: () => copyToClipboard(`ID: ${clientId}\nSecret: ${clientSecret}`, 'Credentials'),
                          }
                        : undefined,
                duration: 10000,
            });
        },
        onError: (error) => {
            toast.error('Failed to create client', {
                description: error.message || 'The client could not be created.',
                closeButton: true,
            });
        },
    });
};
</script>

<template>
    <Dialog v-model:open="dialog">
        <DialogTrigger as-child>
            <Button class="w-full cursor-pointer">
                <Plus />
                Add Client
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle> Add New Client </DialogTitle>
                <DialogDescription> Use the form below to create a new client. </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="submit" class="grid gap-4">
                <div class="grid gap-4">
                    <!-- Name -->
                    <Label for="name">Name</Label>
                    <Input id="name" v-model="form.name" placeholder="Nama Client" />
                    <InputError :message="form.errors.name" />

                    <!-- Redirect URL (array<string>) -->
                    <Label for="redirect">Redirect URLs</Label>
                    <div class="space-y-2">
                        <div v-for="(url, index) in form.redirect" :key="index" class="flex gap-2">
                            <Input
                                :id="`redirect-${index}`"
                                v-model="form.redirect[index]"
                                placeholder="https://example.com/callback"
                                class="flex-1"
                            />
                            <Button
                                type="button"
                                variant="destructive"
                                size="icon"
                                @click="removeRedirectUrl(index)"
                                :disabled="form.redirect.length === 1"
                                class="shrink-0"
                            >
                                <X class="h-4 w-4" />
                            </Button>
                        </div>
                        <Button type="button" variant="outline" @click="addRedirectUrl" class="w-full">
                            <Plus class="mr-2 h-4 w-4" />
                            Add Another URL
                        </Button>
                    </div>
                    <InputError :message="form.errors.redirect" />

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="flex items-center space-x-2">
                            <Switch id="confidential" v-model="form.confidential" />
                            <Label for="confidential">Confidential</Label>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Switch id="device_flow" v-model="form.device_flow" />
                            <Label for="device_flow">Device Flow</Label>
                        </div>
                    </div>
                </div>
                <DialogFooter>
                    <Button type="submit" :disabled="form.processing" class="cursor-pointer">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        <Plus v-else />
                        Tambah Client
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
