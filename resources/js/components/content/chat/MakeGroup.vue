<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogTrigger } from '@/components/ui/dialog';
import { UsersRound } from 'lucide-vue-next';
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button class="w-full">
                <UsersRound />
                <p class="hidden lg:block">Group</p>
            </Button>
        </DialogTrigger>
    </Dialog>
</template>
