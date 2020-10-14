<template>
    <div>
        <div class="book-actions" v-if="!pageIsLoading">
            <div class="actions-container">
                <div class="action">
                    <button class="btn btn-primary" @click="openAddBookModal">Add new book</button>
                </div>
                <div class="action">
                    <select class="custom-select"
                            @change="filterBooks($event)"
                            v-model="booksFilter">
                        <option value="" selected>All books</option>
                        <option value="rented">Rented</option>
                        <option value="non-rented">Non rented</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="books-container">
            <div class="card" v-for="book in books" :key="book.id">
                <img class="card-img-top rounded" :src="book.cover_image" alt="Book cover image">
                <div class="card-body">
                    <div class="title-description">
                        <h5 class="card-title">{{ book.title }} <span class="font-italic text-sm">by {{ book.author_name }}</span></h5>
                        <p class="card-text">{{ cropText(book.description, 200) }}</p>
                    </div>
                    <div class="actions">
                        <i class="fas fa-eye visibility-icon" v-show="book.active" @click="toggleBookVisibility(book.id)"></i>
                        <i class="fas fa-eye-slash visibility-icon" v-show="!book.active" @click="toggleBookVisibility(book.id)"></i>
                        <i class="fas fa-edit visibility-icon" @click="openEditBookModal(book)"></i>
                        <i class="fas fa-book-open visibility-icon" v-if="book.bookRental"></i>
                        <router-link :to="`/books/${book.id}`" class="btn btn-primary float-right">See details</router-link>
                    </div>
                </div>
            </div>
            <spinner style="margin: 3rem auto 0 auto; width: 100%;" v-if="pageIsLoading" />
            <h3 class="text-center mt-3" v-show="!pageIsLoading" v-if="books.length == 0">There are no books.</h3>
        </div>
        <div class="pagination">
            <pagination show-disabled
                        :data="booksPagination"
                        :limit="1"
                        @pagination-change-page="loadMyBooks">
                <span slot="prev-nav">Previous</span>
                <span slot="next-nav">Next</span>
            </pagination>
        </div>
        <book-create />
        <book-edit />
    </div>
</template>

<script>
    import BookCreate from "./BookCreate";
    import BookEdit from "./BookEdit";
    import Spinner from "../partials/Spinner";
    import {
        cropText,
        swalNotification } from "../../libraries/my-libs";
    import { EventBus} from "../../libraries/event-bus";
    export default {
        components: {
            BookCreate,
            BookEdit,
            Spinner,
        },
        data() {
            return {
                pageIsLoading: true,
                booksPagination: {},
                books: [],
                booksFilter: '',
            };
        },
        methods: {
            loadMyBooks(page = 1) {
                const params = `?page=${page}
                                &my_books=1`;
                const route = `/get/books/my${params}`;
                axios.get(route).then(response => {
                    this.pageIsLoading = false;
                    if(response.data.success) {
                        this.booksPagination = response.data.books;
                        this.books = response.data.books.data;
                    }
                }).catch(error => {
                    this.pageIsLoading = false;
                });
            },
            toggleBookVisibility(bookId) {
                const route = `/books/${bookId}/toggle-visibility`;
                axios.post(route).then(response => {
                    this.pageIsLoading = false;
                    if(response.data.success) {
                        swalNotification('success', response.data.message);
                        this.loadMyBooks();
                    }
                }).catch(error => {
                    this.pageIsLoading = false;
                });
            },
            filterBooks(page = 1) {
                const params = `?page=${page}
                                &my_books=1
                                &booksfilter=${this.booksFilter}`;
                const route = `/get/books/my${params}`;
                axios.get(route).then(response => {
                    this.pageIsLoading = false;
                    if(response.data.success) {
                        this.booksPagination = response.data.books;
                        this.books = response.data.books.data;
                    }
                }).catch(error => {
                    this.pageIsLoading = false;
                });
            },
            openAddBookModal() {
                EventBus.$emit('open-add-book-modal');
            },
            openEditBookModal(book) {
                EventBus.$emit('open-edit-book-modal', book);
            },
            bookShow(bookId) {
                this.$router.push(`/books/${bookId}`);
            },
            cropText(value, limit) {
                return cropText(value, limit);
            }
        },
        mounted() {
            this.loadMyBooks();
            EventBus.$on('load-my-books', () => this.loadMyBooks())
        }
    }
</script>

<style scoped lang="scss">
    .book-actions {
        margin: 0 auto;
        border: 1px solid #ccc;
        background-color: #f8f8f8;
        border-top: none;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        height: 4rem;
        width: 90%;
        display: flex;
        flex-direction: row;
        align-items: center;
        .actions-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            .action {
                display: flex;
                flex-direction: row;
                align-items: center;
                margin-left: 1.5rem;
            }
        }
    }
    .card {
        max-width: 20rem;
        margin-top: 1rem;
        margin-left: 1rem;
        box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px;
        transition: box-shadow 0.2s ease-in-out;
        .card-body {
            display: flex;
            flex-direction: column;
        }
        // cursor: pointer;
        img {
            height: 19rem;
        }
        .title-description {
            height: 16rem;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            .visibility-icon {
                cursor: pointer;
                font-size: 1.25rem;
                transition: font-size 0.1s ease-in-out;
                color: #777;
                &:hover {
                    font-size: 1.5rem;
                    color: #17a2b8;
                }
            }
        }
    }
    .card:hover {
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }
    .books-container {
        width: 90%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        margin: 2rem auto 0 auto;
    }
    .pagination {
        margin: 1rem auto 2rem auto;
    }

    @media (max-width: 768px) {
        .books-container {
            width: 100%;
        }
    }
</style>
