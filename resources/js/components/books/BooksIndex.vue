<template>
    <div>
        <div class="book-actions" v-if="!pageIsLoading">
            <div class="actions-container">
                <div class="action">
                    <select class="custom-select"
                            @change="filterBooks($event)"
                            v-model="booksFilter">
                        <option value="" selected>All books</option>
                        <option value="rented">Rented</option>
                        <option value="non-rented">Non rented</option>
                        <option value="i-rented" v-if="isLogged">I rented</option>
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
                        @pagination-change-page="loadBooks">
                <span slot="prev-nav">Previous</span>
                <span slot="next-nav">Next</span>
            </pagination>
        </div>
    </div>
</template>

<script>
    import BookCreate from "./BookCreate";
    import Spinner from "../partials/Spinner";
    import {
        cropText,
        swalNotification } from "../../libraries/my-libs";
    import { EventBus} from "../../libraries/event-bus";
    export default {
        components: {
            BookCreate,
            Spinner,
        },
        data() {
            return {
                pageIsLoading: true,
                isLogged: false,
                authUser: {},
                booksPagination: {},
                books: [],
                booksFilter: '',
            };
        },
        methods: {
            loadBooks(page = 1) {
                const params = `?page=${page}`;
                const route = `/get/books${params}`;
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
            filterBooks(page = 1) {
                const params = `?page=${page}
                                &booksfilter=${this.booksFilter}`;
                const route = `/get/books${params}`;
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
            loadAuthUser(authUserObject) {
                this.authUser = authUserObject.authUser;
                this.isLogged = authUserObject.isLogged;
            },
            bookShow(bookId) {
                this.$router.push(`/books/${bookId}`);
            },
            cropText(value, limit) {
                return cropText(value, limit);
            }
        },
        created() {
            EventBus.$emit("load-auth-user");
        },
        mounted() {
            this.loadBooks();
            EventBus.$on('load-books', () => this.loadBooks());
            EventBus.$on("auth-user", authUserObject => this.loadAuthUser(authUserObject));
        }
    }
</script>

<style scoped lang="scss">
    .book-actions {
        margin: 0 auto;
        border: 1px solid #ccc;
        background-color: #f8f8f8;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
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
        margin-left: 0.5rem;
        margin-right: 0.5rem;
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
                margin-left: 0.5rem;
                cursor: pointer;
                text-size: 1.5rem;
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
        margin: 1rem auto 0 auto;
    }

    @media (max-width: 768px) {
        .books-container {
            width: 100%;
        }
    }
</style>
