<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogTrigger } from '@/components/ui/dialog';
import { Filter } from 'lucide-vue-next';
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="secondary" class="@container col-span-1 md:col-span-1">
                <Filter />
                <span class="@max-[80px]:hidden"> Filter </span>
            </Button>
        </DialogTrigger>
    </Dialog>
</template>
