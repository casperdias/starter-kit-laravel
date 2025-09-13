
<script setup lang="ts">
import { ref, watch } from 'vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
// import type { DateValue } from 'your-calendar-lib'; // Update as needed

const emit = defineEmits<{
  (e: 'update:filters', filters: { type: string; date: string }): void;
}>();

const type = ref('');
const date = ref<any>(undefined); // Use correct DateValue type if available

const reset = () => {
  type.value = '';
  date.value = undefined;
};

watch([type, date], () => {
  let dateString = '';
  if (date.value) {
    dateString = typeof date.value === 'string' ? date.value : (date.value.toISOString ? date.value.toISOString().slice(0, 10) : '');
  }
  emit('update:filters', { type: type.value, date: dateString });
});
</script>

<template>
  <div class="flex flex-col gap-2 md:flex-row md:items-center">
    <Select v-model="type">
      <SelectTrigger class="w-36">
        <SelectValue placeholder="Type" />
      </SelectTrigger>
      <SelectContent>
        <SelectItem value="">All Types</SelectItem>
        <SelectItem value="news">News</SelectItem>
        <SelectItem value="announcement">Announcement</SelectItem>
        <SelectItem value="update">Update</SelectItem>
      </SelectContent>
    </Select>
  <Calendar v-model="date" class="w-44" />
  <Button variant="outline" @click="reset">Reset</Button>
  </div>
</template>
