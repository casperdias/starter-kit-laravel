<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DialogFooter } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { LoaderCircle, Plus } from 'lucide-vue-next';

interface RoleFormData {
    name: string;
    display_name: string;
    description: string;
}

interface FormErrors {
    name?: string;
    display_name?: string;
    description?: string;
}

defineProps<{
    form: RoleFormData;
    errors: FormErrors;
    processing: boolean;
    submitLabel?: string;
}>();

defineEmits<{
    submit: [];
    'update:form': [form: RoleFormData];
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

            <Label for="display_name">Display Name</Label>
            <Input
                id="display_name"
                :model-value="form.display_name"
                @update:model-value="$emit('update:form', { ...form, display_name: String($event) })"
                type="text"
                class="input"
                required
            />
            <InputError :message="errors.display_name" />

            <Label for="description">Description</Label>
            <Textarea
                id="description"
                :model-value="form.description"
                @update:model-value="$emit('update:form', { ...form, description: String($event) })"
                class="textarea"
            />
            <InputError :message="errors.description" />
        </div>
        <DialogFooter>
            <Button type="submit" :disabled="processing" class="cursor-pointer">
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                <Plus v-else />
                {{ submitLabel || 'Submit' }}
            </Button>
        </DialogFooter>
    </form>
</template>
