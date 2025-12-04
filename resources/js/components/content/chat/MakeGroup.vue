<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Stepper, StepperDescription, StepperItem, StepperSeparator, StepperTitle } from '@/components/ui/stepper';
import { Textarea } from '@/components/ui/textarea';
import UserInfo from '@/components/UserInfo.vue';
import { useUserFetch } from '@/composables/helper';
import { useRoute } from '@/composables/useRoute';
import { User } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { useFileDialog, useObjectUrl } from '@vueuse/core';
import {
    ArchiveX,
    Check,
    ChevronDown,
    FileText,
    Image as ImageIcon,
    LoaderCircle,
    MessagesSquare,
    Minus,
    Plus,
    Search,
    SendHorizonal,
    Users,
    UsersRound,
    X,
} from 'lucide-vue-next';
import { onBeforeUnmount, ref, watch } from 'vue';

const route = useRoute();

const form = useForm({
    name: '',
    type: 'group',
    description: '',
    avatar: null as File | null,
    members: [] as User[],
});

const stepIndex = ref(1);
const steps = [
    {
        step: 1,
        title: 'Group Information',
        description: 'Provide group information',
        icon: FileText,
    },
    {
        step: 2,
        title: 'Group Member',
        description: 'Choose member',
        icon: Users,
    },
    {
        step: 3,
        title: 'Confirmation',
        description: 'Final Check',
        icon: SendHorizonal,
    },
];

const { files, open, reset, onChange } = useFileDialog({
    accept: 'image/*',
    multiple: false,
});

const avatarFile = ref<File | null>(null);
const avatarUrl = useObjectUrl(avatarFile);

onChange((files) => {
    if (files && files[0]) {
        const file = files[0];

        form.avatar = file;
        avatarFile.value = file;
    } else {
        form.avatar = null;
        avatarFile.value = null;
    }
});

const searchTerm = ref('');
const dialogOpen = ref(false);
let searchTimeout: ReturnType<typeof setTimeout>;

const { users, hasMore, isLoading, isLoadingMore, fetchUsers, loadMore, resetUsers } = useUserFetch();

watch(dialogOpen, (isOpen) => {
    if (isOpen) {
        fetchUsers();
    } else {
        searchTerm.value = '';
        resetUsers();
    }
});

watch(searchTerm, (newSearchTerm) => {
    if (!dialogOpen.value) return;

    clearTimeout(searchTimeout);

    if (newSearchTerm.trim() === '') {
        fetchUsers();
        return;
    }

    searchTimeout = setTimeout(() => {
        fetchUsers(newSearchTerm);
    }, 500);
});

onBeforeUnmount(() => {
    if (searchTimeout) clearTimeout(searchTimeout);
});

