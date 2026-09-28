<template>
  <q-input v-model="form.title" label="Title" />
  <q-input v-model="form.author" label="Author" />
  <q-input v-model="form.status" label="Status" />
  <q-input v-model="form.rating" label="Review" />
  <q-btn label="Save" @click="save" />
  <h1>Hello Book edit component</h1>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useBookStore } from '@/stores/bookStore';

const bookStore = useBookStore();

interface Book {
  id: number;
  title: string;
  author: string;
  status: string;
  rating: number;
}

const props = defineProps<{ book: Book }>();
const emit = defineEmits<{ (e: 'saved'): void }>();

const form = ref<Book>({ ...props.book });

async function save() {
  await bookStore.updateBook(form.value.id, {
    title: form.value.title,
    author: form.value.author,
    status: form.value.status,
    rating: form.value.rating,
  });
  emit('saved');
}
</script>
