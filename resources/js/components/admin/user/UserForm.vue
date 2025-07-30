<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { LoaderCircle, Plus } from 'lucide-vue-next';

interface UserFormData {
    name: string;
    email: string;
}

interface FormErrors {
    name?: string;
    email?: string;
}

defineProps<{
    form: UserFormData;
    errors: FormErrors;
    processing: boolean;
    submitLabel?: string;
}>();

defineEmits<{
    submit: [];
    'update:form': [form: UserFormData];
}>();
</script>

<template>
    <form @submit.prevent="$emit('submit')" class="grid gap-4">
        <div class="grid gap-4">
            <Label for="name">Name</Label>
            <Input
                id="name"
                :model-value="form.name"
                @update:model-value="$emit('update:form', { ...form, name: String($event) })"
                type="text"
                class="input"
                required
            />
            <InputError :message="errors.name" />

            <Label for="email">Email</Label>
            <Input
                id="email"
                :model-value="form.email"
                @update:model-value="$emit('update:form', { ...form, email: String($event) })"
                type="email"
                class="input"
                required
            />
            <InputError :message="errors.email" />
        </div>
        <div class="flex justify-end">
            <Button type="submit" :disabled="processing">
                <LoaderCircle v-if="processing" class="mr-2 animate-spin" />
                <Plus v-else />
                {{ submitLabel || 'Submit' }}
            </Button>
        </div>
    </form>
</template>