const createGroup = () => {
    form.post(route('chats.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Dialog v-model:open="dialogOpen">
        <DialogTrigger as-child>
            <Button class="w-full">
                <UsersRound />
                <p class="hidden lg:block">Group</p>
            </Button>
        </DialogTrigger>
        <DialogContent :hide-close="true">
            <DialogHeader class="flex flex-row items-center justify-between">
                <div class="flex items-center gap-3">
                    <MessagesSquare class="size-10" />
                    <div class="hidden max-w-[400px] space-y-2 md:block">
                        <DialogTitle>Group Chat</DialogTitle>
                        <DialogDescription>Fill Information, Select User, Create Group</DialogDescription>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <DialogClose as-child>
                        <Button variant="destructive" size="sm">
                            <X class="h-4 w-4" />
                            <p class="hidden md:block">Tutup</p>
                        </Button>
                    </DialogClose>
                </div>
            </DialogHeader>
            <Stepper v-model="stepIndex" class="flex flex-col gap-2">
                <div class="flex-start flex w-full gap-2">
                    <StepperItem
                        v-for="item in steps"
                        :key="item.step"
                        v-slot="{ state }"
                        class="relative flex w-full flex-col items-center justify-center"
                        :step="item.step"
                    >
                        <Button
                            :variant="state === 'completed' || state === 'active' ? 'default' : 'outline'"
                            size="icon"
                            class="z-10 shrink-0 rounded-full"
                            :class="[state === 'active' && 'ring-2 ring-ring ring-offset-2 ring-offset-background']"
                        >
                            <Check v-if="state === 'completed'" class="size-5" />
                            <component v-else :is="item.icon" class="size-5" />
                        </Button>
                        <StepperSeparator
                            v-if="item.step !== steps[steps.length - 1]?.step"
                            class="absolute top-5 right-[calc(-50%+10px)] left-[calc(50%+20px)] block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary"
                        />
                        <div class="flex flex-col items-center">
                            <StepperTitle>
                                {{ item.title }}
                            </StepperTitle>
                            <StepperDescription>
                                {{ item.description }}
                            </StepperDescription>
                        </div>
                    </StepperItem>
                </div>

                <div v-if="stepIndex === 1" class="grid gap-4">
                    <!-- Avatar Preview and Upload Section -->
                    <div class="space-y-4">
                        <Label for="avatar">Avatar</Label>
                        <div class="flex flex-col items-center gap-4 sm:flex-row">
                            <!-- Avatar Preview -->
                            <div class="relative">
                                <div
                                    class="relative h-24 w-24 overflow-hidden rounded-full border-2 border-dashed border-muted-foreground/30 bg-muted/50"
                                >
                                    <template v-if="avatarUrl">
                                        <img :src="avatarUrl" alt="Avatar preview" class="h-full w-full object-cover" />
                                    </template>
                                    <template v-else>
                                        <div class="flex h-full w-full items-center justify-center">
                                            <ImageIcon class="h-12 w-12 text-muted-foreground/50" />
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Upload Controls -->
                            <div class="flex-1 space-y-2">
                                <div class="flex gap-2">
                                    <Button type="button" variant="outline" @click="open">
                                        <ImageIcon class="mr-2 h-4 w-4" />
                                        {{ files ? 'Change Avatar' : 'Upload Avatar' }}
                                    </Button>

                                    <Button v-if="avatarUrl" type="button" variant="destructive" @click="reset">
                                        <X class="mr-2 h-4 w-4" />
                                        Remove
                                    </Button>
                                </div>

                                <div>
                                    <p class="text-xs text-muted-foreground">Recommended: Square image, max 2MB. JPG, PNG, or WebP.</p>
                                    <InputError :message="form.errors.avatar" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group Name -->
                    <div class="space-y-2">
                        <Label for="name">Group Name</Label>
                        <Input id="name" v-model="form.name" placeholder="Enter group name" :maxlength="50" />
                        <p class="text-xs text-muted-foreground">{{ form.name.length }}/50 characters</p>
                        <InputError :message="form.errors.name" />
                    </div>

                    <!-- Description -->
                    <div class="flex min-w-0 flex-col space-y-2">
                        <Label for="description">Description</Label>
                        <Textarea
                            id="description"
                            v-model="form.description"
                            placeholder="Enter group description (optional)"
                            :maxlength="200"
                            class="w-full min-w-0 overflow-auto"
                        />
                        <p class="text-xs text-muted-foreground">{{ form.description.length }}/200 characters</p>
                        <InputError :message="form.errors.description" />
                    </div>
                </div>

                <div v-if="stepIndex === 2" class="grid gap-4">
                    <div class="relative flex h-full w-full items-center">
                        <Input
                            id="search"
                            type="text"
                            name="search"
                            placeholder="Search members..."
                            class="w-full pl-10"
                            v-model="searchTerm"
                            :disabled="isLoading"
                        />
                        <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                            <Search class="size-6 text-muted-foreground" />
                        </span>
                    </div>

                    <p class="text-xs text-muted-foreground">{{ form.members.length }} Member/s</p>

                    <div class="max-h-96 overflow-y-auto">
                        <div v-if="isLoading" class="flex flex-col items-center justify-center space-y-2 py-8">
                            <LoaderCircle class="size-10 animate-spin" />
                            <p class="font-semibold">Loading users...</p>
                        </div>
                        <div v-else-if="users.length === 0" class="flex flex-col items-center justify-center space-y-2 py-8">
                            <ArchiveX class="size-10" />
                            <p class="font-semibold">No users found.</p>
                        </div>
                        <div v-else class="space-y-3">
                            <div v-for="user in users" :key="user.id" class="flex items-center justify-between rounded border p-2">
                                <div class="flex items-center gap-2">
                                    <UserInfo :user="user" :show-email="true" />
                                </div>
                                <Button size="sm" @click="form.members.push(user)" v-if="!form.members.some((member) => user.id === member.id)">
                                    <Plus />
                                </Button>
                                <Button
                                    size="sm"
                                    variant="destructive"
                                    @click="form.members = form.members.filter((member) => member.id !== user.id)"
                                    v-else
                                >
                                    <Minus />
                                </Button>
                            </div>

                            <div v-if="isLoadingMore" class="flex items-center justify-center py-4">
                                <LoaderCircle class="size-6 animate-spin" />
                                <span class="ml-2 text-sm">Loading more...</span>
                            </div>
                        </div>
                    </div>

                    <div v-if="hasMore && !isLoading && users.length > 0" class="flex justify-center">
                        <Button @click="loadMore(searchTerm)" variant="outline" size="sm" :disabled="isLoadingMore" class="w-full">
                            <ChevronDown class="mr-2 h-4 w-4" />
                            Load More Users
                        </Button>
                    </div>
                </div>

                <div v-if="stepIndex === 3" class="grid gap-4">
                    <!-- Group Information Summary -->
                    <div class="space-y-6">
                        <div class="space-y-4 rounded-lg border p-4">
                            <h3 class="text-lg font-semibold">Group Details</h3>

                            <!-- Avatar Preview -->
                            <div class="flex items-center gap-4">
                                <div class="relative h-20 w-20 overflow-hidden rounded-full border">
                                    <template v-if="avatarUrl">
                                        <img :src="avatarUrl" alt="Group avatar" class="h-full w-full object-cover" />
                                    </template>
                                    <template v-else>
                                        <div class="flex h-full w-full items-center justify-center bg-muted">
                                            <ImageIcon class="h-8 w-8 text-muted-foreground" />
                                        </div>
                                    </template>
                                </div>

                                <div class="flex-1 space-y-2">
                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">Group Name</p>
                                        <p class="text-lg font-semibold">{{ form.name || '-' }}</p>
                                        <InputError :message="form.errors.name" />
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-muted-foreground">Description</p>
                                        <p class="text-sm">{{ form.description || '-' }}</p>
                                        <InputError :message="form.errors.description" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Members Summary -->
                        <div class="space-y-4 rounded-lg border p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold">Members</h3>
                                <span class="rounded-full bg-primary/10 px-3 py-1 text-sm font-medium"> {{ form.members.length }} member(s) </span>
                            </div>

                            <div v-if="form.members.length === 0" class="flex flex-col items-center justify-center py-6">
                                <UsersRound class="mb-2 h-12 w-12 text-muted-foreground" />
                                <p class="text-muted-foreground">No members selected</p>
                            </div>

                            <div v-else class="grid max-h-40 grid-cols-1 gap-3 overflow-auto sm:grid-cols-2">
                                <div v-for="member in form.members" :key="member.id" class="flex items-center gap-3 rounded-lg border p-3">
                                    <div class="relative h-10 w-10 overflow-hidden rounded-full">
                                        <img v-if="member.avatar" :src="member.avatar" :alt="member.name" class="h-full w-full object-cover" />
                                        <div v-else class="flex h-full w-full items-center justify-center bg-primary/10">
                                            <span class="font-medium text-primary">
                                                {{ member.name.charAt(0).toUpperCase() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate font-medium">{{ member.name }}</p>
                                        <p class="truncate text-sm text-muted-foreground">{{ member.email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <Button :disabled="stepIndex === 1" variant="outline" size="sm" @click="stepIndex--"> Back </Button>
                    <Button v-if="stepIndex === steps.length" size="sm" type="submit" @click="createGroup"> Create Group </Button>
                    <Button v-else type="button" size="sm" @click="stepIndex++"> Next </Button>
                </div>
            </Stepper>
        </DialogContent>
    </Dialog>
</template>

<style scoped>
img {
    transition: transform 0.3s ease;
}

img:hover {
    transform: scale(1.05);
}
</style>
