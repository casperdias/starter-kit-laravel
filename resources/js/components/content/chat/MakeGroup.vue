<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Stepper, StepperDescription, StepperItem, StepperSeparator, StepperTitle } from '@/components/ui/stepper';
import { Textarea } from '@/components/ui/textarea';
import { useForm } from '@inertiajs/vue3';
import { useFileDialog, useObjectUrl } from '@vueuse/core';
import { Check, FileText, Image as ImageIcon, MessagesSquare, Search, Users, UsersRound, X } from 'lucide-vue-next';
import { ref } from 'vue';

const form = useForm({
    name: '',
    type: 'group',
    description: '',
    avatar: null as File | null,
    members: [],
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
];

const searchTerm = ref('');
const isLoading = ref(false);

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
</script>

<template>
    <Dialog>
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
                            <div class="flex-1 space-y-3">
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

                                <p class="text-xs text-muted-foreground">Recommended: Square image, max 2MB. JPG, PNG, or WebP.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Group Name -->
                    <div class="space-y-2">
                        <Label for="name">Group Name</Label>
                        <Input id="name" v-model="form.name" placeholder="Enter group name" :maxlength="50" />
                        <p class="text-xs text-muted-foreground">{{ form.name.length }}/50 characters</p>
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
                </div>

                <div class="flex items-center justify-between">
                    <Button :disabled="stepIndex === 1" variant="outline" size="sm" @click="stepIndex--"> Back </Button>
                    <Button v-if="stepIndex === steps.length" size="sm" type="submit" :disabled="!form.name.trim()"> Create Group </Button>
                    <Button v-else type="button" size="sm" @click="stepIndex++" :disabled="!form.name.trim()"> Next </Button>
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
