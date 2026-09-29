import {defineStore} from 'pinia';
import axios from 'axios';
import type { UnwrapRef } from 'vue';

interface Book {
  id: number;
  title: string;
  author: string;
  status: string;
  rating: number;
}

interface StatusOption {
  label: number;
  value: string;
}
const API_BASE = 'http://127.0.0.1:8000/api';
export const useBookStore = defineStore('books', {
  state: () => ({
    books: [] as Book[],
    statusOptions: [] as StatusOption[],
    currentPage: 1,
    totalPages: 1,
    pageSize: 10,
    searchTerm: '',
  }),
  actions: {
    async fetchBooks() {
      try {
        const response = await axios.get(
          `${API_BASE}/books?page=${this.currentPage}&limit=${this.pageSize}&search=${encodeURIComponent(this.searchTerm)}`,
        );
        console.log('FETCHBOOK()' + response.data);
        this.books = response.data.items;
        this.totalPages = response.data.totalPages;
      } catch (error) {
        console.error(error);
      }
    },
    async fetchBookStatuses() {
      try {
        const response = await axios.get(`${API_BASE}/books/status-options`);
        console.log(response.data);
        this.statusOptions = response.data;
      } catch (error) {
        console.error(error);
      }
    },
    async createBook(bookData: UnwrapRef<{ title: string; author: string; status: string }>) {
      try {
        const response = await axios.post(`${API_BASE}/books`, bookData);
        this.books.push(response.data);
      } catch (error) {
        console.error(error);
      }
    },
    async updateBook(id: number, bookData: Omit<Book, 'id'>) {
      try {
        const response = await axios.put(`${API_BASE}/books/edit/${id}`, bookData);
        console.log(response.data);
        const index = this.books.findIndex((book) => book.id === id);
        if (index !== -1) {
          this.books[index] = response.data;
        }
      } catch (error) {
        console.error(error);
      }
    },
    async deleteBook(id: number) {
      try {
        await axios.delete(`${API_BASE}/books/delete/${id}`);
        const index = this.books.findIndex((book) => book.id === id);
        if (index !== -1) {
          this.books.splice(index, 1);
        }
      } catch (error) {
        console.error(error);
      }
    },
  },
});
