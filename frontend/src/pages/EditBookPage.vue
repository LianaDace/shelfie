<template>
  <EditBook v-if="book" :book="book" @saved="router.push('/')"></EditBook>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useBookStore } from '@/stores/bookStore'
import EditBook from '@/components/Books/EditBook.vue'

const route = useRoute()
const router = useRouter()
const bookStore = useBookStore()

const bookId = computed(() => Number(route.params.id))
const book = computed(() => bookStore.books.find((b) => b.id === bookId.value))

onMounted(() => {
  if (!bookStore.books.length) void bookStore.fetchBooks()
})
</script>
