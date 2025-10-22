<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogTrigger } from '@/components/ui/dialog';
import { UserRound } from 'lucide-vue-next';
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button class="w-full">
                <UserRound />
                <p class="hidden lg:block">Private</p>
            </Button>
        </DialogTrigger>
    </Dialog>
</template>
