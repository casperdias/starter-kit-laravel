<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Wrench } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import RoleForm from './RoleForm.vue';

const page = usePage();

const props = defineProps<{
    id: number;
}>();

const dialog = ref(false);

const form = useForm({
    name: '',
    display_name: '',
    description: '',
});

// If dialog is opened, axios will fetch the role details based on the ID
watch(dialog, (isOpen) => {
    if (isOpen) {
        axios
            .get(route('admin.roles.edit', props.id))
            .then((response) => {
                form.name = response.data.name;
                form.display_name = response.data.display_name;
                form.description = response.data.description;
            })
            .catch((error) => {
                toast.error('Failed to load role details', {
                    description: error.message || 'An error occurred while fetching the role details.',
                    closeButton: true,
                });
            });
    } else {
        form.reset();
    }
});

const submit = () => {
    form.put(route('admin.roles.update', props.id), {
        onSuccess: () => {
            form.reset();
            dialog.value = false;
            toast.success('Role updated successfully', {
                description: page.props.flash.success || 'The role has been updated successfully.',
                closeButton: true,
            });
        },
        onError: (error) => {
            toast.error('Failed to update role', {
                description: error.message || 'An error occurred while updating the role.',
                closeButton: true,
            });
        },
    });
};
</script>

<template>
    <Dialog v-model:open="dialog">
        <DialogTrigger as-child>
            <Button>
                <Wrench />
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Edit Role</DialogTitle>
                <DialogDescription> Use the form below to edit the role. </DialogDescription>
            </DialogHeader>
            <RoleForm
                :form="{ name: form.name, display_name: form.display_name, description: form.description }"
                :errors="form.errors"
                :processing="form.processing"
                submit-label="Update Role"
                @submit="submit"
                @update:form="
                    (newForm) => {
                        form.name = newForm.name;
                        form.display_name = newForm.display_name;
                        form.description = newForm.description;
                    }
                "
            />
        </DialogContent>
    </Dialog>
</template>
